<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\Exception;

use Piwik\Url;

/**
 * Class MissingSamlServiceProviderAssertionConsumerServiceUrlException is Exception message with missing
 * `samlServiceProviderAssertionConsumerServiceUrl` entry.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO\Exception
 */
class MissingSamlServiceProviderAssertionConsumerServiceUrlException extends ConfigException
{
    public function __construct()
    {
        $baseUrlWithProtocol = Url::getCurrentScheme() . '://' . Url::getHost();

        parent::__construct(
            'SAML Service Provider Assertion Consumer Service (acs) url',
            'specified return url (URL where to the SAML Response/SAML Assertion will be posted)',
            'samlServiceProviderAssertionConsumerServiceUrl',
            sprintf(
                '"%s/plugins/LoginSamlSSO/web/index.php"',
                $baseUrlWithProtocol
            )
        );
    }
}
