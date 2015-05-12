<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\Exception;

/**
 * Class MissingSamlAttributeKeyException is Exception message with missing `samlAttributeKey` entry.
 *
 * @codeCoverageIgnore
 * @package Piwik\plugins\LoginSamlSSO\Exception
 */
class MissingSamlAttributeKeyException extends ConfigException
{
    public function __construct()
    {
        parent::__construct(
            'saml attribute key',
            'map IdP response properly',
            'samlAttributeKey',
            'login'
        );
    }
}