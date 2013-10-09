<?php if(! defined('WPPH_PLUGIN_NAME')) return;

/**
 * @param string $error  The error to display in the admin notice
 * This function can be used to generate an admin notice error
 */
function wpph_adminNotice($error) { echo '<div id="errMessage" class="error"><p><strong>'.WPPH_PLUGIN_NAME.' '.__('Error',WPPH_PLUGIN_TEXT_DOMAIN).':</strong> '.$error.'</p></div>'; }
function wpph_adminUpdate($message) { echo '<div id="errMessage" class="updated"><p><strong>'.$message.'</strong></p></div>'; }

add_action('wpph_set_post_type', 'wpph_setPostType', 1, 1);
function wpph_setPostType($postType){
    WPPHPost::$currentPostType = $postType;
    wpphLog(__FUNCTION__.' triggered', array('postType'=>$postType));
}


/**
 * Retrieve the custom post type from the given base post type
 * @since v0.4
 * @param string $baseType The post's base type from which to extract the custom type
 * @return string The custom post type
 */
function wpph_extractCustomPostType($baseType) { return substr($baseType, strpos($baseType,'-')+1); }

/**
 * Check to see whether or not the provided event is enabled
 * @since v0.4
 * @param integer $event the event to search for
 * @param array $events Optional. The list of events where to search for $event to see if it's enabled or not
 * @return bool
 */
function wpph_isEventEnabled($event, array $events = array())
{
    if(empty($event)){ return false; }
    if(empty($events)){
        $temp = WPPH::getPluginSettings();
        $events = $temp->logEvents;
    }
    foreach($events as $k=>$entries){
        foreach($entries as $_event => $enabled){
            if(($event == $_event) && $enabled){
                return true;
            }
        }
    }
    return false;
}

// Add custom links on plugins page
function wpphCustomLinks($links) { return array_merge(array('<a href="admin.php?page=wpph_">Audit Log Viewer </a>', '<a href="admin.php?page=wpph_settings">'.__('Settings',WPPH_PLUGIN_TEXT_DOMAIN).'</a>'), $links); }
// Load text domain
function wpphLoadTextDomain() { load_plugin_textdomain(WPPH_PLUGIN_TEXT_DOMAIN, false, 'wp-security-audit-log/languages/'); }


//@see: http://codex.wordpress.org/Function_Reference/register_activation_hook#A_Note_on_Variable_Scope
global $wpphEvents;
/**
 * @array
 * @since v0.4
 * Holds the list of all events
 */
