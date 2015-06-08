<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\SamlProcess;

use OneLogin_Saml2_Settings;
use Piwik\Access;
use Piwik\Plugins\Login\SessionInitializer;
use Piwik\Plugins\LoginSamlSSO\Config;
use Piwik\Plugins\LoginSamlSSO\Exception;
use Piwik\Plugins\UsersManager\Model;
use Piwik\Url;

/**
 * Class SamlProcessFactory is factory object to create SamlProcess instance.
 *
 * @package Piwik\Plugins\LoginSamlSSO\SamlProcess
 */
class SamlProcessFactory
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var SamlProcessAttributes
     */
    private $samlProcessAttributes;

    /**
     * @var null|string
     */
    private $currentUrlWithoutQueryString;

    /**
     * @param Config $config
     * @param SamlProcessAttributes $samlProcessAttributes
     * @param null|string $currentUrlWithoutQueryString
     */
    public function __construct(Config $config, SamlProcessAttributes $samlProcessAttributes = null,
                                $currentUrlWithoutQueryString = null)
    {
        $this->config = $config;

        if ($samlProcessAttributes === null) {
            // @codeCoverageIgnoreStart
            $samlProcessAttributes = new SamlProcessAttributes(
                new UserFromAttributesExtractor(
                    $this->config,
                    new Model
                ),
                Access::getInstance(),
                new SessionInitializer(
                    null,
                    null,
                    null,
                    $this->getUrlToIndexPage()
                )
            );
            // @codeCoverageIgnoreEndK
        }

        $this->samlProcessAttributes = $samlProcessAttributes;
        $this->currentUrlWithoutQueryString = $currentUrlWithoutQueryString;
    }

    /**
     * This method create SamlProcess object.
     *
     * @return SamlProcess
     * @throws Exception\ConfigException
     */
    public function get()
    {
        $samlAuthClass = $this->config->getSamlAuthClass();

        return new SamlProcess(
            new $samlAuthClass($this->getSamlSettings()),
            $this->samlProcessAttributes
        );
    }

    /**
     * @codeCoverageIgnore
     * @return OneLogin_Saml2_Settings
     */
    public function getSettings()
    {
        return new OneLogin_Saml2_Settings(
            $this->getSamlSettings()
        );
    }

    /**
     * This method return Saml configuration array.
     *
     * @return array
     * @throws Exception\ConfigException
     */
    private function getSamlSettings()
    {
        return array(
            'sp' => array(
                'entityId' => $this->config->getSamlSpEntityId(),
                'assertionConsumerService' => array(
                    'url' => $this->config->getSamlSpAssertionConsumerServiceUrl(),
                ),
                'NameIDFormat' => $this->config->getSamlSpNameIDFormat(),
                'privateKey' => $this->config->getSamlSpPrivateKey(),
                'x509cert' => $this->config->getSamlSpPublicCertificate()
            ),
            'idp' => array(
                'entityId' => $this->config->getSamlIdpEntityId(),
                'singleSignOnService' => array(
                    'url' => $this->config->getSamlIdpSingleSignOnServiceUrl(),
                ),
                'x509cert' => $this->config->getSamlIdpPublicCertificate(),
                'privateKey' => $this->config->getSamlIdpPrivateKey(),
            ),

            'security' => array(
                'authnRequestsSigned' => $this->config->getSecurityEntry('authnRequestsSigned'),
                'signMetadata' => $this->config->getSecurityEntry('signMetadata'),
                'wantMessagesSigned' => $this->config->getSecurityEntry('wantMessagesSigned'),
                'wantAssertionsSigned' => $this->config->getSecurityEntry('wantAssertionsSigned'),
                'wantNameIdEncrypted' => $this->config->getSecurityEntry('wantNameIdEncrypted')
            )
        );
    }

    /**
     * This method parse plugin frontend url into Piwik main frontend url.
     * This method is using as helper for redirection.
     *
     * @codeCoverageIgnore
     * @return string
     */
    private function getUrlToIndexPage()
    {
        $currentUrlWithoutQueryString = $this->currentUrlWithoutQueryString;
        if ($currentUrlWithoutQueryString === null) {
            $currentUrlWithoutQueryString = Url::getCurrentScriptName(false);;
        }

        $splitUrl = explode('/', $currentUrlWithoutQueryString);
        if ($splitUrl[sizeof($splitUrl) - 1] === 'index.php') {
            array_pop($splitUrl);
        }

        if ($splitUrl[sizeof($splitUrl) - 1] === 'web'
            && $splitUrl[sizeof($splitUrl) - 2] === 'LoginSamlSSO'
            && $splitUrl[sizeof($splitUrl) - 3] === 'plugins'
        ) {
            array_pop($splitUrl);
            array_pop($splitUrl);
            array_pop($splitUrl);
        }

        return sprintf('%s/', implode('/', $splitUrl));
    }
}