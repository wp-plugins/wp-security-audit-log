<?php
/**
 * Class that will handle the events management
 * @kyos
 */
class WPPHEvent
{
    public static function bindHooks(array $hooks = array())
    {
        if(empty($hooks)){ return; }
        foreach($hooks as $hook){
            if(is_callable(array(self,$hook),true)){
                call_user_func(array(self,$hook));
            }
        }
    }
    /**
     * Retrieve the list of events
     * @return array
     */
    public static function listEvents()
    {
        return array(
// 1xxx - Login/Logout events
            array( 'id' => 1000, 'category' => 'NOTICE', 'text' => __('Successfully logged in.') ),
            array( 'id' => 1001, 'category' => 'NOTICE', 'text' => __('Successfully logged out.') ),
            array( 'id' => 1002, 'category' => 'WARNING', 'text' => __('Failed Login detected using <strong>%s</strong> as username.') ),

// 2xxx - User activity events
            //  Created a new blog post called %Post Title%. Blog post ID is %ID%
            array( 'id' => 2000, 'category' => 'NOTICE', 'text' => __('Created a new draft blog post called <strong>%s</strong>. Blog post ID is <strong>%d</strong>.') ),
            // Published a blog post called %Post_Title%. Blog post URL is %Post_URL%
            array( 'id' => 2001, 'category' => 'NOTICE', 'text' => __('Published a blog post called <strong>%s</strong>. Blog post URL is <strong>%s</strong>.') ),
            // Modified the published blog post %post_title%. Blog post URL is %post_URL%
            array( 'id' => 2002, 'category' => 'NOTICE', 'text' => __('Modified the published blog post <strong>%s</strong>. Blog post URL is <strong>%s</strong>.') ),
            // Modified the draft blog post %post_title%. Blog post ID is %ID%
            array( 'id' => 2003, 'category' => 'NOTICE', 'text' => __('Modified the draft blog post <strong>%s</strong>. Blog post ID is <strong>%d</strong>.') ),

            // Created a new page called %page_title%. Page ID is %ID%
            array( 'id' => 2004, 'category' => 'NOTICE', 'text' => __('Created a new draft page called <strong>%s</strong>. Page ID is <strong>%d</strong>.') ),
            // Published a page called %page_title%. Page URL is %URL%
            array( 'id' => 2005, 'category' => 'NOTICE', 'text' => __('Published a page called <strong>%s</strong>. Page URL is <strong>%s</strong>.') ),
            // Modified the published page %page_title%. Page URL is %URL%
            array( 'id' => 2006, 'category' => 'NOTICE', 'text' => __('Modified the published page <strong>%s</strong>. Page URL is <strong>%s</strong>.') ),
            // Modified the draft page %page_title%. Page ID is %ID%
            array( 'id' => 2007, 'category' => 'NOTICE', 'text' => __('Modified the draft page <strong>%s</strong>. Page ID is <strong>%d</strong>.') ),
            // Deleted the post %Title%. Blog post ID is %ID%
            array( 'id' => 2008, 'category' => 'HIGH', 'text' => __('Deleted the post <strong>%s</strong>. Blog post ID is <strong>%d</strong>.') ),
            // Deleted the page %Title%. Page ID is %ID%
            array( 'id' => 2009, 'category' => 'HIGH', 'text' => __('Deleted the page <strong>%s</strong>. Page ID is <strong>%d</strong>.') ),

            // Uploaded the file %file name$ in %file location%
            array( 'id' => 2010, 'category' => 'NOTICE', 'text' => __('Uploaded the file <strong>%s</strong> in <strong>%s</strong>/.') ),
            // Deleted file %file name$ from %file_location%
            array( 'id' => 2011, 'category' => 'HIGH', 'text' => __('Deleted the file <strong>%s</strong> from <strong>%s</strong>/.') ),
            // 2012 - trashed draft post
            array( 'id' => 2012, 'category' => 'HIGH', 'text' => __('Moved the post <strong>%s</strong> to trash.') ),
            // 2013 - trashed published post
            array( 'id' => 2013, 'category' => 'HIGH', 'text' => __('Moved the page <strong>%s</strong> to trash.') ),
            // 2014 - untrashed post
            array( 'id' => 2014, 'category' => 'HIGH', 'text' => __('Post <strong>%s</strong> has been restored from trash.') ),
            // 2015 - untrashed page
            array( 'id' => 2015, 'category' => 'HIGH', 'text' => __('Page <strong>%s</strong> has been restored from trash.') ),

// 3xxx - Themes management
            // Activated the theme %themeName%
            array( 'id' => 3000, 'category' => 'NOTICE', 'text' => __('Activated the theme <strong>%s</strong>.') ),

// 4xxx - User profile events
            array( 'id' => 4000, 'category' => 'HIGH', 'text' => __('A new user with the username <strong>%s</strong> has registered with the role of <strong>%s</strong>.') ),
            array( 'id' => 4001, 'category' => 'HIGH', 'text' => __('<strong>%s</strong> created a new user <strong>%s</strong> with the role of <strong>%s</strong>.') ),
            array( 'id' => 4002, 'category' => 'HIGH', 'text' => __('The role of user <strong>%s</strong> was changed from <strong>%s</strong> to <strong>%s</strong> by <strong>%s</strong>.') ),
            array( 'id' => 4003, 'category' => 'HIGH', 'text' => __('Changed the account password.') ),
            array( 'id' => 4004, 'category' => 'HIGH', 'text' => __('<strong>%s</strong> changed the password for user <strong>%s</strong> with the role of <strong>%s</strong>.') ),
            // Changed the email address from %old_email% to %new_email%
            array( 'id' => 4005, 'category' => 'NOTICE', 'text' => __('Changed the email address from <strong>%s</strong> to <strong>%s</strong>.') ),
            // %user_making_change% changed the email address of user %user% from %old_email% to %new_email%
            array( 'id' => 4006, 'category' => 'NOTICE', 'text' => __('<strong>%s</strong> changed the email address of user <strong>%s</strong> from <strong>%s</strong> to <strong>%s</strong>.') ),
            // User %user% with the role of %role% was deleted by %user_deleting%
            array( 'id' => 4007, 'category' => 'HIGH', 'text' => __('User <strong>%s</strong> with the role of <strong>%s</strong> was deleted by <strong>%s</strong>.') ),

// 5xxx - Plugin management
            // Activated the plugin %plugin_name% installed in %plugin_directory%
            array( 'id' => 5000, 'category' => 'HIGH', 'text' => __('Activated the plugin <strong>%s</strong> installed in /<strong>%s</strong>.') ),
            // Deactivated the plugin %plugin_name% installed in %plugin_directory%
            array( 'id' => 5001, 'category' => 'HIGH', 'text' => __('Deactivated the plugin <strong>%s</strong> installed in /<strong>%s</strong>.') ),

// 6xxx - System events
            //
            array( 'id' => 6000, 'category' => 'NOTICE', 'text' => __('Events automatically deleted by system.') ),
        );
    }


// 1xxx - Login/Logout events

