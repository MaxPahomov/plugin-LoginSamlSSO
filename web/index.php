<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

/**
 * This files is frontend dispatcher for SAML authentication. It is using as IdP endpoint to send response.
 * Module and action is hardcoded to logMe action from plugin.
 * This is similar file to index.php from Piwik root directory.
 */

$file = realpath(dirname(__FILE__) . '/../../..');

$_GET['module'] = 'LoginSamlSSO';
$_GET['action'] = 'logMe';

if (!defined('PIWIK_DOCUMENT_ROOT')) {
    define('PIWIK_DOCUMENT_ROOT', $file == '/' ? '' : $file);
}
if (file_exists(PIWIK_DOCUMENT_ROOT . '/bootstrap.php')) {
    require_once PIWIK_DOCUMENT_ROOT . '/bootstrap.php';
}
if (!defined('PIWIK_INCLUDE_PATH')) {
    define('PIWIK_INCLUDE_PATH', PIWIK_DOCUMENT_ROOT);
}

require_once PIWIK_INCLUDE_PATH . '/core/bootstrap.php';

if (!defined('PIWIK_PRINT_ERROR_BACKTRACE')) {
    define('PIWIK_PRINT_ERROR_BACKTRACE', false);
}

require_once PIWIK_INCLUDE_PATH . '/core/dispatch.php';