$wpphEvents = array(
    'Login_Logout' => array(
        1000 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User logs in',WPPH_PLUGIN_TEXT_DOMAIN)),
        1001 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User Logs out',WPPH_PLUGIN_TEXT_DOMAIN)),
        1002 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('Failed login detected',WPPH_PLUGIN_TEXT_DOMAIN)),
    ),
    'Pages' => array(
        2004 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User created a new WordPress page and saved it as draft',WPPH_PLUGIN_TEXT_DOMAIN)),
        2005 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User published a WorPress page',WPPH_PLUGIN_TEXT_DOMAIN)),
        2006 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User modified a published WordPress page',WPPH_PLUGIN_TEXT_DOMAIN)),
        2007 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User modified a draft WordPress page',WPPH_PLUGIN_TEXT_DOMAIN)),
        2009 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User permanently deleted a page from the trash',WPPH_PLUGIN_TEXT_DOMAIN)),
        2013 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('User moved WordPress page to the trash',WPPH_PLUGIN_TEXT_DOMAIN)),
        2015 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('User restored a WordPress page from trash',WPPH_PLUGIN_TEXT_DOMAIN)),
        2018 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed page URL',WPPH_PLUGIN_TEXT_DOMAIN)),
        2020 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed page author',WPPH_PLUGIN_TEXT_DOMAIN)),
        2022 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed page status',WPPH_PLUGIN_TEXT_DOMAIN)),
        2026 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('User changed the visibility of a page',WPPH_PLUGIN_TEXT_DOMAIN)),
        2028 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed the date of a page post',WPPH_PLUGIN_TEXT_DOMAIN)),
    ),
    'Blog_Posts' => array(
        2000 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User created a new blog post and saved it as draft',WPPH_PLUGIN_TEXT_DOMAIN)),
        2001 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User published a blog post',WPPH_PLUGIN_TEXT_DOMAIN)),
        2002 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User modified a published blog post',WPPH_PLUGIN_TEXT_DOMAIN)),
        2003 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User modified a draft blog post',WPPH_PLUGIN_TEXT_DOMAIN)),
        2008 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User permanently deleted a blog post from the trash',WPPH_PLUGIN_TEXT_DOMAIN)),
        2010 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User uploaded a file to the uploads directory',WPPH_PLUGIN_TEXT_DOMAIN)),
        2011 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('User deleted a file from the uploads directory',WPPH_PLUGIN_TEXT_DOMAIN)),
        2012 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('User moved a blog post to the trash',WPPH_PLUGIN_TEXT_DOMAIN)),
        2014 => array('type' => WPPH_E_HIGH_TEXT,   'text' => __('User restored a blog post from trash',WPPH_PLUGIN_TEXT_DOMAIN)),
        2016 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed blog post category',WPPH_PLUGIN_TEXT_DOMAIN)),
        2017 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed blog post URL',WPPH_PLUGIN_TEXT_DOMAIN)),
        2019 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed blog post author',WPPH_PLUGIN_TEXT_DOMAIN)),
        2021 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed blog post status',WPPH_PLUGIN_TEXT_DOMAIN)),
        2023 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User created new category',WPPH_PLUGIN_TEXT_DOMAIN)),
        2024 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('User deleted a category',WPPH_PLUGIN_TEXT_DOMAIN)),
        2025 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('User changed the visibility of a blog post',WPPH_PLUGIN_TEXT_DOMAIN)),
        2027 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed the date of a blog post',WPPH_PLUGIN_TEXT_DOMAIN)),
    ),
    'Custom_Posts' => array(
        2029 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User created a new custom blog post and saved it as draft',WPPH_PLUGIN_TEXT_DOMAIN)),
        2030 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User published a custom blog post',WPPH_PLUGIN_TEXT_DOMAIN)),
        2031 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User modified a published custom blog post',WPPH_PLUGIN_TEXT_DOMAIN)),
        2032 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User modified a draft custom blog post',WPPH_PLUGIN_TEXT_DOMAIN)),
        2033 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('User permanently deleted a custom blog post from the trash',WPPH_PLUGIN_TEXT_DOMAIN)),
        2034 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('User moved a custom blog post to the trash',WPPH_PLUGIN_TEXT_DOMAIN)),
        2035 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('User restored a custom blog post from trash',WPPH_PLUGIN_TEXT_DOMAIN)),
        2036 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed custom blog post category',WPPH_PLUGIN_TEXT_DOMAIN)),
        2037 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed custom blog post URL',WPPH_PLUGIN_TEXT_DOMAIN)),
        2038 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed custom blog post author',WPPH_PLUGIN_TEXT_DOMAIN)),
        2039 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed custom blog post status',WPPH_PLUGIN_TEXT_DOMAIN)),
        2040 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('User changed the visibility of a custom blog post',WPPH_PLUGIN_TEXT_DOMAIN)),
        2041 => array('type' => WPPH_E_NOTICE_TEXT, 'text' => __('User changed the date of a custom blog post',WPPH_PLUGIN_TEXT_DOMAIN)),
    ),
    'Users_Profiles' => array(
        4000 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('A new user was created on WordPress',WPPH_PLUGIN_TEXT_DOMAIN)),
        4001 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('A user created another WordPress user',WPPH_PLUGIN_TEXT_DOMAIN)),
        4002 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('The role of a user was changed by another WordPress user',WPPH_PLUGIN_TEXT_DOMAIN)),
        4003 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('User has changed his or her password',WPPH_PLUGIN_TEXT_DOMAIN)),
        4004 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('A user changed another user\'s password',WPPH_PLUGIN_TEXT_DOMAIN)),
        4005 => array('type' => WPPH_E_NOTICE_TEXT,'text' => __('User changed his or her email address',WPPH_PLUGIN_TEXT_DOMAIN)),
        4006 => array('type' => WPPH_E_NOTICE_TEXT,'text' => __('A user changed another user\'s email address',WPPH_PLUGIN_TEXT_DOMAIN)),
        4007 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('A user was deleted by another user',WPPH_PLUGIN_TEXT_DOMAIN)),
    ),
    'Plugins' => array(
        5000 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('User installed a plugin',WPPH_PLUGIN_TEXT_DOMAIN)),
        5001 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('User activated a WordPress plugin',WPPH_PLUGIN_TEXT_DOMAIN)),
        5002 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('User deactivated a WordPress plugin',WPPH_PLUGIN_TEXT_DOMAIN)),
        5003 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('User uninstalled a plugin',WPPH_PLUGIN_TEXT_DOMAIN)),
        5004 => array('type' => WPPH_E_WARNING_TEXT, 'text' => __('User upgraded a plugin',WPPH_PLUGIN_TEXT_DOMAIN)),
    ),
    'Settings_And_System_Activity' => array(
        6000 => array('type' => WPPH_E_NOTICE_TEXT,'text' => __('Events automatically pruned by system',WPPH_PLUGIN_TEXT_DOMAIN)),
        6001 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('Option Anyone Can Register in WordPress settings changed',WPPH_PLUGIN_TEXT_DOMAIN)),
        6002 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('New User Default Role changed',WPPH_PLUGIN_TEXT_DOMAIN)),
        6003 => array('type' => WPPH_E_HIGH_TEXT, 'text' => __('WordPress Administrator Notification email changed',WPPH_PLUGIN_TEXT_DOMAIN))
    ),
);