    // 1000
    public static function hookLoginEvent()   { add_action('wp_login', array('WPPHEventWatcher', 'watchEventLogin'), 10, 2); }
    // 1001
    public static function hookLogoutEvent()  { add_action('wp_logout', array('WPPHEventWatcher', 'watchEventLogout')); }
    // 1002
    public static function hookLoginFailure() { add_action('wp_login_failed', array('WPPHEventWatcher', 'watchLoginFailure')); }


// 2xxx - User activity events

    // 2000, 2001, 2002, 2003, 2004, 2005, 2006, 2007,
    public static function hookWatchBlogActivity() { add_action('transition_post_status', array('WPPHEventWatcher', 'WatchBlogActivity'), 10, 3); }
    // 2008, 2009
    public static function hookFileDeletion() { add_action('delete_post', array('WPPHEventWatcher', 'watchTrash'), 10, 1); }
    // 2010
    public static function hookFileUploaded() { add_action('add_attachment', array('WPPHEventWatcher', 'watchFileUploaded')); }
    // 2011
    public static function hookFileUploadedDeleted() { add_action('delete_attachment', array('WPPHEventWatcher', 'watchFileUploadedDeleted')); }
    // 2012
    public static function hookTrashPost() {
        if(defined('EMPTY_TRASH_DAYS') && (EMPTY_TRASH_DAYS == 0)){
            add_action('delete_post', array('WPPHEventWatcher', 'watchTrash'), 10, 1);
        }
        else { add_action('trash_post', array('WPPHEventWatcher', 'watchFileDeletion')); }
    }
    // 2013
    public static function hookTrashPage() {
        if(defined('EMPTY_TRASH_DAYS') && (EMPTY_TRASH_DAYS == 0)){
            add_action('delete_post', array('WPPHEventWatcher', 'watchTrash'), 10, 1);
        }
        else { add_action('trash_page', array('WPPHEventWatcher', 'watchFileDeletion')); }
    }
    //2014
    public static function hookUntrashedPosts() { add_action('untrash_post', array('WPPHEventWatcher', 'watchTrashUndo')); }
    // 2015
    public static function hookUntrashedPages() { add_action('untrash_page', array('WPPHEventWatcher', 'watchTrashUndo')); }

// 3xxx - Themes management

