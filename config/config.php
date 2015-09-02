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
            $level = $c->get('ini.LoginSamlSSO.log_level');
            return constant('Monolog\Logger::' . strtoupper($level));
        }
        return Logger::WARNING;
    }),

    'loginsamlsso.log.filename' => DI\factory(function (ContainerInterface $c) {
        if ($c->has('ini.LoginSamlSSO.logger_file_path')) {
            $file = $c->get('ini.LoginSamlSSO.logger_file_path');
            // Absolute path
            if (strpos($file, '/') === 0) {
                return $file;
            }
            // Relative to Piwik root
            return PIWIK_INCLUDE_PATH . '/' . $file;
        }
        // Default log file
        return $c->get('path.tmp') . '/logs/login-saml-sso.log';
    }),
	
	'loginsamlsso.log.file.filename' => DI\factory(function (ContainerInterface $c) {
        if ($c->has('ini.LoginSamlSSO.logger_file_path')) {
            $file = $c->get('ini.LoginSamlSSO.logger_file_path');
            // Absolute path
            if (strpos($file, '/') === 0) {
                return $file;
            }
            // Relative to Piwik root
            return PIWIK_INCLUDE_PATH . '/' . $file;
        }
        // Default log file
        return $c->get('path.tmp') . '/logs/login-saml-sso.log';
    })
);
