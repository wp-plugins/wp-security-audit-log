<?php
/**
 * WPPHEvent
 */
class WPPHEvent
{
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
            array( 'id' => 1002, 'category' => 'WARNING', 'text' => __('Failed login detected using <strong>%s</strong> as username.') ),

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
            // 2016 - Post category changed
            array( 'id' => 2016, 'category' => 'NOTICE', 'text' => __('Changed the category(ies) of the post <strong>%s</strong> from <strong>%s</strong> to <strong>%s</strong>.') ),
            // 2017 - Changed the URL of the post %post_name% from %old_url% to %new_url%
            array( 'id' => 2017, 'category' => 'NOTICE', 'text' => __('Changed the URL of the post <strong>%s</strong> from <strong>%s</strong> to <strong>%s</strong>.') ),
            // 2018 - Changed the URL of the page %page_name% from %old_url% to %new_url%
            array( 'id' => 2018, 'category' => 'NOTICE', 'text' => __('Changed the URL of the page <strong>%s</strong> from <strong>%s</strong> to <strong>%s</strong>.') ),
            // 2019 - Changed the author of %post_name% post from %old_author% to %new_author%
            array( 'id' => 2019, 'category' => 'NOTICE', 'text' => __('Changed the author of <strong>%s</strong> from <strong>%s</strong> to <strong>%s</strong>.') ),
            // 2020 - Changed the author of %page_name% page from %old_author% to %new_author%
            array( 'id' => 2020, 'category' => 'NOTICE', 'text' => __('Changed the author of <strong>%s</strong> page from <strong>%s</strong> to <strong>%s</strong>.') ),
            // 2021 - %postName% from %oldStatus% to %newStatus%
            array( 'id' => 2021, 'category' => 'NOTICE', 'text' => __('Changed the status of <strong>%s</strong> post from <strong>%s</strong> to <strong>%s</strong>.') ),
            // 2022 - page from published to draft
            array( 'id' => 2022, 'category' => 'NOTICE', 'text' => __('Changed the status of <strong>%s</strong> page from <strong>%s</strong> to <strong>%s</strong>.') ),
            // 2023 - added new category
            array( 'id' => 2023, 'category' => 'NOTICE', 'text' => __('Created a new category called <strong>%s</strong>.') ),
            // 2024 - deleted category
            array( 'id' => 2024, 'category' => 'WARNING', 'text' => __('Deleted the <strong>%s</strong> category.') ),

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
            // # 5000 Installed the plugin %name%.
            array( 'id' => 5000, 'category' => 'HIGH', 'text' => __('Installed the plugin <strong>%s</strong>.') ),
            // Activated the plugin %plugin_name% installed in %plugin_directory%
            array( 'id' => 5001, 'category' => 'HIGH', 'text' => __('Activated the plugin <strong>%s</strong> installed in /<strong>%s</strong>.') ),
            // Deactivated the plugin %plugin_name% installed in %plugin_directory%
            array( 'id' => 5002, 'category' => 'HIGH', 'text' => __('Deactivated the plugin <strong>%s</strong> installed in /<strong>%s</strong>.') ),
            // # 5003 Uninstalled the plugin %plugin_name% which was installed in %path%
            array( 'id' => 5003, 'category' => 'HIGH', 'text' => __('Uninstalled the plugin <strong>%s</strong> which was installed in /<strong>%s</strong>.') ),
            // # 5004 Upgraded the plugin %name% installed in %path%
            array( 'id' => 5004, 'category' => 'WARNING', 'text' => __('Upgraded the plugin <strong>%s</strong> installed in /<strong>%s</strong>.') ),

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

    // 2000, 2001, 2002, 2003, 2004, 2005, 2006, 2007, 2021, 2022
    public static function hookWatchBlogActivity() { add_action('transition_post_status', array('WPPHEventWatcher', 'watchBlogActivity'), 10, 3); }
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
    // 2016, 2017
    public static function hookWatchPostStateBefore()
    {
        if(isset($_POST['action']) && $_POST['action'] == 'autosave') { return; }

        if(isset($GLOBALS['WPPH_DEFAULT_EDITOR_ENABLED']) || isset($GLOBALS['WPPH_SCREEN_EDITOR_ENABLED']))
        {
            wpphLog(__METHOD__.'() triggered by hook.');

            $pid = $_POST['post_ID'];

            /*
             * CHECK IF POST/PAGE AUTHOR UPDATED; 2019
             * ## step 1: this is where we check if author has been changed
             * ## step 2: @see wpph_managePostAuthorUpdateQuickEditForm()
             */
            if(! empty($_POST['post_author']))
            {
                $GLOBALS['WPPH_POST_AUTHOR_UPDATED'] = intval($_POST['post_author']);
                if(isset($GLOBALS['WPPH_SCREEN_EDITOR_ENABLED'])){
                    // trigger hook manually
                    add_filter( 'wp_insert_post_data', 'wpph_managePostAuthorUpdateQuickEditForm', '1', 2 );
                }
            }

            // if blog post
            if($_POST['post_type'] == 'post')
            {
                // before further checks, we have to make sure this post isn't new
                global $wpdb;
                $postExists = (bool)$wpdb->get_var("SELECT ID FROM ".$wpdb->posts." WHERE ID = ".$pid);
                if(! $postExists){
                    return;
                }

                /*
                 * CHECK IF POST CATEGORY UPDATED; 2016
                 */
                $GLOBALS['WPPH_POST_OLD_CATEGORIES'] = wp_get_post_categories($pid);

                /*
                 * CHECK IF POST URL UPDATED; 2017
                 * ## step 1: this is where we retrieve the new URL
                 * ## step 2: @see WPPHEventWatcher::watchBlogActivity()
                 */
                $GLOBALS['WPPH_POST_NEW_URL'] = get_permalink($pid);
            }
            // if page
            elseif($_POST['post_type'] == 'page')
            {
                /*
                 * CHECK IF PAGE URL UPDATED; 2018
                 * ## step 1: this is where we retrieve the new URL
                 * ## step 2: @see WPPHEventWatcher::watchBlogActivity()
                 */
                $GLOBALS['WPPH_POST_NEW_URL'] = get_permalink($pid);
            }
        }
    }

