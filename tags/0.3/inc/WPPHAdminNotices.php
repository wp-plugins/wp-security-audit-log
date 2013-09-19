<?php
/**
 * Class WPPHAdminNotices
 */
class WPPHAdminNotices
{
    public static function show($errorCode)
    {
        $f = "e".$errorCode;
        if(is_callable(array(__CLASS__,$f))){ add_action('admin_notices',array(__CLASS__,$f)); }
    }

    public static function e0()
    {
        $tableName = WPPHDatabase::getFullTableName('events');
        $query = '<pre><code>'.WPPHDatabase::getCreateQueryEventsDetailsTable().'</code></pre>';
        $m = sprintf(
            __('The table <strong>%s</strong> was not found nor it could be created.<br/>Please run this query manually and reactivate the plugin: %s')
            , $tableName, $query);
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }

    public static function e1()
    {
        $tableName = WPPHDatabase::getFullTableName('events');
        $queryUpdate = '<pre><code>';
        foreach(WPPHDatabase::getUpdateQueryEventsDetailsTable() as $query){
            $queryUpdate .= $query.'<br/>';
        }
        $queryUpdate .= '</code></pre>';
        $m = sprintf(__('We have encountered an error while trying to update the table: <strong>%s</strong>
        <br/>Please run the following queries manually then reload this page: %s')
            ,$tableName, $queryUpdate);
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }

    public static function e2()
    {
        $tableName = WPPHDatabase::getFullTableName('events');
        $queries = WPPHDatabase::getUpgradeQueryEventsDetailsTable();
        $out = '<pre>';
        foreach($queries as $query){
            $out .= "<code>{$query}</code><br/>";
        }
        $out .= '</pre>';
        $m = sprintf(
            __('The table <strong>%s</strong> could not be updated.<br/>Please run the following queries manually and reactivate the plugin: %s')
            , $tableName, $out);
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }

    public static function e3()
    {
        $tableName = WPPHDatabase::getFullTableName('main');
        $query = '<pre><code>'.WPPHDatabase::getCreateQueryLogsTable().'</code></pre>';
        $m = sprintf(
            __('The table <strong>%s</strong> was not found nor it could be created.<br/>Please run this query manually and reactivate the plugin: %s')
            , $tableName, $query);
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }

    public static function e4()
    {
        $tableName = WPPHDatabase::getFullTableName('main');
        $query = '<pre><code>'.WPPHDatabase::getUpdateQueryLogsTable().'</code></pre>';
        $m = sprintf(
            __('The table <strong>%s</strong> could not be updated.<br/>Please run this query manually and reactivate the plugin: %s')
            , $tableName, $query);
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }

    public static function e5()
    {
        $tableName = WPPHDatabase::getFullTableName('main');
        $queries = WPPHDatabase::getUpgradeQueryLogsTable();
        $out = '<pre>';
        foreach($queries as $query){
            $out .= "<code>{$query}</code><br/>";
        }
        $out .= '</pre>';
        $m = sprintf(
            __('The table <strong>%s</strong> could not be updated.<br/>Please run the following queries manually and reactivate the plugin: %s')
            , $tableName, $out);
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }

    // MySQL < 5
    public static function e6(){
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> Plugin could not be properly installed. MySQL version detected is lower than 5.</p></div>';
    }
    // PHP < 5
    public static function e7(){
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> Plugin could not be properly installed. PHP version detected is lower than 5.</p></div>';
    }


    public static function e100(){
        $m = __('Plugin cannot create tables in the WordPress database to store security audit logs. Allow write access to the WordPress database user temporarily to activate this plugin.
                For more information contact us on support@wpprohelp.com.');
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }
    public static function e200($missingRights=''){
        if(! empty($missingRights)){
            $missingRights = base64_decode($missingRights);
        }
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> Plugin could not be properly installed.
        The db user used to connect to the WordPress database is missing the following rights: '.$missingRights.'.</p></div>';
    }
    public static function e300(){
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> Plugin could not be properly installed.
        The PHP version installed on this server is less than 5.</p></div>';
    }
    public static function e400(){
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> Plugin could not be properly installed.
        The MySQL version installed on this server is less than 5.</p></div>';
    }
    public static function e500(){
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Fatal Error').':</strong> Plugin could not be properly installed because the server does not meet our requirements:
        MySQL and PHP version >= 5.</p></div>';
    }
    public static function e600(){
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Fatal Error').':</strong> Plugin could not be properly upgraded because we could not empty the content of the following tables: ';
            echo '<br/><strong>'.WPPHDatabase::getFullTableName('main').'</strong>';
            echo '<br/><strong>'.WPPHDatabase::getFullTableName('events').'</strong>';
        echo '</p></div>';
    }
}
