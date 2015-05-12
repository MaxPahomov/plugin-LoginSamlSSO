<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\Exception;

use Piwik\Exception\Exception;

/**
 * Class ConfigException is abstract Exception class with enabled Html message exception.
 * It's based class of Config exception from its namespace.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO\Exception
 */
abstract class ConfigException extends Exception
{
    /**
     * @param string $entryName
     * @param string $usage
     * @param string $entryKey
     * @param string$sampleEntryValue
     */
    public function __construct($entryName, $usage, $entryKey, $sampleEntryValue)
    {
        parent::__construct(
            sprintf(
                'Missing %s config entry. Add this to your config.ini.php file to use %s. ' .
                '<br /><pre>[LoginSamlSSO]<br />%s = %s</pre>',
                $entryName,
                $usage,
                $entryKey,
                $sampleEntryValue
            )
        );

        $this->setIsHtmlMessage();
    }
}