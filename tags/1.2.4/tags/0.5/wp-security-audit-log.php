<?php
/*
Plugin Name: WP Security Audit Log
Plugin URI: http://www.wpwhitesecurity.com/wordpress-security-plugins/wp-security-audit-log/
Description: Identify WordPress security issues before they become a problem and keep track of everything happening on your WordPress, including WordPress users activity. Similar to Windows Event Log and Linux Syslog, WP Security Audit Log will generate a security alert for everything that happens on your WordPress blog or website. Use the Audit Log Viewer included in the plugin to see all the security alerts.
Author: WP White Security
Version: 0.5
Author URI: http://www.wpwhitesecurity.com/
License: GPL2
Text Domain: wp-security-audit-log
Domain Path: languages/

    WP Security Audit Log
    Copyright(c) 2013  Robert Abela  (email : robert@wpwhitesecurity.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define('WPPH_PLUGIN_VERSION','0.5');
define('WPPH_PLUGIN_PREFIX', 'wpph_');
define('WPPH_PLUGIN_NAME', 'WP Security Audit Log');
define('WPPH_PLUGIN_URL', trailingslashit(plugins_url('', __FILE__)));
define('WPPH_PLUGIN_DIR', trailingslashit(plugin_dir_path(__FILE__)));
if(defined('__DIR__')) { define('WPPH_PLUGIN_BASE_NAME', basename(__DIR__)); }
else { define('WPPH_PLUGIN_BASE_NAME', basename(dirname(__FILE__))); }

// Load required files
require('inc/wpphSettings.php');
require('inc/WPPHLogger.php');
require('inc/WPPHUtil.php');
require('inc/WPPHDatabase.php');
require('inc/WPPHEvent.php');
require('inc/WPPHPost.php');
require('inc/WPPH.php');
require('inc/wpphFunctions.php');

// 2000
$GLOBALS['WPPH_POST_IS_NEW'] = false;
add_action('wp_insert_post', 'wpphPostDetectNew', 1, 2);
function wpphPostDetectNew($post, $wp_error = false){
    wpphLog(__FUNCTION__.' triggered by hook: WP_INSERT_POST');
    if(isset($_POST['post_id'])){
        $GLOBALS['WPPH_POST_IS_NEW'] = true;
        wpphLog('POST IS NEW');
    }
}

/**
 * triggered when the plugin is uninstalled (with option files delete: true)
 */
function onPluginUninstall()
{
    global $wpdb;
    delete_option(WPPH_PLUGIN_DB_UPDATED);
    delete_option(WPPH_PLUGIN_VERSION_OPTION_NAME);
    delete_option(WPPH_USERS_CAN_REGISTER_OPT_NAME);
    delete_option(WPPH_PLUGIN_ALLOW_ACCESS_OPTION_NAME);
    delete_option(WPPH_PLUGIN_ALLOW_CHANGE_OPTION_NAME);
    delete_option(WPPH_PLUGIN_SETTING_NAME);
    $wpdb->query("DROP TABLE IF EXISTS ".WPPHDatabase::getFullTableName('main'));
    $wpdb->query("DROP TABLE IF EXISTS ".WPPHDatabase::getFullTableName('events'));
}
// register callbacks
register_activation_hook( __FILE__, array('WPPH', 'onPluginActivate') );
register_deactivation_hook( __FILE__, array('WPPH', 'onPluginDeactivate') );
register_uninstall_hook( __FILE__, 'onPluginUninstall' );

// Add custom links on plugins page
add_filter("plugin_action_links_".plugin_basename(__FILE__), 'wpphCustomLinks' );
// Load text domain
add_action('plugins_loaded', 'wpphLoadTextDomain');
// create dashboard widget
add_action('wp_dashboard_setup', array('WPPHUtil','addDashboardWidget'));

// $GLOBALS['WPPH_CAN_RUN']
// @since v0.3
// @see WPPH::onPluginActivate()
if($GLOBALS['WPPH_CAN_RUN'])
{
    //Create default settings
    WPPH::getPluginSettings();
// Watch widget activity
    add_action('widgets_init',array('WPPHEventWatcher','watchWidgetMove'));
    add_action('sidebar_admin_setup', array('WPPHEventWatcher','watchWidgetActivity'));
// Load the pluggable.php file if needed
    add_action('admin_init', array('WPPHUtil','loadPluggable'));
// Load resources
    add_action('admin_init', array('WPPH', 'loadBaseResources'));
// Add the sidebar menu
    add_action('admin_menu', array('WPPH', 'createPluginWpSidebar'));
// Plugin init
    add_action('init', 'wpphPluginInit');
    function wpphPluginInit()
    {
        if(is_admin())
        {
            if(isset($_POST)){
                //# 6001, 6002, 6003
                WPPHEvent::hookCheckWpGeneralSettings();
                if(isset($_POST)){
                    if(isset($_POST['action']) && $_POST['action'] == 'editpost'){
                        wpphLog('WPPH_DEFAULT_EDITOR_ENABLED');
                        $GLOBALS['WPPH_DEFAULT_EDITOR_ENABLED'] = true;
                    }
                    elseif(isset($_POST['screen'])){
                        if($_POST['screen'] == 'edit-post' || $_POST['screen'] == 'edit-page'){
                            wpphLog('WPPH_SCREEN_EDITOR_ENABLED');
                            $GLOBALS['WPPH_SCREEN_EDITOR_ENABLED'] = true;
                        }
                        else {// Custom Post type screen
                            $type = wpph_extractCustomPostType($_POST['screen']);
                            if(WPPHPost::validatePostType($type)){
                                wpphLog('WPPH_SCREEN_EDITOR_ENABLED');
                                $GLOBALS['WPPH_SCREEN_EDITOR_ENABLED'] = true;
                            }
                        }
                    }
                }
            }
            WPPHEventWatcher::triggerWidgetMoveEvent();
            WPPHEvent::hookWatchPostStateBefore();
            WPPHEvent::hookWatchBlogActivity();
            WPPHEvent::hookWatchCategoryAdd();
            WPPHEvent::hookWatchCategoryDelete();
            WPPHEvent::hookFileDeletion();
            WPPHEvent::hookFileUploaded();
            WPPHEvent::hookFileUploadedDeleted();
            WPPHEvent::hookTrashPost();
            WPPHEvent::hookTrashPage();
            WPPHEvent::hookUntrashedPosts();
            WPPHEvent::hookUntrashedPages();
            WPPHEvent::hookThemeChange();
            WPPHEvent::hookUserRoleUpdated();
            WPPHEvent::hookUserPasswordUpdated();
            WPPHEvent::hookUserEmailUpdated();
            WPPHEvent::hookUserDeletion();
            WPPHEvent::hookEventsDeletion();
            WPPHEvent::hookWatchPluginActivity();
            /* Enable ajax functionality in the dashboard page */
            add_action('wp_ajax_wpph_get_events', array('WPPHUtil','get_events_html'));
            add_action('wp_ajax_wpph_check_user_role', array('WPPHUtil','check_user_role'));
        }
        WPPHEvent::hookLoginEvent();
        WPPHEvent::hookLogoutEvent();
        WPPHEvent::hookLoginFailure();
        WPPHEvent::hookUserRegisterEvent();
    }
}
// End wp-security-audit-log