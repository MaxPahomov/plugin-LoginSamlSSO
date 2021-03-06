<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\Exception;

/**
 * Class MissingSamlServiceProviderNameIDFormatException is Exception message with missing
 * `samlServiceProviderNameIDFormat` entry.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO\Exception
 */
class MissingSamlServiceProviderNameIDFormatException extends ConfigException
{
    public function __construct()
    {
        parent::__construct(
            'SAML Service Provider NameID format to return the authentication token',
            'entity as token format',
            'samlServiceProviderNameIDFormat',
            'urn:oasis:names:tc:SAML:2.0:nameid-format:entity'
        );
    }
}
