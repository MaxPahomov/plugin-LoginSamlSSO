<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\tests\Integration\Stubs;

use Piwik\Config as PiwikConfig;

class PiwikConfigStub extends PiwikConfig
{
    public $LoginSamlSSO;

    public function __construct($scope)
    {
        $this->LoginSamlSSO = $scope;
    }

    public function forceSave()
    {

    }
}
