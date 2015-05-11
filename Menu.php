<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO;

use Piwik\Access;
use Piwik\Menu\MenuAdmin;
use Piwik\Plugin\Menu as PluginMenu;

/**
 * Class Menu is using to enable plugin menu in administration section.
 *
 * @codeCoverageIgnore
 * @package Piwik\Plugins\LoginSamlSSO
 */
class Menu extends PluginMenu
{
    public function configureAdminMenu(MenuAdmin $menu)
    {
        if (Access::getInstance()->hasSuperUserAccess()) {
            $menu->addSettingsItem(
                $menuName = 'LoginSamlSSO_ConfigureMenu',
                $url = array(
                    'module' => 'LoginSamlSSO',
                    'action' => 'configure'
                )
            );
        }
    }
}