    // 3000
    public static function hookThemeChange() { add_action('switch_theme', array('WPPHEventWatcher', 'watchThemeChange'));}


// 4xxx - User profile events

    // 4000, 4001
    public static function hookUserRegisterEvent() { add_action('user_register', array('WPPHEventWatcher', 'watchEventUserRegister')); }
    // 4002
    public static function hookUserRoleUpdated() {
        add_action('edit_user_profile_update', array('WPPHEventWatcher', 'watchUserRoleUpdated'));
        add_action('personal_options_update', array('WPPHEventWatcher', 'watchUserRoleUpdated'));
    }
    // 4003, 4004
    public static function hookUserPasswordUpdated() {
        add_action('edit_user_profile_update', array('WPPHEventWatcher', 'watchUserPasswordUpdated'));
        add_action('personal_options_update', array('WPPHEventWatcher', 'watchUserPasswordUpdated'));
    }
    // 4005, 4006
    public static function hookUserEmailUpdated() {
        add_action('edit_user_profile_update', array('WPPHEventWatcher', 'watchUserEmailUpdated'));
        add_action('personal_options_update', array('WPPHEventWatcher', 'watchUserEmailUpdated'));
    }
    // 4007
    public static function hookUserDeletion() { add_action( 'delete_user', array('WPPHEventWatcher', 'watchUserDeletion') ); }


// 5xxx - Plugin management

    // 5000, 5001
    public static function hookWatchPluginActivity() {
        @require_once(ABSPATH.'wp-admin/includes/plugin.php');
        $current = get_plugins();
        foreach($current as $plugin_file => $plugin_data) {
            if ($plugin_file == WPPH_PLUGIN_BASE_NAME) {
                continue;
            }
            add_action('activate_' . $plugin_file, array('WPPHEventWatcher', 'watchPluginActivate'));
            add_action('deactivate_' . $plugin_file, array('WPPHEventWatcher', 'watchPluginDeactivate'));
        }
    }


// 6xxx - System events

    // 6000
    public static function hookEventsDeletion() { add_action('init', array('WPPHEventWatcher', 'watchDeleteEvents')); }


    /**
     * Add log event. Internal function. Don not use outside class scope.
     * @internal
     * @static
     * @param int $eventID
     * @param int $userID . A value of 0 means user "System". This is the ID of the user triggering the alert.
     * @param string $userIP
     * @param string $eventData Optional. If provided should be as a serialized array.
     * @return bool
     */
    public static function _addLogEvent($eventID = 1000, $userID = 0, $userIP = '', $eventData = '')
    {
        if(empty($userIP)){ $userIP = WPPHUtil::getIP(); }

        global $wpdb;
        $query = sprintf("INSERT INTO %s (EventID, UserID, UserIP, EventData) VALUES(%d, %d, '%s', '%s')"
            ,WPPHDatabase::getFullTableName('MAIN'), $eventID, $userID, $userIP, $eventData);
        if(false === $wpdb->query($wpdb->prepare($query)))
        {
            // mysql error
            return false;
        }
        return true;
    }


/*
 * PUBLIC METHODS
 * ============================================
 */

    public static function getEventDetailsData($eventID)
    {
        global $wpdb;
        $table = WPPHDatabase::getFullTableName('events');
        return $wpdb->get_row($wpdb->prepare("SELECT EventType, EventDescription FROM $table WHERE EventID = $eventID"));
    }

