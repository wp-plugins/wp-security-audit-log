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
        $events = get_option(WPPH_PLUGIN_EVENTS_LIST_OPTION_NAME, array());
        if(empty($events)){
            wpphLog("Error retrieving the list of events from database. option: WPPH_PLUGIN_EVENTS_LIST_OPTION_NAME was either not found or empty.");
            return false;
        }
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