    // 2023
    public static function hookWatchCategoryAdd() { WPPHEventWatcher::watchCategoryAdd($_POST); }
    // 2024
    public static function hookWatchCategoryDelete() { WPPHEventWatcher::watchCategoryDelete($_POST); }


// 3xxx - Themes management

    // 3000
    public static function hookThemeChange() { add_action('switch_theme', array('WPPHEventWatcher', 'watchThemeChange'));}


// 4xxx - User profile events

    // 4000, 4001
    public static function hookUserRegisterEvent() { add_action('user_register', array('WPPHEventWatcher', 'watchEventUserRegister')); }
    // 4002
    public static function hookUserRoleUpdated() {
        add_action('edit_user_profile_update', array('WPPHEventWatcher', 'watchUserInfoUpdated'));
        add_action('personal_options_update', array('WPPHEventWatcher', 'watchUserInfoUpdated'));
    }
    // 4003, 4004
    public static function hookUserPasswordUpdated() {
        add_action('edit_user_profile_update', array('WPPHEventWatcher', 'watchUserInfoUpdated'));
        add_action('personal_options_update', array('WPPHEventWatcher', 'watchUserInfoUpdated'));
    }
    // 4005, 4006
    public static function hookUserEmailUpdated() {
        add_action('edit_user_profile_update', array('WPPHEventWatcher', 'watchUserInfoUpdated'));
        add_action('personal_options_update', array('WPPHEventWatcher', 'watchUserInfoUpdated'));
    }
    // 4007
    public static function hookUserDeletion() { add_action( 'delete_user', array('WPPHEventWatcher', 'watchUserDeletion') ); }


// 5xxx - Plugin management

    // 5000, 5001, 5002, 5003, 5004
    public static function hookWatchPluginActivity() {
        @include_once(ABSPATH.'wp-admin/includes/plugin.php');
        WPPHEventWatcher::watchPluginInstall();  // 5000
        WPPHEventWatcher::watchPluginActivate(); // 5001
        WPPHEventWatcher::watchPluginDeactivate(); // 5002
        WPPHEventWatcher::watchPluginUninstall();  // 5003
        WPPHEventWatcher::watchPluginUpgrade(); // 5004
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
     * @param array $eventData Optional. If provided should be as an array.
     * @param string $failedLoginUserName The name of the user used for the failed login
     * @return bool
     */
    public static function _addLogEvent($eventID = 1000, $userID = 0, $userIP = '', $eventData = array(), $failedLoginUserName='')
    {
        if(empty($userIP)){ $userIP = WPPHUtil::getIP(); }
        $tableName = WPPHDB::getFullTableName('MAIN');
        $eventData = base64_encode(serialize($eventData));
        $query = sprintf("INSERT INTO $tableName (EventID, UserID, UserIP, EventData,UserName) VALUES(%d, %d, '%s', '%s', '%s')",$eventID, $userID, $userIP, $eventData, $failedLoginUserName);

        global $wpdb;
        if($eventID == 1002){ // 1002 == failed login

            // check if there is already an event there
            $eventNumber = $wpdb->get_var("SELECT EventNumber FROM $tableName WHERE EventID = $eventID AND UserIP = '$userIP' AND UserName ='$failedLoginUserName'");
            if(! empty($eventNumber))
            {
                // update
                $query = "UPDATE $tableName
                            SET
                                EventDate = CURRENT_TIMESTAMP(),
                                EventCount = (EventCount + 1)
                                WHERE EventNumber = ".$eventNumber;
            }
        }
        return ((false === $wpdb->query($query)) ? false : true);
    }


/*
 * PUBLIC METHODS
 * ============================================
 */

    public static function getEventDetailsData($eventID)
    {
        global $wpdb;
        $table = WPPHDatabase::getFullTableName('events');
        return $wpdb->get_row("SELECT EventType, EventDescription FROM $table WHERE EventID = $eventID");
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
        global $wpdb;
        $query = "SELECT le.EventNumber, le.EventID, le.EventDate, le.UserID, le.UserIP, le.EventData, le.EventCount, le.UserName,
                                          led.EventType, led.EventDescription
                                        FROM `$t1` as le
                                          INNER JOIN `$t2` as led
                                            ON le.EventID = led.EventID
                                              ORDER BY $orderBy
                                              $sort
                                              LIMIT $limit;";
        return $wpdb->get_results($query, ARRAY_A);
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
        $eventData = array($un, $nu, $nur);

        if($un == 'System')
        {
            // A new user with the username %username% has registered with the role of %user_role%
            $eventData = array($nu, $nur);
            self::_addLogEvent(4000, 0, WPPHUtil::getIP(), $eventData);
        }
        else {
            // %s created new user %s with role %s
            $eventData = array($un, $nu, $nur);
            self::_addLogEvent(4001, $current_user->ID, WPPHUtil::getIP(), $eventData);
        }
    }

    /**
     * #! 6000
     * @internal
     * Hooks to the events deletion event
     */
    public static function watchDeleteEvents()
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        if((defined('DISABLE_WP_CRON') && 'DISABLE_WP_CRON'))
        {
            wpphLog('wp-cron is disabled.');
            self::__deleteEvents();
        }
        else{
            add_action(WPPH_PLUGIN_DEL_EVENTS_CRON_TASK_NAME, array('WPPHEventWatcher','__deleteEvents'));
            if ( ! wp_next_scheduled(WPPH_PLUGIN_DEL_EVENTS_CRON_TASK_NAME) ) {
                wp_schedule_event( time(), 'daily', WPPH_PLUGIN_DEL_EVENTS_CRON_TASK_NAME );
                wpphLog(__METHOD__.'() scheduled by wp-cron.');
            }
        }
    }