    /**
     * Retrieve the events from db.
     * @param array $limit
     * @param string $orderBy. Must be a valid column name. Defaults to EventNumber
     * @param string $sort  ASC or DESC
     * @return mixed
     */
    public static function getEvents($orderBy='EventNumber', $sort = 'DESC', $limit = array(0,0))
    {
        $validArgsSort = array('ASC', 'DESC');
        $validCnTableLogDetails = array('EventID', 'EventType');
        $validCnTableLog = array('EventNumber', 'EventDate', 'UserID', 'UserIP');

        $l0 = 0;
        $l1 = 1;
        if(isset($limit[0]) && ($limit[0] >= 0)){ $l0 = intval($limit[0]); }
        if(isset($limit[1]) && ($limit[1] >= 1)){ $l1 = intval($limit[1]); }
        $limit = "$l0,$l1";

        $sort = strtoupper($sort);
        if(empty($sort) || !in_array($sort, $validArgsSort)) { $sort = $validArgsSort[1]; }

        if(! empty($orderBy)){
            if(in_array($orderBy, $validCnTableLog)){
                $orderBy = 'le.'.$orderBy;
            }
            elseif(in_array($orderBy, $validCnTableLogDetails)){
                $orderBy = 'led.'.$orderBy;
            }
        }
        else { $orderBy = 'le.EventNumber'; }

        $t1 = WPPHDatabase::getFullTableName('main');
        $t2 = WPPHDatabase::getFullTableName('events');
        $querySelect = "SELECT le.EventNumber, le.EventID, le.EventDate, le.UserID, le.UserIP, le.EventData,
                          led.EventType, led.EventDescription
                        FROM `$t1` as le
                          INNER JOIN `$t2` as led
                            ON le.EventID = led.EventID
                              ORDER BY $orderBy
                              $sort
                              LIMIT $limit;";
        global $wpdb;
        return $wpdb->get_results($wpdb->prepare($querySelect), ARRAY_A);
    }


}

/**
 * Class WPPHEventWatcher
 * This class provides callable methods that are called inside the hooks registered
 * in the WPPHEvent class. All methods are internal and should not be used outside
 * scope.
 * @static
 * @internal
 */
class WPPHEventWatcher extends WPPHEvent
{
    /**
     * @internal
     * Hooks to the login event
     * @param $user_login
     * @param WP_User $user
     */
    public static function watchEventLogin($user_login, $user)
    {
        wpphLog(__METHOD__.'() triggered by hook.');
        self::_addLogEvent(1000, $user->ID);
    }
    /**
     * @internal
     * Hooks to the logout event
     */
    public static function watchEventLogout()
    {
        wpphLog(__METHOD__.'() triggered by hook.');
        self::_addLogEvent(1001, wp_get_current_user()->ID);
    }

    /**
     * @internal
     * Hooks to the user register event
     */
    public static function watchEventUserRegister($user_id)
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        global $current_user;
        get_currentuserinfo();

        $un = (empty($current_user->user_login) ? 'System' : $current_user->user_login);
        $uInfo = WPPHDB::getUserInfo($user_id);
        $nu = $uInfo['userName'];
        $nur = ucfirst($uInfo['userRole']);

        // %s created new user %s with role %s
        $eventData = serialize(array($un, $nu, $nur));

