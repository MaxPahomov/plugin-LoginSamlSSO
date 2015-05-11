<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO;

use Piwik\Plugins\LoginSamlSSO\Exception\SettingNotExistsException;

/**
 * Class Settings is facade class for editable configuration.
 *
 * @package Piwik\Plugins\LoginSamlSSO
 */
class SamlSettings
{
    /**
     * @var array
     */
    private $settings;

    /**
     * @var array
     */
    private $blacklisted = array(
        'samlAuthClass',
        'log_writers',
        'logger_file_path',
        'string_message_format'
    );

    /**
     * @var ConfigWriter
     */
    private $configWriter;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param ConfigWriter $configWriter
     * @param Config $config
     */
    public function __construct(ConfigWriter $configWriter = null, Config $config = null)
    {
        // @codeCoverageIgnoreStart
        if (is_null($configWriter)) {
            $configWriter = new ConfigWriter();
        }

        if (is_null($config)) {
            $config = new Config();
        }
        // @codeCoverageIgnoreEnd

        $this->configWriter = $configWriter;
        $this->config = $config;

        $this->init();
    }

    /**
     * Returns settings which can be edited
     * @return array
     */
    public function getAll()
    {
        return $this->settings;
    }

    /**
     * Return value of proper setting's name
     *
     * @param $name
     * @return mixed
     * @throws SettingNotExistsException
     */
    public function get($name)
    {
        if (!isset($this->settings[$name])) {
            throw new SettingNotExistsException("$name setting name does not exists.");
        }

        return $this->settings[$name];
    }

    /**
     * Updates and returns all settings which can be edited
     * @param $settings
     * @throws SettingNotExistsException
     */
    public function update($settings)
    {
        foreach ($settings as $name => $value) {
            $this->configWriter->update($name, $value);
        }

        $this->configWriter->save();
    }

    /**
     * Filter config variables (variables from blacklist are removed) and prepare settings which can be edited
     */
    private function init()
    {
        $blacklisted = $this->blacklisted;

        $keys = array_filter(
            array_keys($this->config->getScope()),
            function ($key) use ($blacklisted) {
                return !in_array($key, $blacklisted);
            }
        );

        $this->settings = array_intersect_key($this->config->getScope(), array_flip($keys));
    }
}
