<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\SamlProcess;

use OneLogin_Saml2_Auth;
use Piwik\Container\StaticContainer;
use Piwik\Url;
use Psr\Log\LoggerInterface;

/**
 * Class SamlProcess is component using to process Saml request with Saml Authenticate object as dependency.
 * This class contains two public methods - `login` and `process`.
 * Login method is using to redirect user with Saml request to Idp.
 * Process method responsibility is processing of Saml request from IdP into `SamlProcessResult` object.
 *
 * @package Piwik\Plugins\LoginSamlSSO\SamlProcess
 */
class SamlProcess
{
    /**
     * @var OneLogin_Saml2_Auth
     */
    private $auth;

    /**
     * @var SamlProcessAttributes
     */
    private $samlProcessAttributes;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string|null
     */
    private $currentUrlWithoutQueryString;

    /**
     * @param OneLogin_Saml2_Auth $auth
     * @param SamlProcessAttributes $samlProcessAttributes
     * @param LoggerInterface $logger
     * @param null $currentUrlWithoutQueryString
     */
    public function __construct(OneLogin_Saml2_Auth $auth, SamlProcessAttributes $samlProcessAttributes,
                                LoggerInterface $logger = null, $currentUrlWithoutQueryString = null)
    {
        if ($logger === null) {
            $logger = StaticContainer::get('Piwik\Plugins\LoginSamlSSO\Logger');
        }

        $this->auth = $auth;
        $this->samlProcessAttributes = $samlProcessAttributes;
        $this->logger = $logger;
        $this->currentUrlWithoutQueryString = $currentUrlWithoutQueryString;
    }

    /**
     * Process Saml POST request into Saml Process Result object.
     * It decode Saml request into xml, parse xml tree into, confirm that user is authenticated in SSO and process
     * result with `SamlProcessAttributes` object.
     *
     * @return SamlProcessResult
     */
    public function process()
    {
        $result = new SamlProcessResult();
        $result->setRedirectUrl(sprintf('%s?module=Login&action=login', $this->getUrlToIndexPage()));

        try {
            $this->logger->info('Waiting for response from Identity Provider');
            $this->auth->processResponse();
            $this->logger->info('Identity Provider returned a response');

            if ($this->isError()) {
                $this->logger->error('Library php-saml returned errors: "{errors}" with reason: "{reason}"', array(
                    'errors' => $this->getErrors(),
                    'reason' => $this->auth->getLastErrorReason()
                ));
            }

            if (!$this->auth->isAuthenticated()) {
                $result->setErrorMessage('User not authenticated.');
                $this->logger->info('User not authenticated in Identity Provider');
            } else {
                $this->logger->info('User authenticated in Identity Provider');
                $attributesProcessed = $this->samlProcessAttributes->process($this->auth->getAttributes(), $result);

                if ($attributesProcessed) {
                    $result->setRedirectUrl($this->getUrlToIndexPage());
                }
            }
        } catch (\OneLogin_Saml2_Error $e) {
            $result->setErrorMessage($this->getErrors());
            $this->logExceptionMessage($e);
        } catch (\Exception $e) {
            $result->setErrorMessage($this->isError() ? $this->getErrors() : $e->getMessage());
            $this->logExceptionMessage($e);
        }

        return $result;
    }

    /**
     * Proceed with Saml login redirect.
     */
    public function login()
    {
        $this->logger->info('Redirect to Identity Provider');
        $this->auth->login();
    }

    /**
     * This method parse Saml errors into string.
     *
     * @return string
     */
    private function getErrors()
    {
        return implode(', ', $this->auth->getErrors());
    }

    /**
     * This method checks if there are Saml errors.
     *
     * @return bool
     */
    private function isError()
    {
        return count($this->auth->getErrors()) ? true : false;
    }

    /**
     * This method parse plugin frontend url into Piwik main frontend url.
     * This method is using as helper for redirection.
     *
     * @return string
     */
    private function getUrlToIndexPage()
    {
        $currentUrlWithoutQueryString = $this->currentUrlWithoutQueryString;
        if ($currentUrlWithoutQueryString === null) {
            $currentUrlWithoutQueryString = Url::getCurrentUrlWithoutQueryString();
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

        return sprintf('%s/index.php', implode('/', $splitUrl));
    }

    /**
     * @param \Exception $e
     */
    private function logExceptionMessage(\Exception $e)
    {
        $this->logger->error('Exception thrown by library php-saml with message: "{message}"', array(
            'message' => $e->getMessage()
        ));
    }
}