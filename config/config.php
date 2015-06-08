<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Piwik\Log;

return array(

    'Piwik\Plugins\LoginSamlSSO\Logger' => DI\factory(function (ContainerInterface $c) {
        $handler = new StreamHandler(
            $c->get('loginsamlsso.log.file.filename'),
            $c->get('loginsamlsso.log.level')
        );

        // Use the default formatter
        $handler->setFormatter($c->get('Piwik\Plugins\Monolog\Formatter\LineMessageFormatter'));

        $logger = new Logger('loginsamlsso', array($handler));

        return $logger;

    }),

    'loginsamlsso.log.level' => DI\factory(function (ContainerInterface $c) {
        if ($c->has('ini.LoginSamlSSO.log_level')) {
            $level = strtoupper($c->get('ini.LoginSamlSSO.log_level'));
            if (!empty($level) && defined('Piwik\Log::'.strtoupper($level))) {
                return Log::getMonologLevel(constant('Piwik\Log::'.strtoupper($level)));
            }
        }
        return Logger::WARNING;
    }),

    'loginsamlsso.log.file.filename' => DI\factory(function (ContainerInterface $c) {
        if ($c->has('ini.LoginSamlSSO.logger_file_path')) {
            $logPath = $c->get('ini.LoginSamlSSO.logger_file_path');

            // Absolute path
            if (strpos($logPath, '/') === 0) {
                return $logPath;
            }

            // Remove 'tmp/' at the beginning
            if (strpos($logPath, 'tmp/') === 0) {
                $logPath = substr($logPath, strlen('tmp'));
            }
        } else {
            // Default log file
            $logPath = '/logs/login-saml-sso.log';
        }

        $logPath = $c->get('path.tmp') . $logPath;
        if (is_dir($logPath)) {
            $logPath .= '/login-saml-sso.log';
        }

        return $logPath;
    })
);
