<?php
/**
 * Base class
 */
class WPPH
{
    /**
     * @return bool
     * Convenient method to check whether or not the user has access rights
     */
    public static function canRun() {
        if(! WPPHUtil::isAdministrator()){ return false; }
        // todo: when we add option to select individual admins...
        //todo...
        return true;
    }
    /**
     * @return bool
     * Convenient method to check whether or not the plugin's resources can be loaded
     */
    public static function canLoad() { return ((false === ($pos = stripos($_SERVER['REQUEST_URI'], WPPH_PLUGIN_PREFIX))) ? false : true); }

    public static function loadBaseResources()
    {
        if(self::canLoad())
        {
            wp_enqueue_style('wpph_styles_base', WPPH_PLUGIN_URL . 'res/css/styles.base.css');
            wp_enqueue_script('wpph-ko-js', WPPH_PLUGIN_URL . 'res/js/knockout.js' );
            wp_enqueue_script('wpph-alvm-js', WPPH_PLUGIN_URL . 'res/js/audit-view-model.js' );
            wp_enqueue_script('wpph-jcookie-js', WPPH_PLUGIN_URL . 'res/js/jquery-ck.js' );
        }
    }

    public static function createPluginWpSidebar()
    {
        if (!current_user_can('administrator')){return false;}
        if (function_exists('add_menu_page'))
        {
            $baseMenuSlug = 'wpph_';
            $reqCap = 'activate_plugins';

            add_menu_page('WP Security Audit Log', 'WP Security Audit Log', $reqCap, $baseMenuSlug, 'WPPH::pageMain', WPPH_PLUGIN_URL.'res/img/logo-main-menu.png');
            add_submenu_page($baseMenuSlug, 'Audit Log Viewer', 'Audit Log Viewer', $reqCap, $baseMenuSlug, 'WPPH::pageMain');
            add_submenu_page($baseMenuSlug, __('Settings',WPPH_PLUGIN_TEXT_DOMAIN), __('Settings',WPPH_PLUGIN_TEXT_DOMAIN), $reqCap, $baseMenuSlug.'settings', 'WPPH::pageSettings');
            add_submenu_page($baseMenuSlug, __('Enable/Disable Alerts',WPPH_PLUGIN_TEXT_DOMAIN), __('Enable/Disable Alerts',WPPH_PLUGIN_TEXT_DOMAIN), $reqCap, $baseMenuSlug.'alerts', 'WPPH::pageAlerts');
            add_submenu_page($baseMenuSlug, __('About',WPPH_PLUGIN_TEXT_DOMAIN), __('About',WPPH_PLUGIN_TEXT_DOMAIN), $reqCap, $baseMenuSlug.'about', 'WPPH::pageAbout');
            add_submenu_page($baseMenuSlug, __('Support',WPPH_PLUGIN_TEXT_DOMAIN), __('Support',WPPH_PLUGIN_TEXT_DOMAIN), $reqCap, $baseMenuSlug.'support', 'WPPH::pageSupport');
        }
    }

    public static function pageMain() { include(WPPH_PLUGIN_DIR.'pages/dashboard.php'); }
    public static function pageSettings() { include(WPPH_PLUGIN_DIR.'pages/settings.php'); }
    public static function pageAlerts() {
        wp_enqueue_style('jquery-smoothness-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/smoothness/jquery-ui.css');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');
        include(WPPH_PLUGIN_DIR.'pages/alerts.php');
    }
    public static function pageAbout() { include(WPPH_PLUGIN_DIR.'pages/about.php'); }
    public static function pageSupport() { include(WPPH_PLUGIN_DIR.'pages/support.php'); }

