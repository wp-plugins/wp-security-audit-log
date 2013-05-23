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
    private static $_canRun = true;

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


    public static function checkTables()
    {
//#! EVENT DETAILS TABLE
        if(! self::_createEventDetailsTable()){
            WPPHAdminNotices::show(0);
            return false;
        }
        if(! self::_updateEventsDetailsTable()){
            WPPHAdminNotices::show(1);
            return false;
        }
        if(! self::_upgradeEventDetailsTable()){
            WPPHAdminNotices::show(2);
            return false;
        }

//#! EVENT LOGS MAIN TABLE
        if(! self::_createEventLogsTable()){
            WPPHAdminNotices::show(3);
            return false;
        }
        if(! self::_updateEventLogsTable()){
            WPPHAdminNotices::show(4);
            return false;
        }
        if(! self::_upgradeEventLogsTable()){
            WPPHAdminNotices::show(5);
            return false;
        }

        self::$_canRun = true;
        return true;
    }

    public static function canRun() { return self::$_canRun; }

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
        $tableName = $wpdb->prefix.self::$_eventsDetailsTableBaseName;
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
        global $wpdb;
        $tableName = $wpdb->prefix.self::$_eventsDetailsTableBaseName;

        $out = array();
        $entries = WPPHEvent::listEvents();
        if(empty($entries)){ return $out; }

        foreach($entries as $entry)
        {
            $q = sprintf("INSERT INTO `%s` (`EventID`,`EventType`,`EventDescription`) VALUES(%d,'%s','%s')", $tableName, $entry['id'], $entry['category'], $entry['text']);
            $out["{$entry['id']}"] = $q;
        }
        return $out;
    }

    //@todo: UPDATE AS NECESSARY
    public static function getUpgradeQueryEventsDetailsTable()
    {
        return '';
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

    public static function getUpdateQueryLogsTable()
    {
        return '';
    }
    public static function getUpgradeQueryLogsTable()
    {
        return '';
    }


    private static function _createEventDetailsTable()
    {
        if(self::_eventDetailsTableExists()) { return true; }
        global $wpdb;
        $query = self::getCreateQueryEventsDetailsTable();
        if (false === $wpdb->query($wpdb->prepare($query))){ return false; }
        return true;
    }

    /**
     * This function will insert the default rows in the events details table
     */
    private static function _updateEventsDetailsTable()
    {
        global $wpdb;
        $queries = self::getUpdateQueryEventsDetailsTable();
        foreach($queries as $id => $query){
            if(! empty($query)){
                $var = $wpdb->get_var("SELECT EventID FROM ".self::getFullTableName('events')." WHERE EventID = $id");
                if(empty($var)){
                    if(false === $wpdb->query($query)){
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
        return true;
    }


    private static function _createEventLogsTable()
    {
        if(self::_eventLogsTableExists()){ return true;}
        global $wpdb;
        $query = self::getCreateQueryLogsTable();
        if(false === $wpdb->query($wpdb->prepare($query))){return false;}
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
        return true;
    }

    private static function _eventLogsTableExists()
    {
        global $wpdb;
        $tableName = $wpdb->prefix.self::$_eventsLogTableBaseName;
        $result = $wpdb->get_var($wpdb->prepare('SELECT EventNumber FROM '.$tableName));
        return (is_null($result) ? false : true);

    }
    private static function _eventDetailsTableExists()
    {
        global $wpdb;
        $tableName = $wpdb->prefix.self::$_eventsDetailsTableBaseName;
        $result = $wpdb->get_var($wpdb->prepare('SELECT EventID FROM '.$tableName));
        return (is_null($result) ? false : true);
    }

    //!! TODO: CHECK
    public static function userHasAccessRights()
    {
        global $wpdb;

        $rights = $wpdb->get_results("SHOW GRANTS FOR CURRENT_USER()", ARRAY_N);

        if(empty($rights)) return false;

        foreach($rights as $right){
            if(!empty($right[0])){
                $r = strtoupper($right[0]);
                if (preg_match("/GRANT ALL PRIVILEGES/i", $r)) { return true; }
                else{
                    if (preg_match_all("/CREATE|DELETE|ALTER|INSERT|UPDATE|SELECT|DELETE/i", $r, $matches)){
                        if (! empty($matches[0])){
                            $m = $matches[0];
                            $m = array_unique($m);
                            if (count($m) >= 5){ return true; }
                        }
                    }
                }
            }
        }
        return false;
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

        $t = $wpdb->prefix.'users';

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
        $result = $wpdb->get_var("SELECT COUNT(EventNumber) FROM ".self::getFullTableName('main'));
        return intval($result);
    }

}