    //@internal
    public static function __deleteEvents()
    {
        // check settings and delete the events (if any)
        $settings = WPPH::getPluginSettings();

        //if wp-cron disabled
        if((defined('DISABLE_WP_CRON') && 'DISABLE_WP_CRON'))
        {
            if($settings->cleanupRan == 1){
                wpphLog(__METHOD__.'() Ignored. Cleanup already ran today.');
                return;
            }
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
                self::_addLogEvent(6000, 0);
                wpphLog(__METHOD__.'() Cleanup complete.');
            }
        }
        // by number
        else
        {
            if(self::_deleteEventsGreaterThan($settings->eventsToKeep)){
                $settings->cleanupRan = 1;
                $settings->lastCleanup = time();
                self::_addLogEvent(6000, 0);
                wpphLog(__METHOD__.'() Cleanup complete.');
            }
        }
        WPPH::updatePluginSettings($settings);
    }

    //@internal
    // delete by days
    private static function _deleteEventsOlderThan($days = 1)
    {
        $query = "DELETE FROM ".WPPHDatabase::getFullTableName('main')." WHERE EventDate < (NOW() - INTERVAL ".$days." DAY)";
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
            $result = $wpdb->query($query);
            if($result === false){ $status = 'Error executing query'; }
            else { $status = 'Query executed'; }
            wpphLog(__METHOD__.'('.$number.') called.', array('query'=>$query, 'status'=>$status, 'rowsAffected'=> (int)$result));
            return ($result !== false);
        }
        else {
            wpphLog(__METHOD__.'('.$number.') called.  Ignored, there are not enough events to trigger this action.');
            return;
        }
    }

    /**
     * @internal
     * Fired on login failure
     */
    public static function watchLoginFailure($username='')
    {
        wpphLog(__METHOD__.'() triggered by hook.', array('username'=>$username));
        self::_addLogEvent(1002,0,WPPHUtil::getIP(),array($username), base64_encode($username));
    }

    public static function watchUserInfoUpdated($userID)
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

        // check and see *what* has been updated...

        // If a user's role has been updated
        if(!empty($_POST['role'])){
            $updatedRole = trim($_POST['role']);
            if(self::_userRoleUpdated($cid, $initialUserRole, $updatedRole, $editedUserName, $cName)){
                return;
            }
        }

        // If a user's password has been updated
        if(!empty($_POST['pass1'])){
            if(self::_userPasswordUpdated($userID, $cid, $cName, $editedUserName, $initialUserRole)){
                return;
            }
        }

        // if a user's email was updated
        if(! empty($_POST['email'])){
            global $wpdb;
            // get current user's email
            $oldEmail = $wpdb->get_var("SELECT user_email FROM ".$wpdb->users." WHERE ID = $userID");
            // new email
            $newEmailSafe = mysql_real_escape_string($_POST['email']);
            self::_userEmailUpdated($userID, $cid, $cName, $oldEmail, $newEmailSafe);
        }
    }

    /**
     * @internal
     * Triggered when a user's role is updated
     * @param $currentUserID
     * @param $initialUserRole
     * @param $updatedRole
     * @param $editedUserName
     * @param $currentUserName
     * @return bool
     */
    private static function _userRoleUpdated($currentUserID, $initialUserRole, $updatedRole, $editedUserName, $currentUserName)
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        //$updatedRole = trim($_POST['role']);
        if(strcasecmp($initialUserRole, $updatedRole)==0){
            wpphLog(__METHOD__.'() Ignored. Role did not change.');
            return false;
        }

        // The role of user <strong>%s</strong> was changed from <strong>%s</strong> to <strong>%s</strong> by <strong>%s</strong>
        $eData = array($editedUserName, ucfirst($initialUserRole), ucfirst($updatedRole), $currentUserName);

        self::_addLogEvent(4002, $currentUserID, WPPHUtil::getIP(), $eData);
        return true;
    }

    /**
     * @internal
     * Triggered when a user's role is updated
     * @param $userID
     * @param $currentUserID
     * @param $currentUserName
     * @param $editedUserName
     * @param $editedUserRole
     * @return bool
     */
    private static function _userPasswordUpdated($userID, $currentUserID, $currentUserName, $editedUserName, $editedUserRole)
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        // check to see who's who here
        if($userID == $currentUserID)
        {
            self::_addLogEvent(4003, $currentUserID);
            return true;
        }

        $eData = array($currentUserName, $editedUserName, ucfirst($editedUserRole));
        self::_addLogEvent(4004, $currentUserID, WPPHUtil::getIP(), $eData);
        return true;
    }

    /**
     * @internal
     * Triggered when a user's email is updated
     * @param $userID The user ID triggering this hook
     * @param $currentUserID The ID of the current logged in user
     * @param $currentUserName
     * @param $oldEmail
     * @param $newEmail
     * @return bool
     */
    private static function _userEmailUpdated($userID, $currentUserID, $currentUserName, $oldEmail, $newEmail)
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        // check if email updated
        if($newEmail == $oldEmail){
            wpphLog(__METHOD__.'() Ignored. Email did not change.');
            return false;
        }

        // check to see who's who here
        if($userID == $currentUserID)
        {
            self::_addLogEvent(4005, $currentUserID, WPPHUtil::getIP(), array($oldEmail, $newEmail));
            return true;
        }

        // get info for the currently updated user
        $editedUserInfo = WPPHDB::getUserInfo($userID);
        $editedUserName = $editedUserInfo['userName'];

        // %user_making_change% changed the email address of user account %user% from %old_email% to %new_email%
        $eData = array($currentUserName, $editedUserName, $oldEmail, $newEmail);

        self::_addLogEvent(4006, $currentUserID, WPPHUtil::getIP(), $eData);
        return true;
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

        self::_addLogEvent(4007, $currentUserID, WPPHUtil::getIP(), array($_userName, $_userRole, $un));
    }

    // # 5001
    public static function watchPluginActivate()
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        // get info for the currently logged in user
        $current_user = wp_get_current_user();

        // activate one by link
        if(! empty($_GET['action']) && ($_GET['action']=='activate') || !empty($_GET['action2']) && ($_GET['action2']=='activate'))
        {
            $pluginFile = $_GET['plugin'];
            $pluginData = get_plugin_data(WP_PLUGIN_DIR.'/'.$pluginFile,false,false);
            $pluginName = $pluginData['Name'];
            self::_addLogEvent(5001,$current_user->ID, WPPHUtil::getIP(), array($pluginName,$pluginFile));
            wpphLog('Plugin activated.', array('plugin file'=>$pluginFile));
        }
        // one by bulk
        elseif(isset($_POST['action']) && ($_POST['action'] == 'activate-selected') || isset($_POST['action2']) && ($_POST['action2'] == 'activate-selected'))
        {
            if(! empty($_POST['checked']))
            {
                foreach($_POST['checked'] as $k=>$pluginFile){
                    $pluginData = get_plugin_data(WP_PLUGIN_DIR.'/'.$pluginFile,false,false);
                    $pluginName = $pluginData['Name'];
                    self::_addLogEvent(5001,$current_user->ID, WPPHUtil::getIP(), array($pluginName,$pluginFile));
                    wpphLog('Plugin activated.', array('plugin file'=>$pluginFile));
                }
            }
        }
        // more by bulk
        elseif(isset($_POST['activate-multi']) && ($_POST['action']=='activate-selected' || $_POST['action2']=='activate-selected'))
        {
            if(! empty($_POST['checked']))
            {
                foreach($_POST['checked'] as $k=>$pluginFile){
                    $pluginData = get_plugin_data(WP_PLUGIN_DIR.'/'.$pluginFile,false,false);
                    $pluginName = $pluginData['Name'];
                    self::_addLogEvent(5001,$current_user->ID, WPPHUtil::getIP(), array($pluginName,$pluginFile));
                    wpphLog('Plugin activated.', array('plugin file'=>$pluginFile));
                }
            }
        }
    }
    // # 5002
    public static function watchPluginDeactivate()
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        // get info for the currently logged in user
        $current_user = wp_get_current_user();

        // activate one by link
        if(isset($_GET['action']) && ($_GET['action']=='deactivate') || isset($_GET['action2']) && ($_GET['action2']=='deactivate'))
        {
            $pluginFile = $_GET['plugin'];
            $pluginData = get_plugin_data(WP_PLUGIN_DIR.'/'.$pluginFile,false,false);
            $pluginName = $pluginData['Name'];
            self::_addLogEvent(5002,$current_user->ID, WPPHUtil::getIP(), array($pluginName,$pluginFile));
            wpphLog('Plugin deactivated.', array('plugin file'=>$pluginFile));
        }
        // one by bulk
        elseif(isset($_POST['action']) && ($_POST['action'] == 'deactivate-selected') || isset($_POST['action2']) && ($_POST['action2'] == 'deactivate-selected'))
        {
            if(! empty($_POST['checked']))
            {
                foreach($_POST['checked'] as $k=>$pluginFile){
                    $pluginData = get_plugin_data(WP_PLUGIN_DIR.'/'.$pluginFile,false,false);
                    $pluginName = $pluginData['Name'];
                    self::_addLogEvent(5002,$current_user->ID, WPPHUtil::getIP(), array($pluginName,$pluginFile));
                    wpphLog('Plugin deactivated.', array('plugin file'=>$pluginFile));
                }
            }
        }
        // more by bulk
        elseif(isset($_GET['activate-multi']) && ($_POST['action']=='deactivate-selected' || $_POST['action2']=='deactivate-selected'))
        {
            if(! empty($_POST['checked']))
            {
                foreach($_POST['checked'] as $k=>$pluginFile){
                    $pluginData = get_plugin_data(WP_PLUGIN_DIR.'/'.$pluginFile,false,false);
                    $pluginName = $pluginData['Name'];
                    self::_addLogEvent(5002,$current_user->ID, WPPHUtil::getIP(), array($pluginName,$pluginFile));
                    wpphLog('Plugin deactivated.', array('plugin file'=>$pluginFile));
                }
            }
        }
    }
    // # 5000
    public static function watchPluginInstall()
    {
        if(empty($_GET)) { return; }

        /**
         * @internal
         * @param $pluginName
         */
        function wpph_installPlugin($pluginName)
        {
            if(! empty($_GET['plugin']))
            {
                // get info for the currently logged in user
                $current_user = wp_get_current_user();
                WPPHEvent::_addLogEvent(5000,$current_user->ID, WPPHUtil::getIP(), array($pluginName));
                wpphLog('Plugin installed.', array('plugin'=>$pluginName));
            }
        }

        if(isset($_GET['action']) && $_GET['action']=='install-plugin'){
            wpphLog(__METHOD__.'() triggered by hook.');
            wpph_installPlugin($_GET['plugin']);
        }
        elseif(isset($_GET['action2']) && $_GET['action2']=='install-plugin'){
            wpphLog(__METHOD__.'() triggered by hook.');
            wpph_installPlugin($_GET['plugin']);
        }
    }
    // # 5003
    public static function watchPluginUninstall()
    {
        if(empty($_POST)) { return; }
        if(! isset($_POST['verify-delete'])) { return; }
        if(empty($_POST['checked'])){ return; }

        $action = '';
        if(! empty($_POST['action'])){ $action = $_POST['action'];}
        elseif(! empty($_POST['action2'])){ $action = $_POST['action2'];}
        if(empty($action) || $action != 'delete-selected') {
            return;
        }

        wpphLog(__METHOD__.'() triggered by hook.');
        // get info for the currently logged in user
        $current_user = wp_get_current_user();
        foreach($_POST['checked'] as $pluginFile){
            $pluginData = get_plugin_data(WP_PLUGIN_DIR.'/'.$pluginFile,false,false);
            $pluginName = $pluginData['Name'];
            self::_addLogEvent(5003,$current_user->ID, WPPHUtil::getIP(), array($pluginName,$pluginFile));
            wpphLog('Plugin uninstalled.', array('plugin file'=>$pluginFile));
        }
    }
    // # 5004
    public static function watchPluginUpgrade()
    {
        // single update/upgrade
        if(! empty($_GET['action']))
        {
            $action = '';
            if(! empty($_GET['action'])){ $action = $_GET['action'];}
            elseif(! empty($_GET['action2'])){ $action = $_GET['action2'];}
            if(empty($action) || $action != 'upgrade-plugin') {
                return;
            }
            wpphLog(__METHOD__.'() triggered by hook.');

            // get info for the currently logged in user
            $current_user = wp_get_current_user();
            $pluginFile = $_GET['plugin'];
            $pluginData = get_plugin_data(WP_PLUGIN_DIR.'/'.$pluginFile,false,false);
            $pluginName = $pluginData['Name'];
            // Upgraded the plugin <strong>%s</strong> installed in /<strong>%s</strong>
            self::_addLogEvent(5004,$current_user->ID, WPPHUtil::getIP(), array($pluginName,$pluginFile));
            wpphLog('Plugin upgraded.', array('plugin file'=>$pluginFile));
        }
        // multi-update/upgrade
        elseif(! empty($_POST))
        {
            $action = '';
            if(! empty($_POST['action'])){ $action = $_POST['action'];}
            elseif(! empty($_POST['action2'])){ $action = $_POST['action2'];}
            if(empty($action) || $action != 'update-selected') { return; }
            if(empty($_POST['checked'])){ return; }
            if(empty($_POST['_wp_http_referer'])){ return; }
            $referrer = $_POST['_wp_http_referer'];
            if(false === ($pos = stripos($referrer, 'plugins.php'))){
                return;
            }
            wpphLog(__METHOD__.'() triggered by hook.');

            // get info for the currently logged in user
            $current_user = wp_get_current_user();
            foreach($_POST['checked'] as $pluginFile){
                $pluginData = get_plugin_data(WP_PLUGIN_DIR.'/'.$pluginFile,false,false);
                $pluginName = $pluginData['Name'];
                self::_addLogEvent(5004,$current_user->ID, WPPHUtil::getIP(), array($pluginName,$pluginFile));
                wpphLog('Plugin upgraded.', array('plugin file'=>$pluginFile));
            }
        }
    }


    public static function watchBlogActivity($newStatus, $oldStatus, $post)
    {
        wpphLog(__FUNCTION__.'. POST DATA', array(
            '$newStatus' => $newStatus,
            '$oldStatus' => $oldStatus,
            '$post' => $post
        ));

        // IGNORE STATES
        if($newStatus == 'auto-draft' || $post->post_status == 'auto-draft')
        {
            return;
        }

        // so we skip generating multiple events
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) { return; }

        $postID = $post->ID;
        $postTitle = $post->post_title;
        $postUrl = get_permalink($postID);
        $postStatus = $post->post_status;
        $currentUserID  = wp_get_current_user()->ID;
        $userID = $postAuthorID = $post->post_author;
        if($currentUserID != $postAuthorID){
            // someone else is doing this
            $userID = $currentUserID;
        }

        if($post->post_type == 'revision'){
            return;
        }

        wpphLog(__FUNCTION__.' triggered by hook.');


        // CHECK TO SEE IF THIS UPDATE IS FROM THE QUICK EDIT FORM or the default wp post editor
        if(isset($_POST['original_post_status']))
        {
            $originalPostStatus = $_POST['original_post_status'];
        }
        else{
            // quick edit form
            $originalPostStatus = $_POST['_status'];
        }


        // We're dealing with posts
        if($post->post_type == 'post')
        {
            /**
             * @@ 2016
             * Check for category change
             */
            if(isset($GLOBALS['WPPH_POST_OLD_CATEGORIES']))
            {
                $originalCats = $GLOBALS['WPPH_POST_OLD_CATEGORIES'];
                $categories_1 = array();
                foreach($originalCats as $catID){
                    $cat = get_category($catID);
                    array_push($categories_1, $cat->name);
                }
                $categories_2 = array();
                $newCats = $post->post_category;
                if(empty($newCats[0])){
                    unset($newCats[0]);
                }
                foreach($newCats as $catID){
                    $cat = get_category($catID);
                    array_push($categories_2, $cat->name);
                }

                sort($categories_1);
                sort($categories_2);

                // categories updated
                if($categories_1 <> $categories_2)
                {
                    $c1 = implode(', ', $categories_1);
                    $c2 = implode(', ', $categories_2);
                    wpph_postCategoriesUpdated(wp_get_current_user()->ID, $postTitle, $c1, $c2);
                    $GLOBALS['WPPH_EVENT_2016'] = true;
                }
            }

            // 2019
            // check to see whether or not the post author was changed
            if(isset($GLOBALS['WPPH_POST_AUTHOR_UPDATED'])){
                $newAuthorID = (int)$GLOBALS['WPPH_POST_AUTHOR_UPDATED'];
                if(wpph_postAuthorUpdated($newAuthorID, $postID, $userID, $postTitle)){
                    unset($GLOBALS['WPPH_POST_AUTHOR_UPDATED']);
                    $GLOBALS['WPPH_EVENT_2019'] = true;
                }
            }

            // # 2000 - NEW POST AS DRAFT
            // # 2003 - DRAFT UPDATED
            if(($oldStatus == 'draft' || $oldStatus == 'auto-draft') && $newStatus == 'draft' && $postStatus == 'draft')
            {
                // # 2000 - NEW POST AS DRAFT
                if($originalPostStatus == 'auto-draft')
                {
                    //  New blog post saved as draft
                    wpph_newPostAsDraft($userID, $postID, $postTitle);
                }
                // # 2003 - DRAFT UPDATED
                elseif($originalPostStatus == 'draft')
                {
                    // only if 2016 || 2017 || 2019 were not triggered
                    if(isset($GLOBALS['WPPH_EVENT_2016']) || isset($GLOBALS['WPPH_EVENT_2017']) || isset($GLOBALS['WPPH_EVENT_2019'])){
                        return;
                    }
                    // Modified the draft blog post %post_title%. Blog post ID is %ID%
                    wpph_draftPostUpdated($userID, $postID, $postTitle);
                }
            }
            // # 2001 - DRAFT TO PUBLISHED
            elseif($oldStatus == 'draft' && $newStatus == 'publish' && $postStatus == 'publish'){
                // Published a blog post called %Post_Title%. Blog post URL is %Post_URL%
                wpph_newPostPublished($userID, $postTitle, $postUrl);
            }
            // # 2001 - NEW POST PUBLISHED
            elseif($oldStatus == 'auto-draft' && $newStatus == 'publish' && $postStatus == 'publish'){
                // Published a blog post called %Post_Title%. Blog post URL is %Post_URL%
                wpph_newPostPublished($userID, $postTitle, $postUrl);
            }
            // # 2001 - PENDING TO PUBLISHED
            elseif($oldStatus == 'pending' && $newStatus == 'publish' && $postStatus == 'publish'){
                // Published a blog post called %Post_Title%. Blog post URL is %Post_URL%
                wpph_newPostPublished($userID, $postTitle, $postUrl);
            }
            // # 2002 - PUBLISHED POST UPDATED
            elseif($oldStatus == 'publish' && $newStatus == 'publish' && $postStatus == 'publish')
            {
                // CHECK IF POST URL MODIFIED
                // ## step 1: see self::hookWatchPostStateBefore()
                // ## step 2: trigger event
                // trigger: 2017 - Changed the URL of the post %post_name% from %old_url% to %new_url%
                if(isset($GLOBALS['WPPH_POST_NEW_URL']))
                {
                    if(wpph_postUrlUpdated($GLOBALS['WPPH_POST_NEW_URL'], get_permalink($postID), $userID, $postTitle)){
                        unset($GLOBALS['WPPH_POST_NEW_URL']);
                        $GLOBALS['WPPH_EVENT_2017'] = true;
                    }
                }
                // only if 2016 || 2017 || 2019 were not triggered
                if(isset($GLOBALS['WPPH_EVENT_2016']) || isset($GLOBALS['WPPH_EVENT_2017']) || isset($GLOBALS['WPPH_EVENT_2019'])){
                    return;
                }
                // Modified the published blog post %post_title%. Blog post URL is %post_URL%
                wpph_publishedPostUpdated($userID, $postTitle, $postUrl);
            }
            // # 2021 - PUBLISHED TO PENDING
            elseif($oldStatus == 'publish' && $newStatus == 'pending' && $postStatus == 'pending'){
                wpph_postStatusUpdated('Published', 'Pending Review', $userID, $postTitle);
            }
            // # 2021 - PENDING TO DRAFT
            elseif($oldStatus == 'pending' && $newStatus == 'draft' && $postStatus == 'draft'){
                wpph_postStatusUpdated('Pending Review', 'Draft', $userID, $postTitle);
            }
            // # 2021 - DRAFT TO PENDING
            elseif($oldStatus == 'draft' && $newStatus == 'pending' && $postStatus == 'pending'){
                wpph_postStatusUpdated('Draft', 'Pending Review', $userID, $postTitle);
            }
            // # 2021 - PUBLISHED TO DRAFT
            elseif($oldStatus == 'publish' && $newStatus == 'draft' && $postStatus == 'draft'){
                wpph_postStatusUpdated('Published', 'Draft', $userID, $postTitle);
            }
        }
        // We're dealing with pages
        elseif($post->post_type == 'page')
        {
            // 2020
            // check to see whether or not the page author was changed
            if(isset($GLOBALS['WPPH_POST_AUTHOR_UPDATED'])){
                $newAuthorID = (int)$GLOBALS['WPPH_POST_AUTHOR_UPDATED'];
                if(wpph_pageAuthorUpdated($newAuthorID, $postID, $userID, $postTitle)){
                    unset($GLOBALS['WPPH_POST_AUTHOR_UPDATED']);
                    $GLOBALS['WPPH_EVENT_2020'] = true;
                }
            }

            // # 2004 - NEW PAGE AS DRAFT
            // # 2007 - DRAFT UPDATED
            if(($oldStatus == 'draft' || $oldStatus == 'auto-draft') && $newStatus == 'draft' && $postStatus == 'draft')
            {
                // # 2004 - NEW PAGE AS DRAFT
                if($originalPostStatus == 'auto-draft'){
                    wpph_newPageAsDraft($userID, $postID, $postTitle);
                }
                // # 2007 - DRAFT UPDATED
                elseif($originalPostStatus == 'draft'){
                    // only if 2018 || 2020 were not triggered
                    if(isset($GLOBALS['WPPH_EVENT_2018']) || isset($GLOBALS['WPPH_EVENT_2020'])){
                        return;
                    }
                    wpph_draftPageUpdated($userID, $postID, $postTitle);
                }
            }
            // # 2005 - DRAFT TO PUBLISHED
            elseif($oldStatus == 'draft' && $newStatus == 'publish' && $postStatus == 'publish'){
                wpph_newPagePublished($userID, $postTitle, $postUrl);
            }
            // # 2005 - NEW PAGE PUBLISHED
            elseif(($oldStatus == 'draft' || $oldStatus == 'auto-draft') && $newStatus == 'publish' && $postStatus == 'publish'){
                // Published a page called %page_title%. Page URL is %URL%
                wpph_newPagePublished($userID, $postTitle, $postUrl);
            }
            // # 2005 - PENDING TO PUBLISHED
            elseif($oldStatus == 'pending' && $newStatus == 'publish' && $postStatus == 'publish'){
                // Published a page called %page_title%. Page URL is %URL%
                wpph_newPagePublished($userID, $postTitle, $postUrl);
            }
            // # 2006 - PUBLISHED PAGE UPDATED | 2018, 2020
            elseif($oldStatus == 'publish' && $newStatus == 'publish' && $postStatus == 'publish')
            {
                // CHECK IF PAGE URL MODIFIED
                // ## step 1: see self::hookWatchPostStateBefore()
                // ## step 2: trigger event
                // trigger: 2018 - Changed the URL of the page %post_name% from %old_url% to %new_url%
                if(isset($GLOBALS['WPPH_POST_NEW_URL']))
                {
                    if(wpph_pageUrlUpdated($GLOBALS['WPPH_POST_NEW_URL'], get_permalink($postID), $userID, $postTitle)){
                        unset($GLOBALS['WPPH_POST_NEW_URL']);
                        $GLOBALS['WPPH_EVENT_2018'] = true;
                    }
                }
                // only if 2018 || 2020 were not triggered
                if(isset($GLOBALS['WPPH_EVENT_2018']) || isset($GLOBALS['WPPH_EVENT_2020'])){
                    return;
                }
                // Modified the published page %page_title%. Page URL is %URL%
                wpph_publishedPageUpdated($userID, $postTitle, $postUrl);
            }
            // # 2022 - PUBLISHED TO PENDING
            elseif($oldStatus == 'publish' && $newStatus == 'pending' && $postStatus == 'pending'){
                wpph_pageStatusUpdated('Published', 'Pending Review', $userID, $postTitle);
            }
            // # 2022 - PENDING TO DRAFT
            elseif($oldStatus == 'pending' && $newStatus == 'draft' && $postStatus == 'draft'){
                wpph_pageStatusUpdated('Pending Review', 'Draft', $userID, $postTitle);
            }
            // # 2022 - DRAFT TO PENDING
            elseif($oldStatus == 'draft' && $newStatus == 'pending' && $postStatus == 'pending'){
                wpph_pageStatusUpdated('Draft', 'Pending Review', $userID, $postTitle);
            }
            // # 2022 - PUBLISHED TO DRAFT
            elseif($oldStatus == 'publish' && $newStatus == 'draft' && $postStatus == 'draft'){
                wpph_pageStatusUpdated('Published', 'Draft', $userID, $postTitle);
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
            self::_addLogEvent(2008, $current_user->ID, WPPHUtil::getIP(), array($postTitle,$postID));
            wpphLog('Blog post deleted.', array('title'=>$postTitle, 'id'=>$postID));
        }
        elseif($postType == 'page')
        {
            // Deleted the page %Title%. Page ID is %ID%
            self::_addLogEvent(2009, $current_user->ID, WPPHUtil::getIP(), array($postTitle,$postID));
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
        self::_addLogEvent(2010, $current_user->ID, WPPHUtil::getIP(), array($fileName, $dirName));
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
        self::_addLogEvent(2011, $current_user->ID, WPPHUtil::getIP(), array($rowData->post_title,dirname($rowData->guid)));
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
            self::_addLogEvent(2012, $userID, WPPHUtil::getIP(), array($postTitle));
            wpphLog('Post trashed.', array('name'=>$postTitle));
        }
        elseif ('page' == $postType)
        {
            self::_addLogEvent(2013, $userID, WPPHUtil::getIP(), array($postTitle));
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
            self::_addLogEvent(2014, $userID, WPPHUtil::getIP(), array($postTitle));
            wpphLog('Post restored from trash.', array('name'=>$postTitle));
        }
        elseif ('page' == $postType)
        {
            self::_addLogEvent(2015, $userID, WPPHUtil::getIP(), array($postTitle));
            wpphLog('Page restored from trash.', array('name'=>$postTitle));
        }
    }

    // 3000 - Theme activated
    public static function watchThemeChange($themeName)
    {
        // get info for the currently logged in user
        $current_user = wp_get_current_user();
        // // Activated the theme %themeName%
        self::_addLogEvent(3000, $current_user->ID, WPPHUtil::getIP(), array($themeName));
        wpphLog('Theme activated.', array('name'=>$themeName));
    }

    // 2023 - category created
    public static function watchCategoryAdd(array $postData)
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        if(!empty($_POST['screen']) && !empty($_POST['tag-name']) &&
            $_POST['screen'] == 'edit-category' &&
            $_POST['taxonomy'] == 'category' &&
            $_POST['action'] == 'add-tag')
        {
            $categoryName = sanitize_text_field($_POST['tag-name']);

            // get info for the currently logged in user
            $current_user = wp_get_current_user();
            // Created a new category called %categoryName%
            self::_addLogEvent(2023, $current_user->ID, WPPHUtil::getIP(), array($categoryName));
            wpphLog('Category added.', array('name'=>$categoryName));
        }
        // adding the new category when writing a blog post
        elseif(! empty($_POST['newcategory']) && $_POST['action'] == 'add-category')
        {
            $categoryName = sanitize_text_field($_POST['newcategory']);

            // get info for the currently logged in user
            $current_user = wp_get_current_user();
            // Created a new category called %categoryName%
            self::_addLogEvent(2023, $current_user->ID, WPPHUtil::getIP(), array($categoryName));
            wpphLog('Category added.', array('name'=>$categoryName));
        }
    }

    // 2024 - category deleted
    public static function watchCategoryDelete(array $postData)
    {
        wpphLog(__METHOD__.'() triggered by hook.');

        if(empty($postData)){ return; }

        // get info for the currently logged in user
        $current_user = wp_get_current_user();
        $userID = $current_user->ID;
        $userIP = WPPHUtil::getIP();

        //@internal
        function __alertDeletedCategory($userID, $userIP, $categoryID){
            $category = get_category($categoryID);
            $categoryName = $category->cat_name;
            WPPHEvent::_addLogEvent(2024, $userID, $userIP, array($categoryName));
            wpphLog('Category deleted.', array('name'=>$categoryName));
        }

        $action = '';
        if(! empty($postData['action'])){ $action = $postData['action'];}
        elseif(! empty($postData['action2'])){ $action = $postData['action2'];}
        if(empty($action)) {
            return;
        }

        // delete one
        if($action == 'delete-tag' && $postData['taxonomy'] == 'category' && !empty($postData['tag_ID'])){
            __alertDeletedCategory($userID, $userIP, $postData['tag_ID']);
        }

        // bulk delete
        elseif($action == 'delete' && $postData['taxonomy'] == 'category' && !empty($postData['delete_tags'])){
            foreach($postData['delete_tags'] as $categoryID){
                __alertDeletedCategory($userID, $userIP, $categoryID);
            }
        }
    }

}
