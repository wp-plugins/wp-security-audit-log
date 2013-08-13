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
            add_submenu_page($baseMenuSlug, 'Settings', __('Settings'), $reqCap, $baseMenuSlug.'settings', 'WPPH::pageSettings');
            add_submenu_page($baseMenuSlug, 'About', __('About'), $reqCap, $baseMenuSlug.'about', 'WPPH::pageAbout');
            add_submenu_page($baseMenuSlug, 'Support', __('Support'), $reqCap, $baseMenuSlug.'support', 'WPPH::pageSupport');
        }
    }

    public static function pageMain() { include(WPPH_PLUGIN_DIR.'pages/dashboard.php'); }
    public static function pageSettings() { include(WPPH_PLUGIN_DIR.'pages/settings.php'); }
    public static function pageAbout() { include(WPPH_PLUGIN_DIR.'pages/about.php'); }
    public static function pageSupport() { include(WPPH_PLUGIN_DIR.'pages/support.php'); }

    public static function createPluginDefaultSettings()
    {
        $settings = new stdClass();
            $settings->daysToKeep = 0;
            $settings->eventsToKeep = 10000;
            $settings->showEventsViewList = 50; // how many items to show in the event viewer by default
            $settings->lastCleanup = time();
            $settings->cleanupRan = 0;
        add_option(WPPH_PLUGIN_SETTING_NAME, $settings);
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

    public static function optionExists($optionName) { return (false === get_option($optionName, false) ? false : true); }

    public static function onPluginActivate()
    {
        $optErrorData = array();
        $canContinue = true;
        // Check: MySQL, PHP - without these there's not much left for this plugin to do
        if(! self::__checkMySQL()){
            $optErrorData = self::__addError($optErrorData, 'e400');
            $canContinue = false;
        }
        if(! self::__checkPHP()){
            $optErrorData = self::__addError($optErrorData, 'e300');
            $canContinue = false;
        }
        // no need for further checks, the plugin cannot run on this server...
        if(! $canContinue){
            $optErrorData = self::__addError($optErrorData, 'e500');
            update_option(WPPH_PLUGIN_ERROR_OPTION_NAME, $optErrorData);
            return false;
        }

        // check to see whether or not an upgrade is necessary
        $v = get_option(WPPH_PLUGIN_VERSION_OPTION_NAME,false);
        if($v != false)
        {
            $v = (float)$v;
            $cv = (float)WPPH_PLUGIN_VERSION;
            //#! no need for upgrade
            if($v == $cv){
                update_option(WPPH_PLUGIN_VERSION_OPTION_NAME, WPPH_PLUGIN_VERSION);
                WPPHEvent::hookWatchPluginActivity(); //#! log self installation
                return true;
            }
        }

        if(! WPPHDatabase::v2Cleanup()){
            $optErrorData = self::__addError($optErrorData, 'e600');
            update_option(WPPH_PLUGIN_ERROR_OPTION_NAME, $optErrorData);
            return false;
        }

        //#! run the upgrade / update
        if(($result = WPPHDatabase::handleTables()) !== true)
        {
            $optErrorData = self::__addError($optErrorData, 'e'.$result);
        }

        if(empty($optErrorData)){
            update_option(WPPH_PLUGIN_VERSION_OPTION_NAME, WPPH_PLUGIN_VERSION);
            WPPHEvent::hookWatchPluginActivity(); //#! log self installation
            return true;
        }

        update_option(WPPH_PLUGIN_ERROR_OPTION_NAME, $optErrorData);
        return false;
    }

    /**
     * triggered when the plugin is deactivated
     */
    public static function onPluginDeactivate()
    {
        if(self::optionExists(WPPH_PLUGIN_ERROR_OPTION_NAME)){ delete_option(WPPH_PLUGIN_ERROR_OPTION_NAME); }
        if(self::optionExists(WPPH_PLUGIN_SETTING_NAME)){ delete_option(WPPH_PLUGIN_SETTING_NAME); }
        wp_clear_scheduled_hook(WPPH_PLUGIN_DEL_EVENTS_CRON_TASK_NAME);
        wpphLog('Plugin deactivated.');
    }


    public static function __addError(array $errorData, $errorCode, $arg=''){
        $errorData["$errorCode"] = base64_encode($arg);
        return $errorData;
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

    public static function isInstalled() { return self::optionExists(WPPH_PLUGIN_DB_UPDATED); }

    public static function getPluginErrors() { return get_option(WPPH_PLUGIN_ERROR_OPTION_NAME,null); }

    private static function __checkMySQL(){
        global $wpdb;
        $v = $wpdb->get_var("SELECT VERSION();");
        if(empty($v)){ return false; }
        $v = trim($v);
        if(intval($v[0]) < 5){ return false; }
        return true;
    }
    private static function __checkPHP(){ return (version_compare(phpversion(), '5.0.0', '>')); }
}


