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
            'Missing saml attribute key config entry. Add this to your config.ini.php file to use ' .
            'map IdP response properly. ' .
            '<br /><pre>[LoginSamlSSO]<br />samlAttributeKey = employee_number</pre>'
        );
    }
}