        if($un == 'System')
        {
            // A new user with the username %username% has registered with the role of %user_role%
            $eventData = serialize(array($nu, $nur));
            self::_addLogEvent(4000, 0, WPPHUtil::getIP(), $eventData);
        }
        else {
            // %s created new user %s with role %s
            $eventData = serialize(array($un, $nu, $nur));
            self::_addLogEvent(4001, $current_user->ID, WPPHUtil::getIP(), $eventData);
        }
    }

    /**
     * @internal
     * Hooks to the events deletion event
     */
    public static function watchDeleteEvents()
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        // check settings and delete the events (if any)
        $settings = WPPH::getPluginSettings();
        if($settings->cleanupRan == 1){
            wpphLog(__METHOD__.'() Ignored. Cleanup already ran today.');
            return;
        }
        // check to see how we should do the cleanup (by days or by number)
        $cleanupType = 1; // by number by default

        if(!empty($settings->daysToKeep)){
            $cleanupType = 0;
        }

        // by days
        if($cleanupType == 0)
        {
            if(self::_deleteEventsOlderThan($settings->daysToKeep)){
                $settings->cleanupRan = 1;
                $settings->lastCleanup = time();
            }
        }
        // by number
        else
        {
            if(self::_deleteEventsGreaterThan($settings->eventsToKeep)){
                $settings->cleanupRan = 1;
                $settings->lastCleanup = time();
            }
        }
        WPPH::updatePluginSettings($settings);
        if($settings->cleanupRan == 1){
            //#! add event success
            wpphLog(__METHOD__.'() Cleanup complete.');
            self::_addLogEvent(6000, 0);
        }
    }
    //@internal
    // delete by days
    private static function _deleteEventsOlderThan($days = 1)
    {
        $query = "DELETE FROM ".WPPHDatabase::getFullTableName('main')." WHERE EventDate > (NOW() - INTERVAL ".$days." DAY)";
        global $wpdb;
        $result = $wpdb->query($query);
        if($result === false){ $status = 'Error executing query'; }
        else { $status = 'Query executed'; }
        wpphLog(__METHOD__.'('.$days.') called.', array('query'=>$query, 'status'=>$status, 'rowsDeleted'=> (int)$result));
        return ($result !== false);
    }
    //@internal
    // delete by number
    private static function _deleteEventsGreaterThan($number = 10000)
    {
        if($number > 10000){ $number = 10000; }
        global $wpdb;
        $tableName = WPPHDatabase::getFullTableName('main');
        $count = $wpdb->get_var("SELECT COUNT(0) FROM $tableName");
        if(empty($count)){
            wpphLog(__METHOD__.'('.$number.') called.  Ignored, there are no events in the database');
            return;
        }
        $keep = $number;
        if($count > $keep)
        {
            $limit = $count - $keep;
            $query = "DELETE FROM $tableName ORDER BY EventDate LIMIT $limit";
        }
        else {
            wpphLog(__METHOD__.'('.$number.') called.  Ignored, there are not enough events to trigger this action.');
            return;
        }

        $result = $wpdb->query($query);
        if($result === false){ $status = 'Error executing query'; }
        else { $status = 'Query executed'; }
        wpphLog(__METHOD__.'('.$number.') called.', array('query'=>$query, 'status'=>$status, 'rowsAffected'=> (int)$result));
        return ($result !== false);
    }

    /**
     * @internal
     * Fired on login failure
     */
    public static function watchLoginFailure($username)
    {
        wpphLog(__METHOD__.'() triggered by hook.', array('username'=>$username));
        self::_addLogEvent(1002,0,WPPHUtil::getIP(),serialize(array($username)));
    }

    /**
     * @internal
     * @param $userID the id of the user being updated
     * Triggered when a user's role is updated
     */
    public static function watchUserRoleUpdated($userID)
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        // get info for the currently logged in user
        $current_user = wp_get_current_user();
        $cid = $current_user->ID;
        $cName = $current_user->user_login;

        // get info for the currently updated user
        $editedUserInfo = WPPHDB::getUserInfo($userID);
        $editedUserName = $editedUserInfo['userName'];
        $initialUserRole = $editedUserInfo['userRole'];

        if(empty($_POST['role'])){
            wpphLog(__METHOD__.'() Ignored. Role did not change.');
            return;
        }

        $updatedRole = trim($_POST['role']);
        if(strcasecmp($initialUserRole, $updatedRole)==0){
            wpphLog(__METHOD__.'() Ignored. Role did not change.');
            return;
        }

        // The role of user <strong>%s</strong> was changed from <strong>%s</strong> to <strong>%s</strong> by <strong>%s</strong>
        $eData = serialize(array($editedUserName, ucfirst($initialUserRole), ucfirst($updatedRole), $cName));

        self::_addLogEvent(4002, $cid, WPPHUtil::getIP(), $eData);
    }

    /**
     * @internal
     * @param $userID the id of the user being updated
     * Triggered when a user's role is updated
     */
    public static function watchUserPasswordUpdated($userID)
    {
        if(empty($_POST['pass1'])){
            wpphLog(__METHOD__.'() triggered by hook. Ignored. Password did not change.');
            return;
        }
        wpphLog(__METHOD__.'() triggered by hook.');

        // get info for the currently logged in user
        $current_user = wp_get_current_user();
        $cid = $current_user->ID;
        $cName = $current_user->user_login;

        // check to see who's who here
        if($userID == $cid)
        {
            self::_addLogEvent(4003, $cid);
            return;
        }

        // get info for the currently updated user
        $editedUserInfo = WPPHDB::getUserInfo($userID);
        $editedUserName = $editedUserInfo['userName'];
        $editedUserRole = $editedUserInfo['userRole'];

        // <strong>%s</strong> changed the password for <strong>%s</strong> with the role of <strong>%s</strong>
        $eData = serialize(array($cName, $editedUserName, ucfirst($editedUserRole)));

        self::_addLogEvent(4004, $cid, WPPHUtil::getIP(), $eData);
    }

    /**
     * @internal
     * @param $userID the id of the user being updated
     * Triggered when a user's email is updated
     */
    public static function watchUserEmailUpdated($userID)
    {
        if(empty($_POST['email'])){
            wpphLog(__METHOD__.'() triggered by hook. Ignored. Email did not change.');
            return;
        }
        wpphLog(__METHOD__.'() triggered by hook.');

        global $wpdb;

        // get info for the currently logged in user
        $current_user = wp_get_current_user();
        $cid = $current_user->ID;
        $cName = $current_user->user_login;

        // get current user's email
        $oldEmail = $wpdb->get_var("SELECT user_email FROM ".$wpdb->users." WHERE ID = $userID");
        // new email
        $newEmail = mysql_real_escape_string($_POST['email']);

        // check to see who's who here
        if($userID == $cid)
        {

            self::_addLogEvent(4005, $cid, WPPHUtil::getIP(), serialize(array($oldEmail, $newEmail)));
            return;
        }

        // check if email updated
        if($_POST['email'] == $oldEmail){
            wpphLog(__METHOD__.'() Ignored. Email did not change.');
            return;
        }

        // get info for the currently updated user
        $editedUserInfo = WPPHDB::getUserInfo($userID);
        $editedUserName = $editedUserInfo['userName'];

        // %user_making_change% changed the email address of user account %user% from %old_email% to %new_email%
        $eData = serialize(array($cName, $editedUserName, $oldEmail, $newEmail));

        self::_addLogEvent(4006, $cid, WPPHUtil::getIP(), $eData);
    }

    /**
     * @internal
     * @param $userID the id of the user being deleted
     * Triggered when a user is deleted
     */
    public static function watchUserDeletion($userID)
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        global $current_user;
        get_currentuserinfo();

        $un = (empty($current_user->user_login) ? 'System' : $current_user->user_login);
        if($un == 'System'){
            $currentUserID = 0;
        }
        else { $currentUserID = $current_user->ID; }

        // get info for the currently deleted user
        $_userInfo = WPPHDB::getUserInfo($userID);
        $_userName = $_userInfo['userName'];
        $_userRole = ucfirst($_userInfo['userRole']);

        self::_addLogEvent(4007, $currentUserID, WPPHUtil::getIP(), serialize(array($_userName, $_userRole, $un)));
        // delete transient as well
        delete_transient(sha1($userID));
    }

    public static function watchPluginActivate()
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        // activate
        $a = (empty($_GET['plugin']) ? '' : $_GET['plugin']);

        // if active plugin edited (Using the plugin editor)
        if(empty($a)){
            $a = (empty($_GET['file']) ? '' : $_GET['file']);
        }

        $b = '';
        if(!empty($a)){ $b = dirname($a); }

        // get info for the currently logged in user
        $current_user = wp_get_current_user();

        self::_addLogEvent(5000,$current_user->ID, WPPHUtil::getIP(), serialize(array($b,$a)));
        wpphLog('Plugin activated.', array('plugin file'=>$a));
    }
    public static function watchPluginDeactivate()
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        // deactivate
        $a = (empty($_GET['plugin']) ? '' : $_GET['plugin']);
        $b = '';
        if(!empty($a)){ $b = dirname($a); }

        // get info for the currently logged in user
        $current_user = wp_get_current_user();

        self::_addLogEvent(5001,$current_user->ID, WPPHUtil::getIP(), serialize(array($b,$a)));
        wpphLog('Plugin deactivated.', array('plugin file'=>$a));

    }


    public static function WatchBlogActivity($newStatus, $oldStatus, $post)
    {
        $a = func_get_args();
        wpphLog(__METHOD__.'() triggered by hook.', $a);

        $postID = $post->ID;
        $hPid = md5($postID);
        $currentUserID  = wp_get_current_user()->ID;
        $userID = $postAuthorID = $post->post_author;
        if($currentUserID != $postAuthorID){
            // someone else is doing this
            $userID = $currentUserID;
        }

        // We're dealing with posts
        if($post->post_type == 'post')
        {
            // if transient exists then this is an update, otherwise this is a new post
            $value = get_transient($hPid);

            if($newStatus == 'inherit' && $oldStatus == 'new')
            {
                if(empty($value))
                {
                    //  Created a new blog post called %Post Title%. Blog post ID is %ID%
                    self::_addLogEvent(2000, $userID, WPPHUtil::getIP(), serialize(array($post->post_title,$postID)));
                    wpphLog('New blog post saved as draft.', array('title'=>$post->post_title));
                    set_transient($hPid, $postID, 0);
                    return;
                }
            }

            // #2000 , #2003
            if((($oldStatus == $newStatus) == 'draft') && $post->post_status == 'draft')
            {
                // so we skip generating multiple events
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }

                // #2000 : new blog post [as draft]
                if(empty($value))
                {
                    //  Created a new blog post called %Post Title%. Blog post ID is %ID%
                    self::_addLogEvent(2000, $userID, WPPHUtil::getIP(), serialize(array($post->post_title,$postID)));
                    wpphLog('New blog post saved as draft.', array('title'=>$post->post_title));
                    set_transient($hPid, $postID, 0);
                    return;

                }
                // #2003 : Updated draft post
                else
                {
                    // invalid
                    if($value != $postID) { return; }

                    // Modified the draft blog post %post_title%. Blog post ID is %ID%
                    self::_addLogEvent(2003, $userID, WPPHUtil::getIP(), serialize(array($post->post_title,$postID)));
                    wpphLog('Draft blog post updated.', array('title'=>$post->post_title));
                    return;

                }
            }
            // #2001 : Published a blog post
            elseif(($oldStatus == 'draft' && $newStatus == 'publish') && $post->post_status == 'publish')
            {
                // Published a blog post called %Post_Title%. Blog post URL is %Post_URL%
                self::_addLogEvent(2001, $userID, WPPHUtil::getIP(), serialize(array($post->post_title,$post->guid)));
                wpphLog('Blog post published.', array('title'=>$post->post_title));
                return;
            }
            // #2002 : Updated published post
            elseif(($oldStatus == 'publish' && $newStatus == 'publish') && $post->post_status == 'publish')
            {
                // Modified the published blog post %post_title%. Blog post URL is %post_URL%
                self::_addLogEvent(2002, $userID, WPPHUtil::getIP(), serialize(array($post->post_title,$post->guid)));
                wpphLog('Published blog post updated.', array('title'=>$post->post_title));
                return;
            }
        }

        // We're dealing with pages
        elseif($post->post_type == 'page')
        {
            // if transient exists then this is an update, otherwise this is a new page
            $value = get_transient($hPid);

            if($newStatus == 'inherit' && $oldStatus == 'new')
            {
                if(empty($value))
                {
                    // Created a new page called %page_title%. Page ID is %ID%
                    self::_addLogEvent(2004, $userID, WPPHUtil::getIP(), serialize(array($post->post_title,$postID)));
                    set_transient($hPid, $postID, 0);
                    wpphLog('New page saved as draft.', array('title'=>$post->post_title));
                    return;
                }
            }

            // #2004 , #2007
            if((($oldStatus == $newStatus) == 'draft') && $post->post_status == 'draft')
            {
                // so we skip generating multiple events
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }

                // #2004 : new page [as draft]
                if(empty($value))
                {
                    // Created a new page called %page_title%. Page ID is %ID%
                    self::_addLogEvent(2004, $userID, WPPHUtil::getIP(), serialize(array($post->post_title,$postID)));
                    set_transient($hPid, $postID, 0);
                    wpphLog('New page saved as draft.', array('title'=>$post->post_title));
                    return;

                }
                // #2007 : Updated draft page
                else
                {
                    // invalid
                    if($value != $postID) { return; }
                    // Modified the draft page %page_title%. Page ID is %ID%
                    self::_addLogEvent(2007, $userID, WPPHUtil::getIP(), serialize(array($post->post_title,$postID)));
                    wpphLog('Draft page updated.', array('title'=>$post->post_title));
                    return;

                }
            }
            // #2005 : Published a page
            elseif(($oldStatus == 'draft' && $newStatus == 'publish') && $post->post_status == 'publish')
            {
                // Published a page called %page_title%. Page URL is %URL%
                self::_addLogEvent(2005, $userID, WPPHUtil::getIP(), serialize(array($post->post_title,$post->guid)));
                wpphLog('Page published.', array('title'=>$post->post_title));
                return;
            }
            // #2006 : Updated published page
            elseif(($oldStatus == 'publish' && $newStatus == 'publish') && $post->post_status == 'publish')
            {
                // Modified the published page %page_title%. Page URL is %URL%
                self::_addLogEvent(2006, $userID, WPPHUtil::getIP(), serialize(array($post->post_title,$post->guid)));
                wpphLog('Published page updated.', array('title'=>$post->post_title));
                return;
            }
        }
    }

    public static function watchTrash($postID)
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        $hPid = md5($postID);

        // get info for the currently logged in user
        $current_user = wp_get_current_user();

        global $wpdb;

        $postInfo = $wpdb->get_row("SELECT post_title, post_type FROM ".$wpdb->posts." WHERE ID = ".$postID);
        $postTitle = $postInfo->post_title;
        $postType = $postInfo->post_type;

        if($postType == 'post')
        {
            // Deleted the blog post %Title%. Blog post ID is %ID%
            self::_addLogEvent(2008, $current_user->ID, WPPHUtil::getIP(), serialize(array($postTitle,$postID)));
            delete_transient($hPid);
            wpphLog('Blog post deleted.', array('title'=>$postTitle, 'id'=>$postID));
        }
        elseif($postType == 'page')
        {
            // Deleted the page %Title%. Page ID is %ID%
            self::_addLogEvent(2009, $current_user->ID, WPPHUtil::getIP(), serialize(array($postTitle,$postID)));
            delete_transient($hPid);
            wpphLog('Page deleted.', array('title'=>$postTitle, 'id'=>$postID));
        }
    }

    // 2010
    public static function watchFileUploaded($attachmentID)
    {
        global $wpdb;

        // get info for the currently logged in user
        $current_user = wp_get_current_user();

        $rowData = $wpdb->get_row("SELECT guid FROM ".$wpdb->posts." WHERE ID = ".$attachmentID);
        $fileName = basename($rowData->guid);
        $dirName = dirname($rowData->guid);

        // Uploaded the file %file name$ in %file location%
        self::_addLogEvent(2010, $current_user->ID, WPPHUtil::getIP(), serialize(array($fileName, $dirName)));
        wpphLog('File uploaded.', array('title'=>$fileName, 'url'=>$dirName));
    }
    // 2011
    public static function watchFileUploadedDeleted($attachmentID)
    {
        global $wpdb;

        // get info for the currently logged in user
        $current_user = wp_get_current_user();

        $rowData = $wpdb->get_row("SELECT post_title, guid FROM ".$wpdb->posts." WHERE ID = ".$attachmentID);

        // Deleted file %file name$ from %file_location%
        self::_addLogEvent(2011, $current_user->ID, WPPHUtil::getIP(), serialize(array($rowData->post_title,dirname($rowData->guid))));
        wpphLog('File deleted.', array('title'=>$rowData->post_title, 'url'=>dirname($rowData->guid)));
    }

    // 2012, 2013
    public static function watchFileDeletion($postID)
    {
        global $wpdb;

        $postInfo = $wpdb->get_row("SELECT post_title, post_type FROM ".$wpdb->posts." WHERE ID = ".$postID);
        $postTitle = $postInfo->post_title;
        $postType = $postInfo->post_type;

        // get info for the currently logged in user
        $userID = wp_get_current_user()->ID;

        if('post' == $postType)
        {
            self::_addLogEvent(2012, $userID, WPPHUtil::getIP(), serialize(array($postTitle)));
            wpphLog('Post trashed.', array('name'=>$postTitle));
        }
        elseif ('page' == $postType)
        {
            self::_addLogEvent(2013, $userID, WPPHUtil::getIP(), serialize(array($postTitle)));
            wpphLog('Page trashed.', array('name'=>$postTitle));
        }
    }

    // 2014, 2015
    public static function watchTrashUndo($postID)
    {
        global $wpdb;

        $postInfo = $wpdb->get_row("SELECT post_title, post_type FROM ".$wpdb->posts." WHERE ID = ".$postID);
        $postTitle = $postInfo->post_title;
        $postType = $postInfo->post_type;

        // get info for the currently logged in user
        $userID = wp_get_current_user()->ID;

        if('post' == $postType)
        {
            self::_addLogEvent(2014, $userID, WPPHUtil::getIP(), serialize(array($postTitle)));
            wpphLog('Post restored from trash.', array('name'=>$postTitle));
        }
        elseif ('page' == $postType)
        {
            self::_addLogEvent(2015, $userID, WPPHUtil::getIP(), serialize(array($postTitle)));
            wpphLog('Page restored from trash.', array('name'=>$postTitle));
        }
    }


    // 3000 - Theme activated
    public static function watchThemeChange($themeName)
    {
        // get info for the currently logged in user
        $current_user = wp_get_current_user();

        // // Activated the theme %themeName%
        self::_addLogEvent(3000, $current_user->ID, WPPHUtil::getIP(), serialize(array($themeName)));
        wpphLog('Theme activated.', array('name'=>$themeName));
    }
}
