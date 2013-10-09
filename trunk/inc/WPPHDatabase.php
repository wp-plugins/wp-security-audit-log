<?php
/**
 * Class WPPHDatabase
 * Internal class handling the creation, update and upgrade of the db tables
 */
class WPPHDatabase
{
    /**
     * @var bool
     * Whether or not we can safely use the plugin
     */
    private static $_canRun = false;

    private static $_canUpgrade = false;

    /**
     * @var string
     * @private
     * Holds the name of the event logs table WITHOUT the db prefix!
     */
    private static $_eventsLogTableBaseName = '_wordpress_eventlog';

    /**
     * @var string
     * @private
     * Holds the name of the events details table WITHOUT the db prefix!
     */
    private static $_eventsDetailsTableBaseName = '_wordpress_eventlog_details';


    private static $_tablesCreated = false;
    private static $_tablesUpgraded = false;
    private static $_tablesUpdated = false;


    //================================================================================================================

    static function handleDatabase()
    {
        // Check database
        $dbUpdated = get_option(WPPH_PLUGIN_DB_UPDATED);
        if(false !== $dbUpdated){
            self::$_tablesCreated = true;
            self::$_tablesUpgraded = true;
            self::$_tablesUpdated = true;
            self::$_canRun = true;
            return true;
        }

        global $wpdb;
        $tableMain = self::getFullTableName('MAIN');
        $tableEvents = self::getFullTableName('EVENTS');

        // Check if tables exist
        if(! self::tableExists($wpdb, $tableMain)){
            if(! self::_createEventLogsTable($wpdb, $tableMain)){
                WPPH::__addPluginError(__("Plugin cannot create tables in the WordPress database to store security audit logs. Allow write access to the WordPress database user temporarily to activate this plugin.
                For more information contact us on support@wpprohelp.com.",WPPH_PLUGIN_TEXT_DOMAIN));
                return false;
            }
        }
        if(! self::tableExists($wpdb, $tableEvents)){
            if(! self::_createEventDetailsTable($wpdb, $tableEvents)){
                WPPH::__addPluginError(__("Plugin cannot create tables in the WordPress database to store security audit logs. Allow write access to the WordPress database user temporarily to activate this plugin.
                For more information contact us on support@wpprohelp.com.",WPPH_PLUGIN_TEXT_DOMAIN));
                return false;
            }
        }
        // Check if tables need to be upgraded
        if(! self::_upgradeEventLogsTable($wpdb, $tableMain)){
            return false;
        }
        if(! self::_upgradeEventDetailsTable($wpdb, $tableEvents)){
            return false;
        }
        // Check if tables need to be updated
        if(! self::_updateEventsDetailsTable($wpdb, $tableEvents)){
            WPPH::__addPluginError(sprintf(__("Error updating table <strong>%s</strong>.",WPPH_PLUGIN_TEXT_DOMAIN), $tableEvents));
            return false;
        }
        if(! self::_updateEventLogsTable($wpdb, $tableMain)){
            WPPH::__addPluginError(sprintf(__("Error updating table <strong>%s</strong>.",WPPH_PLUGIN_TEXT_DOMAIN), $tableMain));
            return false;
        }
        self::$_tablesCreated = true;
        self::$_tablesUpgraded = true;
        self::$_tablesUpdated = true;
        self::$_canRun = true;
        return true;
    }

    static function tableExists($wpdb, $tableFullName)
    {
        $result = $wpdb->get_var("SHOW TABLES LIKE '$tableFullName'");
        return (is_null($result) ? false : true);
    }

    /**
     * Returns the full table name db_prefix + base_table_name for the requested table
     * @param string $what the table identifier. Possible values:
     * main -> to retrieve : db_prefix + self::$_eventsLogTableBaseName
     * events -> to retrieve: db_prefix + self::$_eventsDetailsTableBaseName
     * @return string
     */
    static function getFullTableName($what = 'main')
    {
        global $wpdb;
        if(strcasecmp($what, 'MAIN') == 0){
            return $wpdb->prefix.self::$_eventsLogTableBaseName;
        }
        elseif(strcasecmp($what, 'EVENTS') == 0){
            return $wpdb->prefix.self::$_eventsDetailsTableBaseName;
        }
        return '';
    }

    static function canRun() { return self::$_canRun; }

    /**
     * @internal
     * Prepares the tables for future upgrades from v0.1
     */
    static function v2Cleanup()
    {
        global $wpdb;

        $t1 = self::getFullTableName('MAIN');
        $t2 = self::getFullTableName('EVENTS');

        // empty table 1
        $query = "TRUNCATE ". $wpdb->prefix.self::$_eventsLogTableBaseName;
        if(false === $wpdb->query($query)){
            WPPH::__addPluginError(
                sprintf(
                    __("Plugin could not be properly upgraded because we could not empty the content of the following table: <strong>%s</strong>",WPPH_PLUGIN_TEXT_DOMAIN),$t1)
                );
            self::$_canUpgrade = false;
        }
        else { self::$_canUpgrade = true; }

        // empty table 2
        $query = "TRUNCATE ".$wpdb->prefix.self::$_eventsDetailsTableBaseName;
        if(false === $wpdb->query($query)){
            WPPH::__addPluginError(
                sprintf(__("Plugin could not be properly upgraded because we could not empty the content of the following table: <strong>%s</strong>",WPPH_PLUGIN_TEXT_DOMAIN),$t2)
            );
            self::$_canUpgrade = false;
        }
        else { self::$_canUpgrade = true; }

        return self::$_canUpgrade;
    }


    private static function _createEventLogsTable($wpdb, $tableFullName)
    {
        $query = "CREATE TABLE IF NOT EXISTS `$tableFullName` (
                  `EventNumber` bigint(40) NOT NULL AUTO_INCREMENT,
                  `EventID` int(8) NOT NULL,
                  `EventDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `UserID` int(8) NOT NULL DEFAULT '0',
                  `UserIP` varchar(24) NOT NULL DEFAULT '0.0.0.0',
                  `EventData` TEXT NOT NULL,
                  PRIMARY KEY (`EventNumber`),
                  UNIQUE KEY `EventNumber` (`EventNumber`)
                );";
        if(false === @$wpdb->query($query)){return false;}
        return true;
    }
    private static function _createEventDetailsTable($wpdb, $tableFullName)
    {
        $query = "CREATE TABLE IF NOT EXISTS `$tableFullName` (
              `EventID` int(8) NOT NULL,
              `EventType` varchar(10) DEFAULT 'NOTICE',
              `EventDescription` text NOT NULL,
              PRIMARY KEY (`EventID`),
              UNIQUE KEY `EventID` (`EventID`)
            );";
        if (false === @$wpdb->query($query)){ return false; }
        return true;
    }
    private static function _upgradeEventLogsTable($wpdb, $tableFullName)
    {
        $q = "SHOW COLUMNS FROM $tableFullName LIKE 'EventCount';";
        $rowData = $wpdb->get_row($q, ARRAY_A);
        if(empty($rowData['Field']))
        {
            $q = "ALTER TABLE $tableFullName ADD COLUMN `EventCount` INT NOT NULL DEFAULT 1  AFTER `EventData`;";
            $result = @$wpdb->query($q);
            if($result === false){
                WPPH::__addPluginError(
                    sprintf(__("Plugin could not be properly installed. The db user used to connect to the WordPress database is missing the <strong>ALTER</strong> right for query: <strong>%s</strong>",WPPH_PLUGIN_TEXT_DOMAIN),$q)
                );
                return false;
            }
            $q = "ALTER TABLE $tableFullName ADD COLUMN `UserName` VARCHAR(125) NOT NULL DEFAULT ''  AFTER `EventCount`;";
            $result = @$wpdb->query($q);
            if($result === false){
                WPPH::__addPluginError(
                    sprintf(__("Plugin could not be properly installed. The db user used to connect to the WordPress database is missing the <strong>ALTER</strong> right for query: <strong>%s</strong>",WPPH_PLUGIN_TEXT_DOMAIN),$q)
                );
                return false;
            }
        }
        return true;
    }
    private static function _upgradeEventDetailsTable($wpdb, $tableFullName)
    {
        return true;
    }
    /**
     * This function will insert the default rows in the events details table
     */
    private static function _updateEventsDetailsTable($wpdb, $tableFullName)
    {
        $queries = array();
        $events = WPPHEvent::listEvents();
        if(empty($events)){ return true; }

        // check for differences
        $numFileEvents = count($events);
        $numDbEvents = (int)$wpdb->get_var("SELECT COUNT(EventID) FROM $tableFullName;");

        // no update necessary
        if($numFileEvents == $numDbEvents){
            return true;
        }

        foreach($events as $entry)
        {
            $q = sprintf("INSERT INTO $tableFullName (`EventID`,`EventType`,`EventDescription`) VALUES(%d,'%s','%s')", $entry['id'], $entry['category'], $entry['text']);
            $queries["{$entry['id']}"] = $q;
        }

        // Clear table
        if($numDbEvents > 0){
            $result = @$wpdb->query("TRUNCATE $tableFullName");
            if($result === false){
                WPPH::__addPluginError(
                    sprintf(
                        __("Could not empty table <strong>%s</strong>. Please run the following query manually: <strong>TRUNCATE %s</strong>",WPPH_PLUGIN_TEXT_DOMAIN)
                        ,$tableFullName, $tableFullName)
                    );
                return false;
            }
        }
        // Insert data
        foreach($queries as $id => $query){
            if(! empty($query)){
                if(false === @$wpdb->query($query)){
                    wpphLog('QUERY FAILED TO RUN: ',$query);
                    WPPH::__addPluginError(
                        sprintf(
                            __("Error updating table <strong>%s</strong> using query: <strong>%s</strong>",WPPH_PLUGIN_TEXT_DOMAIN)
                            ,$tableFullName, $query)
                        );
                    return false;
                }
            }
        }
        return true;
    }

    private static function _updateEventLogsTable($wpdb, $tableFullName)
    {
        return true;
    }
}

/**
 * Class WPPHDB
 * Contains utility methods to communicate with the database
 */
class WPPHDB extends WPPHDatabase
{
    /**
     * @return string The current logged in user's role
     */
    static function getCurrentUserRole()
    {
        global $current_user;
        get_currentuserinfo();
        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);
        return $user_role;
    }
    // returns array(userName, userRole)
    static function getUserInfo($userID)
    {
        global $wpdb;

        $t = $wpdb->users;

        $username = $wpdb->get_var("SELECT user_login FROM $t WHERE ID=$userID");
        $user = new WP_User( $userID );
        $userRole = (empty($user->roles[0]) ? '' : $user->roles[0]);

        return array(
            'userName' => $username,
            'userRole' => $userRole
        );
    }

    /**
     * Retrieve the total number of events from db
     * @return int
     */
    static function getEventsCount()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(EventNumber) FROM ".self::getFullTableName('main'));
    }
}