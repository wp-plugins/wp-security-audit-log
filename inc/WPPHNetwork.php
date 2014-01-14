<?php
/**
 * Class wpphNetwork
 */
class WPPHNetwork
{
// runs per site
    static function _activate($blogId=1)
    {
        wpphLog("Activating plugin for blog: $blogId");

        // Network ID
        update_option(WPPH_NETWORK_ID_OPTION_NAME, (defined('SITE_ID_CURRENT_SITE') ? SITE_ID_CURRENT_SITE : 1));
        // Main Site ID
        update_option(WPPH_MAIN_SITE_ID_OPTION_NAME, (defined('BLOG_ID_CURRENT_SITE') ? BLOG_ID_CURRENT_SITE : 1));

        if(WPPH::isMultisite())
        {
            // if main site
            if(WPPHUtil::isMainSite()){
                WPPH::createPluginDefaultSettings();
            }
            // nothing to do here
            else {
                update_option('WPPH_PLUGIN_ACTIVATED',1);
                wpphLog("Plugin activated successfully for blog: $blogId");
                return true;
            }
        }

        $ok = WPPH::onPluginActivate($blogId);
        if($ok)
        {
            update_option('WPPH_PLUGIN_ACTIVATED',1);
            wpphLog("Plugin activated successfully for blog: $blogId");
        }
        else { wpphLog("Plugin could not be activated for blog: $blogId"); }
    }
// runs per site
    static function _deactivate($blogId=1){
        wpphLog("Deactivating plugin for blog: $blogId");
        $ok = WPPH::onPluginDeactivate($blogId);
        if($ok){
            wpphLog("Plugin deactivated successfully for blog: $blogId");
        }
        else { wpphLog("Plugin could not be deactivated for blog: $blogId"); }
    }
// runs per site
    static function _uninstall($blogId=1){
        wpphLog("Uninstalling plugin from blog: $blogId");

        //check first for the wpph_plugin_activated for each site and if active first deactivate and then uninstall
        $active = (int)get_option('WPPH_PLUGIN_ACTIVATED');
        if(!empty($active)){
            self::_deactivate($blogId);
        }

        global $wpdb;

        // check if any tables created for a particular blog inside the network exist
        $prefix = $wpdb->prefix;

        if(empty($blogId) || $blogId == 1){}
        else { $prefix .= $blogId.'_'; }

        $wpdb->query("DROP TABLE IF EXISTS ".$prefix.'_wordpress_eventlog');
        $wpdb->query("DROP TABLE IF EXISTS ".$prefix.'_wordpress_eventlog_details');
        $wpdb->query("DROP TABLE IF EXISTS ".$prefix.'wordpress_auditlog');
        $wpdb->query("DROP TABLE IF EXISTS ".$prefix.'wordpress_auditlog_events');

        delete_option('WPPH_CRON_TASK_NAME');
        delete_option('WPPH_NETWORK_INSTALL');
        delete_option('WPPH_PLUGIN_ACTIVATED');
        delete_option(WPPH_MAIN_SITE_ID_OPTION_NAME);
        delete_option(WPPH_NETWORK_ID_OPTION_NAME);
        delete_option(WPPH_PLUGIN_ALLOW_ACCESS_OPTION_NAME);
        delete_option(WPPH_PLUGIN_ALLOW_CHANGE_OPTION_NAME);
        delete_option(WPPH_PLUGIN_DB_UPDATED);
        delete_option(WPPH_PLUGIN_SETTING_NAME);
        delete_option(WPPH_PLUGIN_EVENTS_LIST_OPTION_NAME);
        delete_option(WPPH_PLUGIN_VERSION_OPTION_NAME);
        delete_option(WPPH_USERS_CAN_REGISTER_OPT_NAME);
        delete_option(WPPH_PLUGIN_ERROR_OPTION_NAME);
        wpphLog("Plugin successfully uninstalled.");
    }

    static function networkActivate($networkwide=false)
    {
        global $wpdb;
        if (WPPH::isMultisite()) {
            // check if it is a network activation - if so, run the activation function for each blog id
            if ($networkwide)
            {
                $old_blog = $wpdb->blogid;
                $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
                foreach ($blogids as $blog_id) {
                    switch_to_blog($blog_id);
                    self::_activate($blog_id);
                }
                switch_to_blog($old_blog);
                update_option('WPPH_NETWORK_INSTALL',1);
                return;
            }
        }
        update_option('WPPH_NETWORK_INSTALL',0);
        self::_activate($wpdb->blogid);
    }

