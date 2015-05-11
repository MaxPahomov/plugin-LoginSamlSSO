<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

use Interop\Container\ContainerInterface;
use Monolog\Logger;
use Piwik\Log;

return array(

    'Piwik\Plugins\LoginSamlSSO\Logger' => DI\object('Monolog\Logger')
        ->constructor('samlsso', DI\link('samlsso.log.handlers'), DI\link('log.processors')),

    'samlsso.log.handlers' => DI\factory(function (ContainerInterface $c) {
        if ($c->has('ini.LoginSamlSSO.log_writers')) {
            $writerNames = $c->get('ini.LoginSamlSSO.log_writers');
        } else {
            return array();
        }
        $classes = array(
            'file'     => 'Piwik\Plugins\LoginSamlSSO\Logger\Handler\FileHandler',
            'screen'   => 'Piwik\Plugins\LoginSamlSSO\Logger\Handler\WebNotificationHandler',
            'database' => 'Piwik\Plugins\LoginSamlSSO\Logger\Handler\DatabaseHandler',
        );
        $writerNames = array_map('trim', $writerNames);
        $writers = array();
        foreach ($writerNames as $writerName) {
            if (isset($classes[$writerName])) {
                $writers[$writerName] = $c->get($classes[$writerName]);
            }
        }
        return array_values($writers);
    }),

    'Piwik\Plugins\LoginSamlSSO\Logger\Handler\FileHandler' => DI\object()
        ->constructor(DI\link('samlsso.log.file.filename'), DI\link('samlsso.log.level'))
        ->method('setFormatter', DI\link('Piwik\Plugins\LoginSamlSSO\Logger\Formatter\LineMessageFormatter')),

    'Piwik\Plugins\LoginSamlSSO\Logger\Handler\DatabaseHandler' => DI\object()
        ->constructor(DI\link('samlsso.log.level'))
        ->method('setFormatter', DI\link('Piwik\Plugins\LoginSamlSSO\Logger\Formatter\LineMessageFormatter')),

    'Piwik\Plugins\LoginSamlSSO\Logger\Handler\WebNotificationHandler' => DI\object()
        ->constructor(DI\link('samlsso.log.level'))
        ->method('setFormatter', DI\link('Piwik\Plugins\LoginSamlSSO\Logger\Formatter\LineMessageFormatter')),

    'samlsso.log.level' => DI\factory(function (ContainerInterface $c) {
        if ($c->has('ini.LoginSamlSSO.log_level')) {
            $level = strtoupper($c->get('ini.LoginSamlSSO.log_level'));
            if (!empty($level) && defined('Piwik\Log::'.strtoupper($level))) {
                return Log::getMonologLevel(constant('Piwik\Log::'.strtoupper($level)));
            }
        }
        return Logger::WARNING;
    }),

    'samlsso.log.file.filename' => DI\factory(function (ContainerInterface $c) {
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
            $logPath = '/logs/samlsso.log';
        }

        $logPath = $c->get('path.tmp') . $logPath;
        if (is_dir($logPath)) {
            $logPath .= '/samlsso.log';
        }

        return $logPath;
    }),

    'Piwik\Plugins\LoginSamlSSO\Logger\Formatter\LineMessageFormatter' => DI\object()
        ->constructor(DI\link('samlsso.log.format')),

    'samlsso.log.format' => DI\factory(function (ContainerInterface $c) {
        if ($c->has('ini.LoginSamlSSO.string_message_format')) {
            return $c->get('ini.LoginSamlSSO.string_message_format');
        }
        return '%level% %tag%[%datetime%] %message%';
    }),
);
