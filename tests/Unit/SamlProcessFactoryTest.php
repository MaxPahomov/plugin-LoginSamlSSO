<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\tests\Unit;

use Piwik\Plugins\LoginSamlSSO\SamlProcess\SamlProcessFactory;

/**
 * @group Plugins
 */
class SamlProcessFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_creates_saml_process_object()
    {
        $factory = new SamlProcessFactory($this->getConfigMock(), $this->getSamlProcessAttributesMock());

        $this->assertInstanceOf('\Piwik\Plugins\LoginSamlSSO\SamlProcess\SamlProcess', $factory->get());
    }

    private function getConfigMock()
    {
        $mock = $this->getMockBuilder('\Piwik\Plugins\LoginSamlSSO\Config')
            ->disableOriginalConstructor()
            ->setMethods(array(
                'getSamlAuthClass',
                'getSamlSpEntityId',
                'getSamlSpAssertionConsumerServiceUrl',
                'getSamlSpNameIDFormat',
                'getSamlIdpEntityId',
                'getSamlIdpSingleSignOnServiceUrl',
                'getSamlIdpPublicCertificate'
            ))
            ->getMock()
        ;

        $mock->expects($this->any())
            ->method('getSamlAuthClass')
            ->will($this->returnValue('\OneLogin_Saml2_Auth'))
        ;

        $mock->expects($this->any())
            ->method('getSamlSpEntityId')
            ->will($this->returnValue('https://sp.example.com/entity'))
        ;

        $mock->expects($this->any())
            ->method('getSamlSpAssertionConsumerServiceUrl')
            ->will($this->returnValue('http://sp.example.com/acs'))
        ;

        $mock->expects($this->any())
            ->method('getSamlSpNameIDFormat')
            ->will($this->returnValue('urn:oasis:names:tc:SAML:2.0:nameid-format:entity'))
        ;

        $mock->expects($this->any())
            ->method('getSamlIdpEntityId')
            ->will($this->returnValue('https://idp.example.com/entity'))
        ;

        $mock->expects($this->any())
            ->method('getSamlIdpSingleSignOnServiceUrl')
            ->will($this->returnValue('https://idp.example.com/saml2sso'))
        ;

        return $mock;
    }

    private function getSamlProcessAttributesMock()
    {
        return $this->getMockBuilder('\Piwik\Plugins\LoginSamlSSO\SamlProcess\SamlProcessAttributes')
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }
}
