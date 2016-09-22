<?php

namespace Hk4w\Member\Service;

use Hk4w\Base\Service\EntityService;
use Hk4w\Member\Exception\Exception;

class AuthService extends EntityService
{
    const FORM_LOGIN           = 'login';
    const FORM_FORGOT_PASSWORD = 'forgot_password';
    const FORM_RESET_PASSWORD  = 'reset_password';

    const PASSWORD_ERROR            = -1;
    const PASSWORD_OLD_NOT_MATCH    = -2;
    const PASSWORD_TOKEN_NOT_FOUND  = -3;
    const PASSWORD_NOT_STRONG       = -4;

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->getServiceLocator()->get('Member.User.Service');
    }

    public function login(\Hk4w\Member\Entity\User $user)
    {
        /* @var $authService \Zend\Authentication\AuthenticationService */
        $authService = $this->getServiceLocator()->get('Member.AuthenticationService');

        if ($authService->hasIdentity()) {
            $authService->clearIdentity();
        }

        $adapter = $authService->getAdapter();
        $adapter->setIdentityValue($user->getUsername());
        $adapter->setCredentialValue($user->getPassword());
        $result = $authService->authenticate();

        if ($result->isValid()) {
            /* @var $user \Hk4w\Member\Entity\User */
            $user = $result->getIdentity();
            $this->getLog()->info("User '$user' authentified");
            $authService->getStorage()->write($result->getIdentity());

            if ($user->getPasswordResetToken()) {
                $user->setPasswordResetToken('');
                $this->getUserService()->update($user);
            }

            /* @var $sessionManager \Zend\Session\SessionManager */
            $sessionManager = $this->getServiceLocator()->get('Session');
            $sessionManager->rememberMe($sessionManager->getConfig()->getCookieLifetime());
        }

        return $result;
    }

    public function logout()
    {
        $authService = $this->getServiceLocator()->get('Member.AuthenticationService');
        $authService->getStorage()->clear();
        $authService->clearIdentity();

        /* @var $sessionManager \Zend\Session\SessionManager */
        $sessionManager = $this->getServiceLocator()->get('Session');
        $sessionManager->forgetMe();

        return true;
    }

    public function resetPassword($usernameOrEmail)
    {
        /* @var $user \Hk4w\Member\Entity\User */

        $this->getLog()->info("Reinitialization of password asked for '$usernameOrEmail'");

        //  Get User
        if (null !== ($user = $this->getRepository()->findOneBy(array('username' => $usernameOrEmail)))) {
            $this->getLog()->info("User '$usernameOrEmail' found by username");
        } elseif (null !== ($user = $this->getRepository()->findOneBy(array('email' => $usernameOrEmail)))) {
            $this->getLog()->info("User '$usernameOrEmail' found by email");
        } else {
            $this->getLog()->info("User '$usernameOrEmail' not found");
            return false;
        }

        $this->getUserService()->generateToken($user);

        $url = $this->getServiceLocator()->get('ViewHelperManager')->get('Url');
        $translate = $this->getServiceLocator()->get('ViewHelperManager')->get('Translate');

        $href = $url('reset_password', array('token' => $user->getPasswordResetToken()), array('force_canonical' => true));
        $html = new \Zend\Mime\Part(nl2br($translate('member_auth_reset_password_mail__content', null, null, array(
            'link' => '<a href="' . $href . '">' . $translate('member_auth_reset_password_mail__reset_password_link') . '</a>',
            'username'  => $user->getUsername(),
            'firstname' => $user->getFirstname(),
            'lastname'  => $user->getLastname(),
        ))));
        $html->setType(\Zend\Mime\Mime::TYPE_HTML);

        $body = new \Zend\Mime\Message();
        $body->setParts(array($html));

        /* @var $message \Hk4w\Base\Mail\Message */
        $message = $this->getServiceLocator()->get('Mail');
        $message->addTo($user->getEmail())
                ->setSubject($translate('member_auth_reset_password_mail__title'))
                ->setBody($body)
                ->setEncoding("UTF-8");

        $transport = new \Zend\Mail\Transport\Sendmail();
        $transport->send($message);

        return $user;
    }

    public function isValidToken($token)
    {
        return $this->getUserService()->getUserByToken($token) ? true : false;
    }

    public function changePassword($token, $password)
    {
        return $this->getUserService()->changePassword($token, $password);
    }

    public function create()
    {
        throw new Exception("Function " . __FUNCTION__ . " not available for " . __CLASS__);
    }

    public function update()
    {
        throw new Exception("Function " . __FUNCTION__ . " not available for " . __CLASS__);
    }

    public function delete()
    {
        throw new Exception("Function " . __FUNCTION__ . " not available for " . __CLASS__);
    }
}