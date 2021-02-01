<?php

namespace App\Domain\User\Service;

use App\Service\Service;
use App\Provider\ExternalAuth;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Data\Payload;
use App\Domain\User\Data\User;
use App\Domain\User\Repository\UserRepository as CurrentUser;
use App\Factory\SettingsFactory;

class Authenticate extends Service
{
    private $auth;
    private $session;
    private $user;

    public function __construct(Session $session, ExternalAuth $auth, CurrentUser $user, SettingsFactory $settings)
    {
        $this->session = $session;
        $this->auth = $auth;
        if (!$this->session->get('site_private_token')) {
            $this->session->invalidate();
            $this->session->start();
            $this->session->set('site_private_token', $this->generateToken());
        }
        $this->user = $user;
        parent::__construct($settings);
        $this->modules = $this->settings->getSettingsByKey('modules');
        $this->payload = new Payload();
    }

    public function beginAuthentication()
    {
        if (!$this->modules['personal_bans']) {
            $this->payload->throwError(500, "This module is not enabled");
            return $this->payload;
        }
        $response = $this->auth->createSession(
            $this->session->get('site_private_token')
        );
        $this->session->set(
            'session_private_token',
            $response->session_private_token
        );
        $this->session->set(
            'session_public_token',
            $response->session_public_token
        );
        $this->payload->setRedirect($this->auth->getRedirect(
            $this->session->get('session_public_token')
        ));
        return $this->payload;
    }

    public function confirmAuthentication()
    {
        if (!$this->modules['personal_bans']) {
            $this->payload->throwError(500, "This module is not enabled");
            return $this->payload;
        }
        $response = $this->auth->fetchSessionInfo(
            $this->session->get('site_private_token'),
            $this->session->get('session_private_token')
        );
        if ("OK" != $response->status) {
            die($response->error);
        }
        $user = new User($response->byond_ckey, $this->user->getUserRank($response->byond_ckey));
        $this->payload->addData('user', $user);
        $this->payload->addSuccessMessage("You have logged in as $user->displayName");
        $this->session->set('user', $user);
        return $this->payload;
    }

    public function destroySession()
    {
        $this->session->invalidate();
        $this->payload->setRedirect($this->auth->getLogoutRedirect());
        return $this->payload;
    }

    private function generateToken(bool $secure = true)
    {
        $r_bytes = openssl_random_pseudo_bytes(5120, $secure);
        return hash('sha512', $r_bytes);
    }
}
