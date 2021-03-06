<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\Exception;

/**
 * Class MissingSamlIdentityProviderEntityIdException is Exception message with missing `samlIdentityProviderEntityId`
 * entry.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO\Exception
 */
class MissingSamlIdentityProviderEntityIdException extends ConfigException
{
    public function __construct()
    {
        parent::__construct(
            'SAML Identity Provider EntityId',
            'that identifier (must be a URI)',
            'samlIdentityProviderEntityId',
            'https://identity.provider.example.com/example/entityid'
        );
    }
}
