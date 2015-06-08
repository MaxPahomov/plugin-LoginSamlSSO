<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\tests\UI\Mocks;

use Piwik\Plugins\LoginSamlSSO\Exception\SettingNotExistsException;
use Piwik\Plugins\LoginSamlSSO\SamlSettings;

class SettingsMock extends SamlSettings
{
    /**
     * @var array
     */
    private $settings;

    public function __construct()
    {
        $this->settings = $this->getFixtures();
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
     * @throws \Exception
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
     * @return array
     * @throws \Exception
     */
    public function update($settings)
    {
        foreach ($settings as $name => $value) {
            if (!isset($this->settings[$name])) {
                throw new SettingNotExistsException("$name setting name does not exists.");
            }
            $this->settings[$name] = $value;
        }
    }

    private function getFixtures()
    {
        return array(
            'samlAttributeKey'                               =>  'someAttributeKey',
            'samlServiceProviderNameIDFormat'                =>  'urn:oasis:names:tc:SAML:2.0:nameid-format:entity',
            'samlServiceProviderAssertionConsumerServiceUrl' =>  'http://sp.example.com/acs',
            'samlServiceProviderEntityId'                    =>  'https://sp.example.com/entity',
            'samlIdentityProviderEntityId'                   =>  'https://idp.example.com/entity' ,
            'samlIdentityProviderSingleSignOnServiceUrl'     =>  'https://idp.example.com/saml2sso',
            'samlIdentityProviderPublicCertificate'          =>  'someCertificate',
            'log_level'                                      =>  'info'
        );
    }
}
