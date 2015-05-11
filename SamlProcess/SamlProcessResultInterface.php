<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\SamlProcess;

/**
 * Interface SamlProcessResultInterface describe how to create Saml Process Result object.
 *
 * @package Piwik\Plugins\LoginSamlSSO
 */
interface SamlProcessResultInterface
{
    /**
     * Set result as error result.
     * Set result error message.
     *
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage);

    /**
     * Set result as redirect to resource result.
     * Set redirect destination.
     *
     * @param string $redirectUrl
     */
    public function setRedirectUrl($redirectUrl);

    /**
     * Get redirect url. If error is persisted in object then request is extended with errorMessage parameter.
     *
     * @return string
     */
    public function getRedirectUrl();
}