    public static function createPluginDefaultSettings()
    {
        global $wpphEvents;
        $settings = new stdClass();
            $settings->daysToKeep = 0;
            $settings->eventsToKeep = WPPH_KEEP_MAX_EVENTS; // default delete option
            $settings->showEventsViewList = 50; // how many items to show in the event viewer by default
            $settings->lastCleanup = time();
            $settings->cleanupRan = 0;
            $settings->logEvents = $wpphEvents; // holds the list of events that will be triggered
            $settings->showDW = 1; // whether or not to show the dashboard widget. @since v0.4
        update_option(WPPH_PLUGIN_SETTING_NAME, $settings);
        wpphLog('Settings added.');
    }
    public static function getPluginSettings()
    {
        $settings = get_option(WPPH_PLUGIN_SETTING_NAME);
        if(false === $settings){
            self::createPluginDefaultSettings();
            $settings = get_option(WPPH_PLUGIN_SETTING_NAME);
        }
        return $settings;
    }

    /**
     * @param object $settings If this param is null, $settingName & $settingValue must be set
     * @param string $settingName Optional. Required if $settings is null
     * @param string $settingValue Optional. Required if $settings is null
     * @param bool $overrideCleanupRan Whether or not to override the cleanupRan option. Defaults to false
     */
    public static function updatePluginSettings($settings = null, $settingName = null, $settingValue=null, $overrideCleanupRan = false)
    {
        if(! is_null($settings)){
            if($overrideCleanupRan){
                $settings->lastCleanup = 0;
                $settings->cleanupRan = 0;
            }
            update_option(WPPH_PLUGIN_SETTING_NAME, $settings);
            return;
        }

        // name and value must be set!
        if(is_null($settingName) || is_null($settingValue)){
            return;
        }

        $settings = self::getPluginSettings();
        $settings->$settingName = $settingValue;
        if($overrideCleanupRan){
            $settings->lastCleanup = 0;
            $settings->cleanupRan = 0;
        }
        update_option(WPPH_PLUGIN_SETTING_NAME, $settings);
        wpphLog('Settings saved.', $settings);
    }

