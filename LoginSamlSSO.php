<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO;

use Piwik\Plugin;

/**
 * Class LoginSamlSSO is main plugin class. It contains events mapping and custom vendors loading.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO
 */
class LoginSamlSSO extends Plugin
{
    /**
     * @param null $pluginName
     */
    public function __construct($pluginName = null)
    {
        $this->loadVendors();

        parent::__construct($pluginName);
    }

    /**
     * Returns a list of hooks with associated event observers.
     *
     * Derived classes should use this method to associate callbacks with events.
     *
     * @return array
     */
    public function getListHooksRegistered()
    {
        return array(
            'AssetManager.getJavaScriptFiles' => 'getJavaScriptFiles',
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'Translate.getClientSideTranslationKeys' => 'getClientSideTranslationKeys',
            'Template.loginNav' => 'loginNav',
            'Controller.Login.login' => 'dispatchLoginAction'
        );
    }

    /**
     * Add plugin javascript files to Piwik assets.
     *
     * @param array &$jsFiles
     */
    public function getJavaScriptFiles(&$jsFiles)
    {
        $jsFiles[] = 'plugins/LoginSamlSSO/javascripts/SamlSSOConfiguration.js';

        $jsFiles[] = 'plugins/LoginSamlSSO/javascripts/controllers/SamlSSOForm.ctrl.js';
        $jsFiles[] = 'plugins/LoginSamlSSO/javascripts/directives/SamlSSOForm.directive.js';
        $jsFiles[] = 'plugins/LoginSamlSSO/javascripts/directives/DynamicName.directive.js';
        $jsFiles[] = 'plugins/LoginSamlSSO/javascripts/resources/SamlSSOApi.resource.js';
        $jsFiles[] = 'plugins/LoginSamlSSO/javascripts/services/SamlSSOForm.service.js';
        $jsFiles[] = 'plugins/LoginSamlSSO/javascripts/services/SamlSSOUI.service.js';
        $jsFiles[] = 'plugins/LoginSamlSSO/javascripts/constants/SamlSSOForm.constant.js';
    }

    /**
     * Add plugin stylesheet files to Piwik assets.
     *
     * @param array &$lessFiles
     */
    public function getStylesheetFiles(&$lessFiles)
    {
        $lessFiles[] = "plugins/LoginSamlSSO/stylesheets/main.less";
    }

    public function getClientSideTranslationKeys(&$translationKeys)
    {
        $translationKeys[] = 'LoginSamlSSO_HelpSamlAttributeKey';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlServiceProviderEntityId';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlServiceProviderNameIDFormat';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlServiceProviderAssertionConsumerServiceUrl';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlServiceProviderPublicCertificate';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlServiceProviderPrivateKey';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlIdentityProviderEntityId';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlIdentityProviderSingleSignOnServiceUrl';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlIdentityProviderPublicCertificate';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlIdentityProviderPrivateKey';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlLogLevel';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlSecurityAuthnRequestsSigned';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlSecuritySignMetadata';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlSecurityWantMessagesSigned';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlSecurityWantAssertionsSigned';
        $translationKeys[] = 'LoginSamlSSO_HelpSamlSecurityWantNameIdEncrypted';
        $translationKeys[] = 'LoginSamlSSO_Description';
        $translationKeys[] = 'LoginSamlSSO_Header';
        $translationKeys[] = 'LoginSamlSSO_SaveButton';

    }

    /**
     * Add link to SSO login page into Piwik login screen.
     *
     * @param string &$content
     * @param string $position
     */
    public function loginNav(&$content, $position)
    {
        switch ($position) {
            case 'bottom':
                $content .= '<a href="index.php?module=LoginSamlSSO&action=redirectToIdp">Log in with SSO</a>';
                break;
        }
    }

    /**
     * Reroute login screen error message in redirection.
     *
     * @param array &$parameters
     */
    public function dispatchLoginAction(&$parameters)
    {
        if (isset($_GET['errorMessage'])) {
            $parameters['messageNoAccess'] = urldecode($_GET['errorMessage']);
        }
    }

    /**
     * Load plugin vendors.
     */
    private function loadVendors()
    {
        require_once __DIR__ . '/vendor/autoload.php';
    }
}
