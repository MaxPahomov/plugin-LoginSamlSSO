<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO;

use Piwik\Config as PiwikConfig;

/**
 * Class ConfigWriter is responsible for writing updated settings to config.ini.php file.
 *
 * @package Piwik\Plugins\LoginSamlSSO
 */
class ConfigWriter
{
    /**
     * @var PiwikConfig $config
     */
    private $config;

    /**
     * @codeCoverageIgnore
     * @param PiwikConfig $config
     */
    public function __construct(PiwikConfig $config = null)
    {
        if ($config === null) {
            $config = PiwikConfig::getInstance();
        }

        $this->config = $config;
    }

    /**
     * Updates specified config from LoginSamlSSO config section
     * @param $name
     * @param $value
     */
    public function update($name, $value)
    {
        $this->config->LoginSamlSSO[$name] = $value;
    }

    /**
     * Saves changes to config.ini.php file
     */
    public function save()
    {
        $this->config->forceSave();
    }
}
