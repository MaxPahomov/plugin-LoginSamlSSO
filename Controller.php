<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO;

use OneLogin_Saml2_Metadata;
use OneLogin_Saml2_Settings;
use Piwik\Piwik;
use Piwik\Plugin\ControllerAdmin as PluginController;
use Piwik\Url;
use Piwik\View;

/**
 * Class Controller is using to enable endpoints for plugin.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO
 */
class Controller extends PluginController
{
    /**
     * @var SamlProcess\SamlProcessFactory
     */
    private $samlProcessFactory;

    /**
     * @param SamlProcess\SamlProcessFactory $samlProcessFactory
     */
    public function __construct(SamlProcess\SamlProcessFactory $samlProcessFactory = null)
    {
        parent::__construct();

        if ($samlProcessFactory === null) {
            $samlProcessFactory = new SamlProcess\SamlProcessFactory(
                new Config()
            );
        }

        $this->samlProcessFactory = $samlProcessFactory;
    }

    /**
     * LogMe action is using to parse identity provider response into attributes, sign them and redirect to proper
     * url as the result of process.
     * This method is using SAML attributes to log in SSO user as Piwik user.
     *
     * @throws Exception\ConfigException
     */
    public function logMe()
    {
        Url::redirectToUrl(
            $this->samlProcessFactory->get()->process()->getRedirectUrl()
        );
    }

    /**
     * RedirectToIdp action redirect user to identity provider with SAML request. (based on Piwik configuration)
     *
     * @throws Exception\ConfigException
     */
    public function redirectToIdp()
    {
        $this->samlProcessFactory->get()->login();
    }

    /**
     * Configure action is end-point for administration page.
     */
    public function configure()
    {
        Piwik::checkUserHasSuperUserAccess();

        $view = new View('@LoginSamlSSO/configure');
        $this->setBasicVariablesView($view);

        echo $view->render();
    }

    public function getMetaData()
    {
        Piwik::checkUserHasSuperUserAccess();

        header('Content-Type: text/xml');

        $settings = $this->samlProcessFactory->getSettings();
        $securityData = $settings->getSecurityData();

        echo OneLogin_Saml2_Metadata::builder(
            $settings->getSPData(),
            $securityData['authnRequestsSigned'],
            $securityData['wantAssertionsSigned']
        );
    }
}