    static function networkDeactivate($networkwide=false){
        global $wpdb;
        if (WPPH::isMultisite()) {
            // check if it is a network activation - if so, run the activation function
            // for each blog id
            if ($networkwide) {
                $old_blog = $wpdb->blogid;
                // Get all blog ids
                $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
                foreach ($blogids as $blog_id) {
                    switch_to_blog($blog_id);
                    self::_deactivate($blog_id);
                }
                switch_to_blog($old_blog);
                return;
            }
        }
        self::_deactivate($wpdb->blogid);
    }

    static function networkUninstall(){
        global $wpdb;
        if (WPPH::isMultisite()) {
            $old_blog = $wpdb->blogid;
            // Get all blog ids
            $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
            foreach ($blogids as $blog_id) {
                switch_to_blog($blog_id);
                self::_uninstall($blog_id);
            }
            switch_to_blog($old_blog);
            return;
        }
        self::_uninstall();
    }

    /**
     * Retrieve the list of all blogs in the network
     * @return array( blogID => blogName )
     */
    static function getBlogsList()
    {
        global $wpdb;
        $out = array();
        $results = $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs WHERE public = 1 ORDER BY blog_id", ARRAY_A);
        if(empty($results)){
            return $out;
        }
        $basePrefix = WPPHDatabase::getDefaultPrefix();
        foreach($results as $entry){
            $blogID = $entry['blog_id'];
            if($blogID == 1){
                $blogName = $wpdb->get_var("SELECT option_value FROM ".$basePrefix."options WHERE option_name = 'blogname';");
            }
            else { $blogName = $wpdb->get_var("SELECT option_value FROM ".$basePrefix.$blogID."_options WHERE option_name = 'blogname';"); }
            $out[$blogID] = $blogName;
        }
        return $out;
    }

    static function getBlogName($blogID)
    {
        global $wpdb;
        $basePrefix = WPPHDatabase::getDefaultPrefix();
        if(empty($blogID) || (int)$blogID == 1){
            $table = $wpdb->prefix . 'options';
        }
        else { $table = $basePrefix.$blogID.'_options'; }
        return $wpdb->get_var("SELECT option_value FROM {$table} WHERE option_name = 'blogname';");
    }

    /**
     * Retrive the value of a global option
     * @param $optionName
     * @param bool $unserialize
     * @param bool $isConstant
     * @param null $default The default value to return if option not found
     * @return mixed string if $unserialize = false, otherwise array
     */
    static function getGlobalOption($optionName, $unserialize = false, $isConstant = false, $default = null)
    {
        global $wpdb;
        $table = WPPHDatabase::getDefaultPrefix();
        if($isConstant){
            $optionName = "'{$optionName}'";
        }
        $query = "SELECT option_value FROM {$table}options WHERE option_name = {$optionName}";
        wpphLog(__METHOD__.'() -> Query executed: '.$query);
        $result = $wpdb->get_var($query);
        if(is_null($result)){
            return $default;
        }
        if($unserialize)
        {
            if(! is_array($result)){
                $result = unserialize($result);
            }
        }
        return $result;
    }

    /**
     * Update a global option
     * @param string $optionName
     * @param mixed $optionValue
     * @param bool $serialize
     * @param bool $isConstant
     * @return bool
     */
    static function updateGlobalOption($optionName, $optionValue, $serialize = false, $isConstant = false)
    {
        global $wpdb;
        $table = WPPHDatabase::getDefaultPrefix().'options';
        if($isConstant){ $optionName = "'{$optionName}'"; }
        if($serialize){ $optionValue = serialize($optionValue); }
        $query = "UPDATE $table SET option_value = '$optionValue' WHERE option_name = $optionName";
        wpphLog(__METHOD__.'() -> Executing query: '.$query);
        $result = $wpdb->query($query);
        return (is_null($result) ? false : true);
    }

    static function addGlobalOption($optionName, $optionValue, $serialize = false, $isConstant = false)
    {
        $r = self::getGlobalOption($optionName, $serialize, $isConstant, null);
        if(! is_null($r)){
            return true;
        }
        global $wpdb;
        $table = WPPHDatabase::getDefaultPrefix().'options';
        if($isConstant || is_string($optionName)){ $optionName = "'{$optionName}'"; }
        if($serialize || is_array($optionValue) || is_object($optionValue)){ $optionValue = "'".serialize($optionValue)."'"; }
        else {
            if(is_string($optionValue)){ $optionValue = "'".$optionValue."'"; }
        }
        $query = "INSERT INTO $table (option_name, option_value) VALUES ($optionName, $optionValue)";
        wpphLog(__METHOD__.'() -> Executing query: '.$query);
        $result = $wpdb->query($query);
        return (is_null($result) ? false : true);
    }
}