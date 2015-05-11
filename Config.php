<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO;

use Piwik\Config as PiwikConfig;

/**
 * Class Config is facade class for configuration.
 * This class contains convenient methods using to fetch configuration from config.ini.php file.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO
 */
class Config
{
    /**
     * @var array $scope
     */
    private $scope;

    /**
     * @param null|array $scope
     */
    public function __construct($scope = null)
    {
        if ($scope === null) {
            $scope = PiwikConfig::getInstance()->LoginSamlSSO;
        }

        $this->scope = $scope;
    }

    /**
     * Get default saml authorization class namespace.
     *
     * @return string
     * @throws Exception\MissingSamlAuthClassException
     */
    public function getSamlAuthClass()
    {
        if (!isset($this->scope['samlAuthClass'])) {
            throw new Exception\MissingSamlAuthClassException();
        }

        return $this->scope['samlAuthClass'];
    }

    /**
     * Get service provider entity ID.
     *
     * @return string
     * @throws Exception\MissingSamlServiceProviderEntityIdException
     */
    public function getSamlSpEntityId()
    {
        if (!isset($this->scope['samlServiceProviderEntityId'])) {
            throw new Exception\MissingSamlServiceProviderEntityIdException();
        }

        return $this->scope['samlServiceProviderEntityId'];
    }

    /**
     * Get service provider assertions consumer service url.
     *
     * @return string
     * @throws Exception\MissingSamlServiceProviderAssertionConsumerServiceUrlException
     */
    public function getSamlSpAssertionConsumerServiceUrl()
    {
        if (!isset($this->scope['samlServiceProviderAssertionConsumerServiceUrl'])) {
            throw new Exception\MissingSamlServiceProviderAssertionConsumerServiceUrlException();
        }

        return htmlspecialchars($this->scope['samlServiceProviderAssertionConsumerServiceUrl'], ENT_QUOTES);
    }

    /**
     * Get service provider name ID format.
     *
     * @return string
     * @throws Exception\MissingSamlServiceProviderNameIDFormatException
     */
    public function getSamlSpNameIDFormat()
    {
        if (!isset($this->scope['samlServiceProviderNameIDFormat'])) {
            throw new Exception\MissingSamlServiceProviderNameIDFormatException();
        }

        return $this->scope['samlServiceProviderNameIDFormat'];
    }

    /**
     * Get service provider private key content.
     *
     * @return string
     */
    public function getSamlSpPrivateKey()
    {
        return $this->getKey('samlServiceProviderPrivateKey');
    }

    /**
     * Get service provider public key content.
     *
     * @return string
     */
    public function getSamlSpPublicCertificate()
    {
        return $this->getKey('samlServiceProviderPublicCertificate');
    }

    /**
     * Get identity provider entity ID.
     *
     * @return string
     * @throws Exception\MissingSamlIdentityProviderEntityIdException
     */
    public function getSamlIdpEntityId()
    {
        if (!isset($this->scope['samlIdentityProviderEntityId'])) {
            throw new Exception\MissingSamlIdentityProviderEntityIdException();
        }

        return $this->scope['samlIdentityProviderEntityId'];
    }

    /**
     * Get identity provider SSO service URL.
     *
     * @return string
     * @throws Exception\MissingSamlIdentityProviderSingleSignOnServiceUrlException
     */
    public function getSamlIdpSingleSignOnServiceUrl()
    {
        if (!isset($this->scope['samlIdentityProviderSingleSignOnServiceUrl'])) {
            throw new Exception\MissingSamlIdentityProviderSingleSignOnServiceUrlException();
        }

        return htmlspecialchars($this->scope['samlIdentityProviderSingleSignOnServiceUrl'], ENT_QUOTES);
    }

    /**
     * Get identity provider public key content.
     *
     * @return string
     */
    public function getSamlIdpPublicCertificate()
    {
        return $this->getKey('samlIdentityProviderPublicCertificate');
    }

    /**
     * Get path to identity provider public certificate.
     *
     * @return string
     */
    public function getSamlIdpPrivateKey()
    {
        return $this->getKey('samlIdentityProviderPrivateKey');
    }

    /**
     * Get key using to map attribute to Piwik login.
     *
     * @return string
     * @throws Exception\MissingSamlAttributeKeyException
     */
    public function getSamlAttributeKey()
    {
        if (!isset($this->scope['samlAttributeKey'])) {
            throw new Exception\MissingSamlAttributeKeyException();
        }

        return $this->scope['samlAttributeKey'];
    }

    /**
     * Get security flag for metadata, authn request and response parsing.
     *
     * @param string $entry
     * @return bool
     */
    public function getSecurityEntry($entry)
    {
        $entryName = sprintf('samlSecurity%s', ucfirst($entry));
        if (!isset($this->scope[$entryName])) {
            return false;
        }

        return $this->scope[$entryName] === 'true';
    }

    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $key
     * @return string
     */
    private function getKey($key)
    {
        if (!isset($this->scope[$key])) {
            return '';
        }

        $filePath = $this->scope[$key];
        if (!is_file($filePath) || !is_readable($filePath)) {
            return '';
        }

        return file_get_contents($filePath);
    }
}
