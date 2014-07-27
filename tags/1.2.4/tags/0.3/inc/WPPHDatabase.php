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

    /**
     * @internal
     * Prepares the tables for future upgrades from v0.1
     */
    public static function v2Cleanup()
    {
        global $wpdb;

        // empty table 1
        if(self::_eventLogsTableExists())
        {
            $query = "TRUNCATE ".$wpdb->prefix.self::$_eventsLogTableBaseName;

            if(false === $wpdb->query($query)){
                self::$_canUpgrade = false;
            }
            else { self::$_canUpgrade = true; }
        }
        else { self::$_canUpgrade = true; }

        // empty table 2
        if(self::_eventDetailsTableExists())
        {
            $query = "TRUNCATE ".$wpdb->prefix.self::$_eventsDetailsTableBaseName;

            if(false === $wpdb->query($query)){
                self::$_canUpgrade = false;
            }
            else { self::$_canUpgrade = true; }
        }
        else { self::$_canUpgrade = true; }

        return self::$_canUpgrade;
    }


    public static function handleTables()
    {
        if(! self::tablesExist())
        {
            $result = WPPHDatabase::createTables();
            if($result !== true){
                return $result;
            }
        }

        $result = self::upgradeTables();
        if($result !== true){
            return $result;
        }

        $result = self::updateTables();
        if($result !== true){
            return $result;
        }

        update_option(WPPH_PLUGIN_DB_UPDATED,1);
        return true;
    }

    // check to see whether or not the tables exist in the database
    public static function tablesExist()
    {
        global $wpdb;
        $tables = $wpdb->get_results("SHOW TABLES;",ARRAY_N);
        $t1 = self::getFullTableName('main');
        $t2 = self::getFullTableName('events');
        $r1 = $r2 = false;
        foreach($tables as $table){
            if(strcasecmp($t1, $table[0])==0){ $r1 = true; }
            elseif(strcasecmp($t2, $table[0])==0){ $r2 = true; }
        }
        if($r1 == true && $r2 == true){
            self::$_tablesCreated = true;
            return true;
        }
        return false;
    }

    public static function createTables()
    {
        global $wpdb;
        if(! self::_eventDetailsTableExists()) {
            $query = self::getCreateQueryEventsDetailsTable();
            if (false === @$wpdb->query($query)){ return 0; }
        }
        if(! self::_eventLogsTableExists()){
            $query = self::getCreateQueryLogsTable();
            if(false === @$wpdb->query($query)){return 3;}
        }
        return true;
    }

    public static function upgradeTables()
    {
        wpphLog(__FUNCTION__.'() triggered.');
        $optData = get_option(WPPH_PLUGIN_DB_UPDATED);
        if($optData !== false){
            wpphLog('Database is already updated.');
            if($optData == 1){ return true; }
        }

        if(! @self::_upgradeEventDetailsTable()){
            return 2;
        }
        if(! @self::_upgradeEventLogsTable()){
            return 5;
        }
        self::$_tablesUpgraded = true;
        return true;
    }

    public static function updateTables()
    {
        if(! @self::_updateEventsDetailsTable()){
            return 1;
        }
        if(! @self::_updateEventLogsTable()){
            return 4;
        }
        self::$_tablesUpdated = true;
        return true;
    }

    public static function canRun() {
        if(self::$_tablesCreated && self::$_tablesUpgraded && self::$_tablesUpdated){
            self::$_canRun = true;
        }
        return self::$_canRun;
    }


    /**
     * Returns the full table name db_prefix + base_table_name for the requested table
     * @param string $what the table identifier. Possible values:
     * main -> to retrieve : db_prefix + self::$_eventsLogTableBaseName
     * events -> to retrieve: db_prefix + self::$_eventsDetailsTableBaseName
     * @return string
     */
    public static function getFullTableName($what = 'main')
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

    public static function getCreateQueryEventsDetailsTable()
    {
        global $wpdb;
        $tableName = self::getFullTableName('events');
        return "CREATE TABLE IF NOT EXISTS `$tableName` (
              `EventID` int(8) NOT NULL,
              `EventType` varchar(10) DEFAULT 'NOTICE',
              `EventDescription` text NOT NULL,
              PRIMARY KEY (`EventID`),
              UNIQUE KEY `EventID` (`EventID`)
            );";
    }

    public static function getUpdateQueryEventsDetailsTable()
    {
        $out = array();
        $entries = WPPHEvent::listEvents();
        if(empty($entries)){ return $out; }

        foreach($entries as $entry)
        {
            $q = sprintf("INSERT INTO ".self::getFullTableName('events')." (`EventID`,`EventType`,`EventDescription`) VALUES(%d,'%s','%s')", $entry['id'], $entry['category'], $entry['text']);
            $out["{$entry['id']}"] = $q;
        }
        return $out;
    }

    //@todo: UPDATE AS NECESSARY
    public static function getUpgradeQueryEventsDetailsTable()
    {
        return array();
    }


    public static function getCreateQueryLogsTable()
    {
        global $wpdb;
        $t1 = $wpdb->prefix.self::$_eventsLogTableBaseName;
        return "CREATE TABLE IF NOT EXISTS `$t1` (
                  `EventNumber` bigint(40) NOT NULL AUTO_INCREMENT,
                  `EventID` int(8) NOT NULL,
                  `EventDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  `UserID` int(8) NOT NULL DEFAULT '0',
                  `UserIP` varchar(24) NOT NULL DEFAULT '0.0.0.0',
                  `EventData` TEXT NOT NULL,
                  PRIMARY KEY (`EventNumber`),
                  UNIQUE KEY `EventNumber` (`EventNumber`)
                );";
    }
    // todo: add more events to db here
    public static function getUpdateQueryLogsTable()
    {
        return '';
    }
    public static function getUpgradeQueryLogsTable()
    {
        return array(
            "ALTER TABLE ".self::getFullTableName('main')." ADD COLUMN `EventCount` INT NOT NULL DEFAULT 1  AFTER `EventData`;",
            "ALTER TABLE ".self::getFullTableName('main')." ADD COLUMN `UserName` VARCHAR(125) NOT NULL DEFAULT ''  AFTER `EventCount`;",
        );
    }


    private static function _createEventDetailsTable()
    {
        if(self::_eventDetailsTableExists()) { return true; }
        global $wpdb;
        $query = self::getCreateQueryEventsDetailsTable();
        if (false === @$wpdb->query($query)){ return false; }
        return true;
    }

    /**
     * This function will insert the default rows in the events details table
     */
    private static function _updateEventsDetailsTable()
    {
        global $wpdb;

        $queries = self::getUpdateQueryEventsDetailsTable();
        if(empty($queries)){
            return true;
        }

        foreach($queries as $id => $query){
            if(! empty($query)){
                $var = @$wpdb->get_var("SELECT EventID FROM ".self::getFullTableName('events')." WHERE EventID = $id");
                if(empty($var)){
                    if(false === @$wpdb->query($query)){
                        wpphLog('QUERY FAILED TO RUN: ',$query);
                        return false;
                    }
                }
            }
        }
        return true;
    }

    //TODO: UPDATE AS NECESSARY
    private static function _upgradeEventDetailsTable()
    {
        //EXECUTE THE QUERY FROM self::getUpgradeQueryEventsDetailsTable();
        $queries = self::getUpgradeQueryEventsDetailsTable();
        if(empty($queries)){ return true; }

        global $wpdb;
        foreach($queries as $query){
            if(false === @$wpdb->query($query)){return false;}
        }
        return true;
    }


    private static function _createEventLogsTable()
    {
        if(self::_eventLogsTableExists()){ return true;}
        global $wpdb;
        $query = self::getCreateQueryLogsTable();
        if(false === @$wpdb->query($query)){return false;}
        return true;
    }

    private static function _updateEventLogsTable()
    {
        return true;
    }

    //TODO: UPDATE AS NECESSARY
    private static function _upgradeEventLogsTable()
    {
        //EXECUTE THE QUERY FROM self::getUpgradeQueryLogsTable();
        $queries = self::getUpgradeQueryLogsTable();
        if(empty($queries)){ return true;}
        global $wpdb;

        foreach($queries as $query){
            if(false === @$wpdb->query($query)){return false;}
        }
        return true;
    }

    private static function _eventLogsTableExists()
    {
        global $wpdb;
        $result = @$wpdb->get_var('SELECT EventNumber FROM '.self::getFullTableName('main'));
        return (is_null($result) ? false : true);

    }
    private static function _eventDetailsTableExists()
    {
        global $wpdb;
        $result = @$wpdb->get_var('SELECT EventID FROM '.self::getFullTableName('events'));
        return (is_null($result) ? false : true);
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
    public static function getCurrentUserRole()
    {
        global $current_user;
        get_currentuserinfo();
        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);
        return $user_role;
    }
    // returns array(userName, userRole)
    public static function getUserInfo($userID)
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
    public static function getEventsCount()
    {
        global $wpdb;
        return $wpdb->get_var("SELECT COUNT(EventNumber) FROM ".self::getFullTableName('main'));
    }

}

