<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\plugins\LoginSamlSSO\tests\Unit\SamlProcess;

require_once __DIR__ . '/../../../vendor/autoload.php';

use OneLogin_Saml2_Auth;
use OneLogin_Saml2_Error;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Piwik\Plugins\LoginSamlSSO\SamlProcess\SamlProcess;
use Piwik\Plugins\LoginSamlSSO\SamlProcess\SamlProcessAttributes;
use Psr\Log\LoggerInterface;

/**
 * Class SamlProcessTest
 * @package Piwik\plugins\LoginSamlSSO\tests\Unit\SamlProcess
 * @group LoginSamlSSO
 * @group SamlProcess
 */
class SamlProcessTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function processShouldMarkResultAsFailedWhenProcessResponseThrowException()
    {
        $exceptionMessage = 'exception_message';

        $saml2_Auth = $this->getAuthMock();
        $saml2_Auth->expects($this->once())
            ->method('processResponse')
            ->willThrowException(new \Exception($exceptionMessage));

        $saml2_Auth->expects($this->once())
            ->method('getErrors')
            ->willReturn(array());

        $samlProcess = new SamlProcess(
            $saml2_Auth,
            $this->getProcessAttributesMock(),
            $this->getLoggerInterfaceMock()
        );

        $this->assertContains('&errorMessage=' . $exceptionMessage, $samlProcess->process()->getRedirectUrl());
    }

    /**
     * @test
     */
    public function processShouldLogExceptionWhenProcessResponseThrowException()
    {
        $exceptionMessage = 'exception_message';

        $saml2_Auth = $this->getAuthMock();
        $saml2_Auth->expects($this->once())
            ->method('processResponse')
            ->willThrowException(new \Exception($exceptionMessage));

        $saml2_Auth->expects($this->once())
            ->method('getErrors')
            ->willReturn(array());

        $logger = $this->getLoggerInterfaceMock();
        $logger->expects($this->once())
            ->method('error')
            ->willReturn(null);

        $samlProcess = new SamlProcess(
            $saml2_Auth,
            $this->getProcessAttributesMock(),
            $logger
        );

        $this->assertContains('&errorMessage=' . $exceptionMessage, $samlProcess->process()->getRedirectUrl());
    }

    /**
     * @test
     */
    public function processShouldReturnErrorsWhenProcessResponseThrowException()
    {
        $saml2_Auth = $this->getAuthMock();
        $saml2_Auth->expects($this->once())
            ->method('processResponse')
            ->willThrowException(new \Exception());

        $saml2_Auth->expects($this->exactly(2))
            ->method('getErrors')
            ->willReturn(array('error1', 'error2'));

        $samlProcess = new SamlProcess(
            $saml2_Auth,
            $this->getProcessAttributesMock(),
            $this->getLoggerInterfaceMock()
        );

        $this->assertContains('&errorMessage=error1%2C+error2', $samlProcess->process()->getRedirectUrl());
    }

    /**
     * @test
     */
    public function processShouldReturnErrorsWhenProcessResponseThrowError()
    {
        $saml2_Auth = $this->getAuthMock();
        $saml2_Auth->expects($this->once())
            ->method('processResponse')
            ->willThrowException(new OneLogin_Saml2_Error('Exception test.'));

        $saml2_Auth->expects($this->once())
            ->method('getErrors')
            ->willReturn(array('error1', 'error2'));

        $samlProcess = new SamlProcess(
            $saml2_Auth,
            $this->getProcessAttributesMock(),
            $this->getLoggerInterfaceMock()
        );

        $this->assertContains('&errorMessage=error1%2C+error2', $samlProcess->process()->getRedirectUrl());
    }

    /**
     * @test
     */
    public function processShouldLogExceptionWhenProcessResponseThrowError()
    {
        $saml2_Auth = $this->getAuthMock();
        $saml2_Auth->expects($this->once())
            ->method('processResponse')
            ->willThrowException(new OneLogin_Saml2_Error('Exception test.'));

        $saml2_Auth->expects($this->once())
            ->method('getErrors')
            ->willReturn(array());

        $logger = $this->getLoggerInterfaceMock();
        $logger->expects($this->once())
            ->method('error')
            ->willReturn(null);

        $samlProcess = new SamlProcess(
            $saml2_Auth,
            $this->getProcessAttributesMock(),
            $logger
        );

        $samlProcess->process();
    }

    /**
     * @test
     */
    public function processShouldReturnErrorsWhenIsAuthenticatedReturnFalse()
    {
        $saml2_Auth = $this->getAuthMock(array('processResponse', 'getErrors', 'isAuthenticated', 'getLastErrorReason'));
        $saml2_Auth->expects($this->once())
            ->method('processResponse')
            ->willReturn(true);

        $saml2_Auth->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(false);

        $saml2_Auth->expects($this->once())
            ->method('getLastErrorReason')
            ->willReturn('');

        $saml2_Auth->expects($this->exactly(2))
            ->method('getErrors')
            ->willReturn(array('error1', 'error2'));

        $samlProcess = new SamlProcess(
            $saml2_Auth,
            $this->getProcessAttributesMock(),
            $this->getLoggerInterfaceMock()
        );

        $this->assertContains('&errorMessage=User+not+authenticated.', $samlProcess->process()->getRedirectUrl());
    }

    /**
     * @test
     */
    public function processShouldRunAttributesProcessWhenUserIsAuthenticated()
    {
        $saml2_Auth = $this->getAuthMock(
            array('processResponse', 'isAuthenticated', 'getAttributes')
        );
        $saml2_Auth->expects($this->once())
            ->method('processResponse')
            ->willReturn(true);

        $saml2_Auth->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $saml2_Auth->expects($this->once())
            ->method('getAttributes')
            ->willReturn(array());

        $processAttributes = $this->getProcessAttributesMock(array('process'));
        $processAttributes->expects($this->once())
            ->method('process')
            ->willReturn(false);

        $samlProcess = new SamlProcess(
            $saml2_Auth,
            $processAttributes,
            $this->getLoggerInterfaceMock()
        );

        $this->assertNotContains('&errorMessage', $samlProcess->process()->getRedirectUrl());
    }

    /**
     * @test
     */
    public function processShouldRunAttributesProcessWhenUserIsAuthenticatedAndChangeRedirectUrl()
    {
        $saml2_Auth = $this->getAuthMock(
            array('processResponse', 'isAuthenticated', 'getAttributes')
        );
        $saml2_Auth->expects($this->once())
            ->method('processResponse')
            ->willReturn(true);

        $saml2_Auth->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $saml2_Auth->expects($this->once())
            ->method('getAttributes')
            ->willReturn(array());

        $processAttributes = $this->getProcessAttributesMock(array('process'));
        $processAttributes->expects($this->once())
            ->method('process')
            ->willReturn(true);

        $piwikUrl = 'http://demo.piwik.org/piwik/webroot/index.php';
        $samlProcess = new SamlProcess(
            $saml2_Auth,
            $processAttributes,
            $this->getLoggerInterfaceMock(),
            $piwikUrl
        );

        $this->assertEquals($piwikUrl, $samlProcess->process()->getRedirectUrl());
    }

    /**
     * @test
     */
    public function processShouldRunAttributesProcessWhenUserIsAuthenticatedAndChangeRedirectUrlIntoSplit()
    {
        $saml2_Auth = $this->getAuthMock(
            array('processResponse', 'isAuthenticated', 'getAttributes')
        );
        $saml2_Auth->expects($this->once())
            ->method('processResponse')
            ->willReturn(true);

        $saml2_Auth->expects($this->once())
            ->method('isAuthenticated')
            ->willReturn(true);

        $saml2_Auth->expects($this->once())
            ->method('getAttributes')
            ->willReturn(array());

        $processAttributes = $this->getProcessAttributesMock(array('process'));
        $processAttributes->expects($this->once())
            ->method('process')
            ->willReturn(true);

        $piwikUrl = 'http://demo.piwik.org/piwik/webroot/plugins/LoginSamlSSO/web/index.php';
        $samlProcess = new SamlProcess(
            $saml2_Auth,
            $processAttributes,
            $this->getLoggerInterfaceMock(),
            $piwikUrl
        );

        $this->assertEquals('http://demo.piwik.org/piwik/webroot/index.php', $samlProcess->process()->getRedirectUrl());
    }

    /**
     * @test
     */
    public function loginShouldRunLoginAuthMethod()
    {
        $saml2_Auth = $this->getAuthMock(
            array('login')
        );
        $saml2_Auth->expects($this->once())
            ->method('login')
            ->willReturn(true);

        $samlProcess = new SamlProcess(
            $saml2_Auth,
            $this->getProcessAttributesMock(),
            $this->getLoggerInterfaceMock()
        );

        $samlProcess->login();
    }

    /**
     * @param array $methods
     * @return OneLogin_Saml2_Auth|PHPUnit_Framework_MockObject_MockObject
     */
    protected function getAuthMock($methods = array('processResponse', 'getErrors'))
    {
        return $this->getMockBuilder('\OneLogin_Saml2_Auth')
            ->setMethods($methods)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param array $methods
     * @return PHPUnit_Framework_MockObject_MockObject|SamlProcessAttributes
     */
    protected function getProcessAttributesMock($methods = array())
    {
        $builder = $this->getMockBuilder('\Piwik\Plugins\LoginSamlSSO\SamlProcess\SamlProcessAttributes')
            ->disableOriginalConstructor();

        if (!empty($methods)) {
            $builder->setMethods(array_merge(array('__construct'), $methods));

            $mock = $builder->getMock();

            if (!in_array('__construct', $methods, true)) {
                $mock->expects($this->never())
                    ->method('__construct')
                    ->willReturnSelf();
            }

            return $mock;
        }

        return $builder->getMock();
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|LoggerInterface
     */
    protected function getLoggerInterfaceMock()
    {
        return $this->getMockBuilder('\Psr\Log\LoggerInterface')
            ->getMock();
    }
}