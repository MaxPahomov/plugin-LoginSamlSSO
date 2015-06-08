<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\Exception;

/**
 * Class MissingSamlIdentityProviderSingleSignOnServiceUrlException is Exception message with missing
 * `samlIdentityProviderSingleSignOnServiceUrl` entry.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO\Exception
 */
class MissingSamlIdentityProviderSingleSignOnServiceUrlException extends ConfigException
{
    public function __construct()
    {
        parent::__construct(
            'SAML Identity Provider Single Sign On Service (sso) url',
            'that url (The URL to submit SAML authentication requests to)',
            'samlIdentityProviderSingleSignOnServiceUrl',
            'https://identity.provider.example.com/example/sso'
        );
    }
}
