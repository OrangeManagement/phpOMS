<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Security
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Security;

/**
 * Php code security class.
 *
 * @package    phpOMS\Security
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class PhpCode
{
    /**
     * Deprecated functions
     *
     * @var array
     * @since 1.0.0
     */
    public static $deprecatedFunctions = [
        'apache_child_terminate', 'apache_setenv', 'define_syslog_variables', 'escapeshellarg', 'escapeshellcmd', 'eval',
        'exec', 'fp', 'fput', 'ftp_connect', 'ftp_exec', 'ftp_get', 'ftp_login', 'ftp_nb_fput', 'ftp_put', 'ftp_raw',
        'ftp_rawlist', 'highlight_file', 'ini_alter', 'ini_get_all', 'ini_restore', 'inject_code', 'mysql_pconnect',
        'openlog', 'passthru', 'php_uname', 'phpAds_remoteInfo', 'phpAds_XmlRpc', 'phpAds_xmlrpcDecode',
        'phpAds_xmlrpcEncode', 'popen', 'posix_getpwuid', 'posix_kill', 'posix_mkfifo', 'posix_setpgid', 'posix_setsid',
        'posix_setuid', 'posix_uname', 'proc_close', 'proc_get_status',
    ];

    /**
     * Normalize source code for inspection
     *
     * @param string $source Source code
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function normalizeSource(string $source) : string
    {
        return str_replace(["\n", "\r\n", "\r", "\t"], ['', '', '', ' '], $source);
    }

    /**
     * Check if has source unicode
     *
     * @param string $source Source code
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function hasUnicode(string $source) : bool
    {
        return (bool) preg_match('/[^\x20-\x7f]/', $source) || !mb_check_encoding($source, 'ASCII');
    }

    /**
     * Check if function is disabled
     *
     * @param array $functions Functions to check
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function isDisabled(array $functions) : bool
    {
        $disabled = ini_get('disable_functions');
        $disabled = str_replace(' ', '', $disabled);
        $disabled = explode(',', $disabled);

        foreach ($functions as $function) {
            if (!in_array($function, $disabled)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if has deprecated functions
     *
     * @param string $source Source code
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function hasDeprecatedFunction(string $source) : bool
    {
        foreach (self::$deprecatedFunctions as $function) {
            if (preg_match('/' . $function . '\s*\(/', $source) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate file integrety
     *
     * @param string $source Source code
     * @param string $hash   Source hash
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function validateFileIntegrity(string $source, string $hash) : array
    {

    }
}
