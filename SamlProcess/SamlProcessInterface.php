<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\SamlProcess;

/**
 * Interface SamlProcessInterface describe how to create Saml process object.
 * It introduce process method and login method.
 * Also set Saml2 Auth object and Saml process attributes object as required dependencies.
 *
 * @package Piwik\Plugins\LoginSamlSSO\SamlProcess
 */
interface SamlProcessInterface
{
    /**
     * Process Saml POST request and Saml Process Result object.
     *
     * @return SamlProcessResultInterface
     */
    public function process();

    /**
     * Proceed with Saml login redirect.
     */
    public function login();
}