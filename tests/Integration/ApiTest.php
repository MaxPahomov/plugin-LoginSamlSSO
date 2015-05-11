<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\tests\Integration;

use Piwik\Access;
use Piwik\Plugins\LoginSamlSSO\API;
use Piwik\Plugins\LoginSamlSSO\Config;
use Piwik\Plugins\LoginSamlSSO\ConfigWriter;
use Piwik\Plugins\LoginSamlSSO\SamlSettings;
use Piwik\Plugins\LoginSamlSSO\tests\Integration\Stubs\PiwikConfigStub;
use Piwik\Tests\Framework\Mock\FakeAccess;
use Piwik\Tests\Framework\TestCase\IntegrationTestCase;

/**
 * @group Plugins
 */
class ApiTest extends IntegrationTestCase
{
    /**
     * @var API
     */
    private $api;

    public function setUp()
    {
        parent::setUp();

        // setup the access layer
        $pseudoMockAccess = new FakeAccess;
        FakeAccess::$superUser = true;
        Access::setSingletonInstance($pseudoMockAccess);

        $scope = array(
            'key' => 'value',
            'samlAuthClass' => 'blacklisted value'
        );

        $this->api = new Api(new SamlSettings(new ConfigWriter(new PiwikConfigStub($scope)), new Config($scope)));
    }

    /**
     * @group Plugins
     */
    public function testGetSettings()
    {
        $this->assertEquals(array('key' => 'value'), $this->api->getSettings());
    }

    /**
     * @group Plugins
     */
    public function testGetSetting()
    {
        $this->assertEquals('value', $this->api->getSetting('key'));
    }

    /**
     * @group Plugins
     * @expectedException \Piwik\Plugins\LoginSamlSSO\Exception\SettingNotExistsException
     * @expectedExceptionMessage samlAuthClass setting name does not exists.
     */
    public function testGetBlacklistedSetting()
    {
        $this->api->getSetting('samlAuthClass');
    }

    /**
     * @group Plugins
     * @expectedException \Piwik\Plugins\LoginSamlSSO\Exception\SettingNotExistsException
     * @expectedExceptionMessage nonExistentSetting setting name does not exists.
     */
    public function testGetNonExistentSetting()
    {
        $this->api->getSetting('nonExistentSetting');
    }

    /**
     * @group Plugins
     */
    public function testUpdateSettings()
    {
        $this->assertApiResponseHasNoError($this->api->updateSettings(array('key' => 'new value')));
    }
}
