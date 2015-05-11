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
 * Class UserFromAttributesExtractor is component responsible for matching attribute to user. After match it fetch them
 * from Piwik database and return object with user parameters.
 *
 * @codeCoverageIgnore
 * @package Piwik\plugins\LoginSamlSSO\SamlProcess
 */
class UserFromAttributesExtractor implements UserFromAttributesExtractorInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Model
     */
    private $usersModel;

    /**
     * @param Config $config
     * @param Model $usersModel
     */
    public function __construct(Config $config, Model $usersModel)
    {
        $this->config = $config;
        $this->usersModel = $usersModel;
    }

    /**
     * Fetch user from Piwik database based on Saml attributes and key from config.ini.php file.
     *
     * @param array $attributes
     * @return array
     */
    public function getUserFromAttributes(array $attributes)
    {
        return $this->usersModel->getUser(
            $attributes[$this->config->getSamlAttributeKey()][0]
        );
    }
}