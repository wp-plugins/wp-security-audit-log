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

    public static function e100(){
        $m = __('Plugin cannot create tables in the WordPress database to store security audit logs. Allow write access to the WordPress database user temporarily to activate this plugin. For more information contact us on support@wpprohelp.com.');
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }


    public static function e0()
    {
        $tableName = WPPHDatabase::getFullTableName('events');
        $query = '<pre><code>'.WPPHDatabase::getCreateQueryEventsDetailsTable().'</code></pre>';
        $m = sprintf(
            __('The table <strong>%s</strong> was not found nor it could be created.<br/>Please run this query manually then reload this page: %s')
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
        $query = '<pre><code>'.WPPHDatabase::getUpgradeQueryEventsDetailsTable().'</code></pre>';
        $m = sprintf(
            __('The table <strong>%s</strong> could not be updated.<br/>Please run this query manually then reload this page: %s')
            , $tableName, $query);
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }

    public static function e3()
    {
        $tableName = WPPHDatabase::getFullTableName('main');
        $query = '<pre><code>'.WPPHDatabase::getCreateQueryLogsTable().'</code></pre>';
        $m = sprintf(
            __('The table <strong>%s</strong> was not found nor it could be created.<br/>Please run this query manually then reload this page: %s')
            , $tableName, $query);
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }

    public static function e4()
    {
        $tableName = WPPHDatabase::getFullTableName('main');
        $query = '<pre><code>'.WPPHDatabase::getUpdateQueryLogsTable().'</code></pre>';
        $m = sprintf(
            __('The table <strong>%s</strong> could not be updated.<br/>Please run this query manually then reload this page: %s')
            , $tableName, $query);
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }

    public static function e5()
    {
        $tableName = WPPHDatabase::getFullTableName('main');
        $query = '<pre><code>'.WPPHDatabase::getUpgradeQueryLogsTable().'</code></pre>';
        $m = sprintf(
            __('The table <strong>%s</strong> could not be updated.<br/>Please run this query manually then reload this page: %s')
            , $tableName, $query);
        echo '<div class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error').':</strong> '.$m.'</p></div>';
    }
}
