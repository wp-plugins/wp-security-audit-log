<?php
// custom function
function wpphLog($message, array $data = array()){ WPPHLogger::write($message,$data); }
/*
 * @internal
 * Debug class
 * DO NOT enable this on live website
 */
class WPPHLogger
{
    private static $_debugLoggingEnabled = false;

    private static $_fileName = 'debug.log';

    public static function enableDebugLogging(){ self::$_debugLoggingEnabled = true; }
    public static function enableErrorLogging(){ ini_set('error_log', WPPH_PLUGIN_DIR.'error.log'); }

    public static function write($message, array $data = array())
    {
        if(!self::$_debugLoggingEnabled) { return; }
        $m = '['.@date("D, M d, Y @H:i:s").'] Debug: '.$message;
        if(! empty($data)) {
            $m .= ' Data: '.var_export($data, true);
        }
        $m .= PHP_EOL;
        @file_put_contents(WPPH_PLUGIN_DIR.self::$_fileName,$m,FILE_APPEND);
    }
}