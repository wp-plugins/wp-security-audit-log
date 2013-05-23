<?php
/**
 * @kyos
 * Base class
 */
class WPPH
{
    /**
     * @return bool
     * Convenient method to check whether or not the plugin can safely run
     */
    public static function canRun() { return WPPHDatabase::canRun(); }
    /**
     * @return bool
     * Convenient method to check whether or not the plugin's resources can be loaded
     */
    public static function canLoad() {
        if(false === ($pos = stripos($_SERVER['REQUEST_URI'], WPPH_PLUGIN_PREFIX))){ return false; }
        return true;
    }

    public static function loadBaseResources()
    {
        if(self::canLoad())
        {
            wp_enqueue_style('wpph_styles_base', WPPH_PLUGIN_URL . 'res/css/styles.base.css');
            wp_enqueue_script('wpph-ko-js', WPPH_PLUGIN_URL . 'res/js/knockout-2.2.1.min.js' );
            wp_enqueue_script('wpph-alvm-js', WPPH_PLUGIN_URL . 'res/js/AuditLogViewModel.js' );
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
}


