<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\SamlProcess;

/**
 * Class SamlProcessResult persist Saml process result.
 * This object persist error message and redirect destination.
 *
 * @package Piwik\Plugins\LoginSamlSSO\SamlProcess
 */
class SamlProcessResult
{
    /**
     * @var string
     */
    private $errorMessage;

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * Set result error message.
     *
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * Set redirect destination.
     *
     * @param string $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * Get redirect url. If error is persisted in object then request is extended with errorMessage parameter.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        $redirectUrl = $this->redirectUrl;

        if (!empty($this->errorMessage)) {
            if (strpos($redirectUrl, '?') !== false) {
                $redirectUrl .= '&';
            } else {
                $redirectUrl .= '?';
            }

            $redirectUrl .= 'errorMessage=' . urlencode($this->errorMessage);
        }

        return $redirectUrl;
    }
}