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
 * Class MissingSamlServiceProviderEntityIdException is Exception message with missing `samlServiceProviderEntityId`
 * entry.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO\Exception
 */
class MissingSamlServiceProviderEntityIdException extends ConfigException
{
    public function __construct()
    {
        $baseUrlWithProtocol = Url::getCurrentScheme() . '://' . Url::getHost();

        parent::__construct(
            'Missing SAML Service Provider EntityId config entry. Add this to your config.ini.php file to use ' .
            'that identifier (must be a URI). ' .
            '<br /><pre>[LoginSamlSSO]<br />samlServiceProviderEntityId = ' .
            sprintf('"%s"', $baseUrlWithProtocol) .
            '</pre>'
        );
    }
}
