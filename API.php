<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO;

use Piwik\Piwik;

/**
 * The LoginSamlSSO API gives you possibility to configure SAML settings.
 *
 * @method static \Piwik\Plugins\LoginSamlSSO\API getInstance()
 */
class API extends \Piwik\Plugin\API
{
    /**
     * @var SamlSettings
     */
    private $settings;

    /**
     * @codeCoverageIgnore
     * @param SamlSettings $settings
     */
    public function __construct(SamlSettings $settings)
    {
        if ($settings === null) {
            $settings = new SamlSettings();
        }

        $this->settings = $settings;
    }

    /**
     * Returns all plugin settings from [LoginSamlSSO] section in config.ini.php file (except blacklisted)
     *
     * @return array|null|string
     */
    public function getSettings()
    {
        Piwik::checkUserHasSuperUserAccess();

        return $this->settings->getAll();
    }

    /**
     * Returns proper plugin setting from [LoginSamlSSO] section in config.ini.php file (except blacklisted)
     *
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function getSetting($name)
    {
        Piwik::checkUserHasSuperUserAccess();

        return $this->settings->get($name);
    }

    /**
     * Updates and returns all plugin settings from [LoginSamlSSO] section in config.ini.php file (except blacklisted)
     *
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function updateSettings($data)
    {
        Piwik::checkUserHasSuperUserAccess();

        return $this->settings->update($data);
    }
}