    public static function onPluginActivate()
    {
        wpphLog(__METHOD__.'() triggered.');

        $canContinue = true;

// Check: MySQL, PHP - without these there's not much left for this plugin to do
        if(! self::checkMySQL()){
            self::__addPluginError(__("Plugin could not be properly installed. The MySQL version installed on this server is less than 5.",WPPH_PLUGIN_TEXT_DOMAIN));
            $canContinue = false;
        }
        if(! self::checkPHP()){
            self::__addPluginError(__("Plugin could not be properly installed. The PHP version installed on this server is less than 5.",WPPH_PLUGIN_TEXT_DOMAIN));
            $canContinue = false;
        }
// no need for further checks, the plugin cannot run on this server...
        if(! $canContinue){
            $GLOBALS['WPPH_CAN_RUN'] = false;
            self::__addPluginError(__("Plugin could not be properly installed because the server does not meet our requirements: MySQL and PHP version >= 5.",WPPH_PLUGIN_TEXT_DOMAIN));
            return false;
        }

        // check to see whether or not an upgrade is necessary
        global $wpdb;
        $dbUpdated = get_option(WPPH_PLUGIN_DB_UPDATED);
        $tablesExist = false;
        $triggerInstallEvent = false; // whether or not the plugin is installed
        $pluginDbVersion = get_option(WPPH_PLUGIN_VERSION_OPTION_NAME);

        delete_option(WPPH_PLUGIN_ERROR_OPTION_NAME);

        // first install?
        if($pluginDbVersion === false){
            // Check tables
            if(WPPHDatabase::tableExists($wpdb, WPPHDatabase::getFullTableName('MAIN')) || WPPHDatabase::tableExists($wpdb, WPPHDatabase::getFullTableName('EVENTS'))){
                $tablesExist = true;
            }
            else { $triggerInstallEvent = true; }
        }

        // if we need to install
        if($triggerInstallEvent)
        {
            if($dbUpdated){ delete_option(WPPH_PLUGIN_DB_UPDATED); }
            if(WPPHDatabase::handleDatabase()){
                self::__handlePluginActivation(true);
                return true;
            }
            else {
                self::__addPluginError(__("Plugin could not be properly installed because we have encountered errors during the database update.",WPPH_PLUGIN_TEXT_DOMAIN));
                return false;
            }

        }
        // plugin already installed
        else
        {
            // if tables exist - do update database
            if($tablesExist)
            {
                // check plugin version
                if(empty($pluginDbVersion))
                {
                    if($dbUpdated){ delete_option(WPPH_PLUGIN_DB_UPDATED); }
                    // maybe version 0.1 ? empty tables
                    if(! WPPHDatabase::v2Cleanup()){
                        self::__addPluginError(__("Plugin could not be properly installed because we have encountered errors during the database update.",WPPH_PLUGIN_TEXT_DOMAIN));
                        return false;
                    }
                    // update database
                    if(WPPHDatabase::handleDatabase()){
                        self::__handlePluginActivation();
                        return true;
                    }
                    else {
                        self::__addPluginError(__("Plugin could not be properly installed because we have encountered errors during the database update.",WPPH_PLUGIN_TEXT_DOMAIN));
                        return false;
                    }
                }
                else {
                    $pluginDbVersion = (float)$pluginDbVersion;
                    $currentVersion = (float)WPPH_PLUGIN_VERSION;
                    // no need for upgrade
                    if(version_compare($pluginDbVersion, $currentVersion, '==')){
                        self::__handlePluginActivation();
                        return true;
                    }
                }
            }
            // tables not found
            else {
                if($dbUpdated){ delete_option(WPPH_PLUGIN_DB_UPDATED); }
                // create them
                if(WPPHDatabase::handleDatabase()){
                    self::__handlePluginActivation();
                    return true;
                }
                else {
                    self::__addPluginError(__("Plugin could not be properly installed because we have encountered errors during the database update.",WPPH_PLUGIN_TEXT_DOMAIN));
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * triggered when the plugin is deactivated
     */
    public static function onPluginDeactivate()
    {
        wp_clear_scheduled_hook(WPPH_PLUGIN_DEL_EVENTS_CRON_TASK_NAME);
        delete_option(WPPH_PLUGIN_ERROR_OPTION_NAME);
        delete_option(WPPH_PLUGIN_SETTING_NAME);
        wpphLog('__FUNCTION__.() triggered.');
    }

    public static function __addPluginError($error){
        $data = get_option(WPPH_PLUGIN_ERROR_OPTION_NAME);
        if(empty($data)){
            $data = array();
        }
        $data[] = base64_encode($error);
        update_option(WPPH_PLUGIN_ERROR_OPTION_NAME, $data);
        return true;
    }

    private static function __handlePluginActivation($triggerInstallEvent = false)
    {
        self::getPluginSettings();

        $GLOBALS['WPPH_CAN_RUN'] = true;
        update_option(WPPH_PLUGIN_DB_UPDATED,1);
        delete_option(WPPH_PLUGIN_ERROR_OPTION_NAME);
        update_option(WPPH_PLUGIN_VERSION_OPTION_NAME, WPPH_PLUGIN_VERSION);
        if($triggerInstallEvent)
        {
            define('WPPH_PLUGIN_INSTALLED_OK',true);
            $current_user = wp_get_current_user();
            // log plugin installation
            WPPHEvent::_addLogEvent(5000,$current_user->ID, WPPHUtil::getIP(), array(WPPH_PLUGIN_NAME));
            wpphLog('Plugin installed.', array('plugin'=>WPPH_PLUGIN_NAME));
        }
        // log plugin activation
        WPPHEvent::hookWatchPluginActivity();
    }
    /**
     * @internal
     * @static
     * @var array Holds the list or errors generated during install
     */
    private static $_errors = array();

    // must only be called in pages
    public static function ready()
    {
        if(empty(self::$_errors)){
            self::$_errors = self::getPluginErrors();
            if(empty(self::$_errors)){
                return true;
            }
        }
        return false;
    }

    public static function getPluginErrors() { return get_option(WPPH_PLUGIN_ERROR_OPTION_NAME); }

    public static function checkMySQL(){
        global $wpdb;
        $v = $wpdb->get_var("SELECT VERSION();");
        if(empty($v)){ return false; }
        $v = trim($v);
        if(intval($v[0]) < 5){ return false; }
        return true;
    }
    public static function checkPHP(){ return (version_compare(phpversion(), '5.0.0', '>=')); }
}


