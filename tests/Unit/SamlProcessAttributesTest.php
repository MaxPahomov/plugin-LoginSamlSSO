<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\tests\Unit;

use Piwik\Plugins\LoginSamlSSO\SamlProcess\SamlProcessAttributes;

/**
 * @group Plugins
 */
class SamlProcessAttributesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider getDataProvider
     * @param $user
     * @param $reload
     * @param $expected
     */
    public function it_process_attributes_into_user_and_authenticate_user($user, $reload, $expected)
    {
        $samlProcessAttributes = new SamlProcessAttributes(
            $this->getUserFromAttributesExtractorMock($user),
            $this->getAccessMock($reload),
            $this->getSessionInitializerMock(),
            $this->getAuthMock(),
            $this->getLoggerMock()
        );

        $this->assertEquals($expected, $samlProcessAttributes->process(array(), $this->getSamlProcessResultMock()));
    }

    public function getDataProvider()
    {
        return array(
            array(array('login' => '', 'token_auth' => ''), true, true),
            array(array('login' => '', 'token_auth' => ''), false, false),
            array(null, null, false)
        );
    }

    private function getUserFromAttributesExtractorMock($user)
    {
        $mock = $this->getMockBuilder('\Piwik\Plugins\LoginSamlSSO\SamlProcess\UserFromAttributesExtractor')
            ->disableOriginalConstructor()
            ->setMethods(array('getUserFromAttributes'))
            ->getMock()
        ;

        $mock->expects($this->once())
            ->method('getUserFromAttributes')
            ->will($this->returnValue($user))
        ;

        return $mock;
    }

    private function getAccessMock($reload)
    {
        $mock = $this->getMockBuilder('\Piwik\Access')
            ->disableOriginalConstructor()
            ->setMethods(array('reloadAccess'))
            ->getMock()
        ;

        $mock->expects($this->any())
            ->method('reloadAccess')
            ->will($this->returnValue($reload))
        ;

        return $mock;

    }

    private function getSessionInitializerMock()
    {
        return $this->getMockBuilder('\Piwik\Plugins\Login\SessionInitializer')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getLoggerMock()
    {
        return $this->getMockBuilder('\Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    private function getAuthMock()
    {
        return $this->getMockBuilder('\Piwik\Auth')
            ->disableOriginalConstructor()
            ->getMock()
            ;
    }

    private function getSamlProcessResultMock()
    {
        return $this->getMockBuilder('\Piwik\Plugins\LoginSamlSSO\SamlProcess\SamlProcessResult')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
