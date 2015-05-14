<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
namespace Piwik\Plugins\LoginSamlSSO\SamlProcess;

use Piwik\Access;
use Piwik\Auth;
use Piwik\Container\StaticContainer;
use Piwik\Plugins\Login\SessionInitializer;
use Psr\Log\LoggerInterface;

/**
 * Class SamlProcessAttributes is component responsible for parsing Saml attributes into Piwik user.
 * If user will be matched then process method will authenticate and reload access for this user.
 *
 * @package Piwik\Plugins\LoginSamlSSO\SamlProcess
 */
class SamlProcessAttributes
{
    /**
     * @var Access
     */
    private $access;

    /**
     * @var SessionInitializer
     */
    private $sessionInitializer;

    /**
     * @var UserFromAttributesExtractor
     */
    private $userFromAttributesExtractor;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @param UserFromAttributesExtractor $userFromAttributesExtractor
     * @param Access $access
     * @param SessionInitializer $sessionInitializer
     * @param Auth $auth
     * @param LoggerInterface $logger
     */
    public function __construct(UserFromAttributesExtractor $userFromAttributesExtractor, Access $access,
                                SessionInitializer $sessionInitializer, Auth $auth = null,
                                LoggerInterface $logger = null)
    {
        // @codeCoverageIgnoreStart
        if ($logger === null) {
            $logger = StaticContainer::get('Piwik\Plugins\LoginSamlSSO\Logger');
        }

        if ($auth === null) {
            $auth = StaticContainer::get('Piwik\Auth');
        }
        // @codeCoverageIgnoreEnd

        $this->access = $access;
        $this->sessionInitializer = $sessionInitializer;
        $this->userFromAttributesExtractor = $userFromAttributesExtractor;
        $this->logger = $logger;
        $this->auth = $auth;

    }

    /**
     * This method process attributes into user and authenticate user if has been found in Piwik database.
     *
     * @param array $attributes
     * @param SamlProcessResult $result
     * @return bool
     */
    public function process(array $attributes, SamlProcessResult $result)
    {
        $user = $this->userFromAttributesExtractor->getUserFromAttributes($attributes);

        if (!empty($user)) {
            return $this->authenticateAndReloadAccess($user);
        } else {
            $result->setErrorMessage('User not authenticated.');
            $this->logger->error('User not authenticated in Piwik');
        }

        return false;
    }

    /**
     * Authenticate user and reload user access.
     * At the end init user session.
     *
     * @param array $user
     * @return bool
     */
    private function authenticateAndReloadAccess(array $user)
    {
        $this->auth->setLogin($user['login']);
        $this->auth->setTokenAuth($user['token_auth']);

        $this->logger->info('User with login {user} authenticated in Piwik', array('user' => $this->auth->getLogin()));

        if ($this->access->reloadAccess($this->auth)) {
            $this->sessionInitializer->initSession($this->auth, false);

            return true;
        }

        return false;
    }
}