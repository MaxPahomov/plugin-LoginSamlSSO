<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\Exception;

/**
 * Class MissingSamlAuthClassException is Exception message with missing `samlAuthClass` entry.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO\Exception
 */
class MissingSamlAuthClassException extends ConfigException
{
    public function __construct()
    {
        parent::__construct(
            'Missing saml auth class name config entry. Add this to your config.ini.php file to use ' .
            'default class. ' .
            '<br /><pre>[LoginSamlSSO]<br />samlAuthClass = \\OneLogin_Saml2_Auth</pre>'
        );
    }
}