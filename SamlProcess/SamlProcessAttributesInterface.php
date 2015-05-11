<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\SamlProcess;

use Piwik\Access;
use Piwik\Plugins\Login\SessionInitializer;

/**
 * Interface SamlProcessAttributesInterface describe how to create Saml process attributes object.
 * It introduce process method.
 * Also set user from attributes extractor, access object and session initializer object as required dependencies.
 *
 * @package Piwik\Plugins\LoginSamlSSO\SamlProcess
 */
interface SamlProcessAttributesInterface
{
    /**
     * @param UserFromAttributesExtractorInterface $userFromAttributesExtractor
     * @param Access $access
     * @param SessionInitializer $sessionInitializer
     */
    public function __construct(UserFromAttributesExtractorInterface $userFromAttributesExtractor, Access $access,
                                SessionInitializer $sessionInitializer);

    /**
     * @param array $attributes
     * @param SamlProcessResultInterface $result
     * @return bool
     */
    public function process(array $attributes, SamlProcessResultInterface $result);
}