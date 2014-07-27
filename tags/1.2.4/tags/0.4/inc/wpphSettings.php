<?php
define('WPPH_PLUGIN_VERSION_OPTION_NAME','WPPH_PLUGIN_VERSION');
define('WPPH_PLUGIN_ERROR_OPTION_NAME','WPPH_PLUGIN_ERROR');
define('WPPH_PLUGIN_SETTING_NAME', 'wpph_plugin_settings');

define('WPPH_PLUGIN_DB_UPDATED', 'WPPH_PLUGIN_DB_UPDATED');
define('WPPH_PLUGIN_DEL_EVENTS_CRON_TASK_NAME', 'wpph_plugin_delete_events_cron');
/** @since v0.3 */
define('WPPH_USERS_CAN_REGISTER_OPT_NAME', 'wpph_users_can_register');
/**
 * @since v0.3
 * @see WPPH::onPluginActivate()
 */
$GLOBALS['WPPH_CAN_RUN'] = true;
/**@since 0.4*/
define('WPPH_PLUGIN_TEXT_DOMAIN', 'wp-security-audit-log');
/**@since 0.4*/
define('WPPH_E_NOTICE_TEXT', __('NOTICE',WPPH_PLUGIN_TEXT_DOMAIN));
/**@since 0.4*/
define('WPPH_E_HIGH_TEXT', __('HIGH',WPPH_PLUGIN_TEXT_DOMAIN));
/**@since 0.4*/
define('WPPH_E_WARNING_TEXT', __('WARNING',WPPH_PLUGIN_TEXT_DOMAIN));

/**@since 0.4*/
define('WPPH_KEEP_MAX_EVENTS', 5000);











