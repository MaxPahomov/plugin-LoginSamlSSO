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
            'SAML Service Provider EntityId',
            'that identifier (must be a URI)',
            'samlServiceProviderEntityId',
            sprintf('"%s"', $baseUrlWithProtocol)
        );
    }
}
