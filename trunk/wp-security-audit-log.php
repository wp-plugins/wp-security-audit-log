<?php
/*
Plugin Name: WP Security Audit Log
Plugin URI: http://www.wpprohelp.com/wordpress-security-plugins/wp-security-audit-log/
Description: Identify WordPress security issues before they become a problem and keep track of everything happening on your WordPress, including WordPress users activity. Similar to Windows Event Log and Linux Syslog, WP Security Audit Log will generate an event for every action it logs. Use the Audit Log Viewer to see all the events.
Author: WPProHelp
Contributors: kyos
Version: 0.1
Author URI: http://www.wpprohelp.com/
License: GPL2

    WP Security Audit Log
    Copyright(c) 2013  Robert Abela  (email : robert@wpprohelp.com)

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
//#! Holds the plugin option name
define('WPPH_PLUGIN_SETTING_NAME', 'wpph_plugin_settings');
define('WPPH_PLUGIN_PREFIX', 'wpph_');
define('WPPH_PLUGIN_NAME', 'WP Security Audit Log');
define('WPPH_PLUGIN_URL', trailingslashit(plugins_url('', __FILE__)));
define('WPPH_PLUGIN_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('WPPH_PLUGIN_BASE_NAME', basename(__DIR__));

//#! Load required files
require('inc/WPPHLogger.php');
require('inc/WPPHUtil.php');
require('inc/WPPHAdminNotices.php');
require('inc/WPPHDatabase.php');
require('inc/WPPHEvent.php');
require('inc/WPPH.php');

//#! Create tables
register_activation_hook( __FILE__, array('WPPHDatabase', 'checkTables') );

//#! Load resources
add_action('admin_init', array('WPPH', 'loadBaseResources'));

//#! Add the sidebar menu
add_action('admin_menu', array('WPPH', 'createPluginWpSidebar'));

//#! Bind hooks
WPPHEvent::bindHooks(array('hookLoginEvent','hookLogoutEvent','hookUserRegisterEvent','hookEventsDeletion','hookUserRoleUpdated','hookUserPasswordUpdated','hookUserEmailUpdated','hookLoginFailure','hookUserDeletion','hookWatchPluginActivity','hookWatchBlogActivity','hookFileDeletion','hookFileUploaded','hookFileUploadedDeleted','hookThemeChange','hookTrashPost','hookTrashPage','hookUntrashedPosts','hookUntrashedPages'));

/* Enable ajax functionality in the settings page */
add_action('wp_ajax_wpph_get_events', array('WPPHUtil','get_events_html'));
//#! End wp-security-audit-log