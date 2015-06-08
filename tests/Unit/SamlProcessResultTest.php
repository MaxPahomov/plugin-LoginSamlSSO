<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\tests\Unit;

use Piwik\Plugins\LoginSamlSSO\SamlProcess\SamlProcessResult;

/**
 * @group Plugins
 */
class SamlProcessResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider getDataProvider
     * @param $redirectUrl
     * @param $errorMessage
     * @param $expectedUrl
     */
    public function it_sets_redirect_url_with_error_message($redirectUrl, $errorMessage, $expectedUrl)
    {
        $samlProcessResult = new SamlProcessResult();
        $samlProcessResult->setRedirectUrl($redirectUrl);
        $samlProcessResult->setErrorMessage($errorMessage);

        $this->assertEquals($expectedUrl, $samlProcessResult->getRedirectUrl());
    }

    public function getDataProvider()
    {
        return array(
            array('example.com', null, 'example.com'),
            array('example.com', '', 'example.com'),
            array('example.com', 'message', 'example.com?errorMessage=message'),
            array('example.com?foo=bar', 'message', 'example.com?foo=bar&errorMessage=message')
        );
    }
}
