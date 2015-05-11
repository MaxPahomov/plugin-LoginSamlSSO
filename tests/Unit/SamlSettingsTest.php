<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\tests\Unit;

use Piwik\Plugins\LoginSamlSSO\SamlSettings;

/**
 * @group Plugins
 */
class SamlSettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SamlSettings
     */
    private $samlSettings;

    /**
     * @test
     */
    public function it_gets_all_settings()
    {
        $this->assertEquals(
            array('settingName1' => 'settingValue1', 'settingName2' => 'settingValue2'),
            $this->samlSettings->getAll()
        );
    }

    /**
     * @test
     */
    public function it_gets_setting()
    {
        $this->assertEquals('settingValue1', $this->samlSettings->get('settingName1'));
    }

    /**
     * @test
     * @expectedException Piwik\Plugins\LoginSamlSSO\Exception\SettingNotExistsException
     * @expectedExceptionMessage nonExistentSetting setting name does not exists.
     */
    public function it_gets_non_existent_setting()
    {
        $this->samlSettings->get('nonExistentSetting');
    }

    /**
     * @test
     * @expectedException Piwik\Plugins\LoginSamlSSO\Exception\SettingNotExistsException
     * @expectedExceptionMessage samlAuthClass setting name does not exists.
     */
    public function it_gets_blacklisted_setting()
    {
        $this->samlSettings->get('samlAuthClass');
    }

    /**
     * @test
     */
    public function it_updates_settings()
    {
        $this->assertNull($this->samlSettings->update(array('settingName1' => '')));
    }

    protected function setUp()
    {
        $this->samlSettings = new SamlSettings($this->getConfigWriterMock(), $this->getConfigMock());
    }

    private function getConfigWriterMock()
    {
        return $this->getMockBuilder('\Piwik\Plugins\LoginSamlSSO\ConfigWriter')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getConfigMock()
    {
        $mock = $this->getMockBuilder('\Piwik\Plugins\LoginSamlSSO\Config')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $mock->expects($this->exactly(2))
            ->method('getScope')
            ->will($this->returnValue(array(
                'settingName1' => 'settingValue1',
                'samlAuthClass' => 'is blacklisted',
                'settingName2' => 'settingValue2'
            )))
        ;

        return $mock;
    }
}
