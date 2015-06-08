<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\tests\Fixtures;

use Piwik\Tests\Fixtures\UITestFixture;

class Settings extends UITestFixture
{
    public function provideContainerConfig()
    {
        return array(
            'Piwik\Plugins\LoginSamlSSO\SamlSettings' => \DI\object('Piwik\Plugins\LoginSamlSSO\tests\UI\Mocks\SettingsMock'),
        );
    }
}
