<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\SamlProcess;


use Piwik\Plugins\LoginSamlSSO\Config;
use Piwik\Plugins\UsersManager\Model;

/**
 * Interface UserFromAttributesExtractorInterface describe how to create User form attributes extractor object.
 * It introduce getUserFromAttributes method.
 * Also set config object and users model as required dependencies.
 *
 * @package Piwik\plugins\LoginSamlSSO\SamlProcess
 */
interface UserFromAttributesExtractorInterface
{
    /**
     * @param Config $config
     * @param Model $usersModel
     */
    public function __construct(Config $config, Model $usersModel);

    /**
     * Fetch user from Piwik database based on Saml attributes and key from config.ini.php file.
     *
     * @param array $attributes
     * @return array
     */
    public function getUserFromAttributes(array $attributes);
}