<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\Exception;

/**
 * Class MissingSamlIdentityProviderPublicCertificateException is Exception message with missing
 * `samlIdentityProviderPublicCertificate` entry.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO\Exception
 */
class MissingSamlIdentityProviderPublicCertificateException extends ConfigException
{
    public function __construct()
    {
        parent::__construct(
            'Missing SAML Identity Provider Public Certificate config entry. Add this to your config.ini.php file to use ' .
            '(The x509 certificate used to authenticate the request.). ' .
            '<br /><pre>[LoginSamlSSO]<br />samlIdentityProviderPublicCertificate = </pre>'
        );
    }
}
