<?php

namespace Hk4w\Member\Service;

//use Hk4w\Base\Service\EntityService;

class UserService extends EntityService
{
    const FORM_LDAP     = 'ldap';
    const HYDRATOR_LDAP = 'ldap';

    const PASSWORD_ERROR            = 'error';
    const PASSWORD_OLD_NOT_MATCH    = 'password_not_match';
    const PASSWORD_TOKEN_NOT_FOUND  = 'token_not_found';
    const PASSWORD_TOKEN_EMPTY      = 'token_empty';
    const PASSWORD_NOT_STRONG       = 'password_not_strong';

    public function getForm($type = self::FORM_DEFAULT)
    {
        $form = parent::getForm($type);

        if (method_exists($form, 'setGroups')) {
            /* @var $groupService GroupService */
            $groupService = $this->getServiceLocator()->get('Member.Group.Service');
            $form->setGroups($groupService->getRepository()->findBy(array(), array(
                'name' => 'ASC',
            )));
        }

        if (method_exists($form, 'setSites')) {
            /* @var $siteService SiteService */
            $siteService = $this->getServiceLocator()->get('Member.Site.Service');
            $form->setSites($siteService->getRepository()->findBy(array(), array(
                'name' => 'ASC',
            )));
        }

        return $form;
    }

    /**
     * @param \Hk4w\Member\Entity\User $user
     * @return UserService
     * @throws \Hk4w\Member\Exception\Exception
     */
    public function create(\Hk4w\Member\Entity\User $user = null)
    {
        if (!$user instanceof \Hk4w\Member\Entity\User) {
            throw new \Hk4w\Member\Exception\Exception('$user must be an instance of \Hk4w\Member\Entity\User');
        }

        if (parent::create($user)) {
            $this->getLog()->info("User '$user' was created");
        }

        return $this;
    }

    /**
     * @param \Hk4w\Member\Entity\User $user
     * @return UserService
     * @throws \Hk4w\Member\Exception\Exception
     */
    public function update(\Hk4w\Member\Entity\User $user = null)
    {
        if (!$user instanceof \Hk4w\Member\Entity\User) {
            throw new \Hk4w\Member\Exception\Exception('$user must be an instance of \Hk4w\Member\Entity\User');
        }

        if (parent::update($user)) {
            $this->getLog()->info("User '$user' was updated");
        }

        return $this;
    }

    /**
     * @param \Hk4w\Member\Entity\User $user
     * @return UserService
     * @throws \Hk4w\Member\Exception\Exception
     */
    public function delete(\Hk4w\Member\Entity\User $user = null)
    {
        if (!$user instanceof \Hk4w\Member\Entity\User) {
            throw new \Hk4w\Member\Exception\Exception('$user must be an instance of \Hk4w\Member\Entity\User');
        }

        if (parent::delete($user)) {
            $this->getLog()->info("User '$user' was deleted");
        }

        return $this;
    }

    /**
     * @param \Hk4w\Member\Entity\User $user
     * @return UserService
     * @throws \Hk4w\Member\Exception\Exception
     */
    public function enable(\Hk4w\Member\Entity\User $user = null)
    {
        if (!$user instanceof \Hk4w\Member\Entity\User) {
            throw new \Hk4w\Member\Exception\Exception('$user must be an instance of \Hk4w\Member\Entity\User');
        }

        $user->setEnable(true);

        if (parent::update($user)) {
            $this->getLog()->info("User '$user' was enabled");
        }

        return $this;
    }

    /**
     * @param \Hk4w\Member\Entity\User $user
     * @return UserService
     * @throws \Hk4w\Member\Exception\Exception
     */
    public function disable(\Hk4w\Member\Entity\User $user = null)
    {
        if (!$user instanceof \Hk4w\Member\Entity\User) {
            throw new \Hk4w\Member\Exception\Exception('$user must be an instance of \Hk4w\Member\Entity\User');
        }

        $user->setEnable(false);

        if (parent::update($user)) {
            $this->getLog()->info("User '$user' was disabled");
        }

        return $this;
    }

    public function generateToken(\Hk4w\Member\Entity\User $user)
    {
        $user->setPasswordResetToken(bin2hex(openssl_random_pseudo_bytes(30)));
        $this->update($user);

        return $this;
    }

    /**
     * @param \Hk4w\Member\Entity\User|string $user
     * @return \Hk4w\Member\Entity\User
     */
    public function getFromLdap($user)
    {
        $ldap = new \Zend\Ldap\Ldap(array(
            'host'                  => 'srvprdldap',
            'accountDomainName'     => 'dps.prod.priv',
            'accountDomainNameShort'=> 'dps',
            'accountCanonicalForm'  => 3,
            'baseDn'                => 'DC=dps,DC=prod,DC=priv',
            'username'              => 'pupitre.trappes',
            'password'              => 'Pr0d@DPS7',
        ));

        $ldap->bind();
        $result = $ldap->search("(sAMAccountName=$user)");

        if (count($result) != 1) {
            return null;
        }

        return $this->getHydrator(self::HYDRATOR_LDAP)->hydrate($result->getFirst(), $user);
    }

    public function getUserByToken($token)
    {
        //  Get User
        if (null === ($user = $this->getRepository()->findOneBy(array('passwordResetToken' => $token)))) {
            return false;
        }

        return $user;
    }

    public function changePassword($userOrToken, $password, $oldPassword = null)
    {
        if ($userOrToken instanceof \Hk4w\Member\Entity\User) {
            if (!password_verify($oldPassword, $userOrToken->getPassword())) {
                $this->getLog()->info("Change User Password : Invalid old password not match for user '$userOrToken'");
                return self::PASSWORD_OLD_NOT_MATCH;
            }
            $user = $userOrToken;
        } elseif (is_string($userOrToken)) {
            if (empty($userOrToken)) {
                $this->getLog()->info("Change User Password : Token $userOrToken empty");
                return self::PASSWORD_TOKEN_EMPTY;
            } elseif (!($user = $this->getUserByToken($userOrToken)) instanceof \Hk4w\Member\Entity\User) {
                $this->getLog()->info("Change User Password : Token $userOrToken not found");
                return self::PASSWORD_TOKEN_NOT_FOUND;
            }
        } else {
            return self::PASSWORD_ERROR;
        }

        if (!$this->checkPasswordStrength($password)) {
            $this->getLog()->info("Change User Password : New password not strong enought");
            return self::PASSWORD_NOT_STRONG;
        }

        $passwordValidity = new \DateTime();
        $passwordValidity->add(new \DateInterval('P1Y'));
        $user->setPassword($this->hashPassword($password))
             ->setPasswordResetToken('')
             ->setPasswordValidity($passwordValidity);
        $this->update($user);
        $this->getLog()->info("Change User Password : Password modified for user '$user'");

        return $user;
    }

    public function hashPassword($password)
    {
        $this->getLog()->info("Hash of password");

        return password_hash($password, PASSWORD_BCRYPT, array(
            'cost' => PASSWORD_BCRYPT_DEFAULT_COST
        ));
    }

    public function checkPasswordStrength($password)
    {
        /* @var $passwordTool \Hk4w\Member\Tool\Password */
        $passwordTool = $this->getServiceLocator()->get('Member.Password.Tool');
        return $passwordTool->isStrong($password);
    }

    protected function getPasswordConfig()
    {
        $config = $this->getServiceLocator()->get('Config');
        if (!isset($config['member']['password'])) {
            throw array();
        }
        return $config['member']['password'];
    }

    public function notify($action, $user = null)
    {
        if (!$user instanceof \Hk4w\Member\Entity\User) {
            $user = $this->getServiceLocator()->get('Member.Identity');
        }
        $translate = $this->getServiceLocator()->get('ViewHelperManager')->get('Translate');
        $partial = $this->getServiceLocator()->get('ViewHelperManager')->get('Partial');

        $html = new \Zend\Mime\Part($partial('mail/registration', array('user' => $user)));
        $html->setType(\Zend\Mime\Mime::TYPE_HTML);

        $body = new \Zend\Mime\Message();
        $body->setParts(array($html));

        /* @var $message \Hk4w\Base\Mail\Message */
        $message = $this->getServiceLocator()->get('Mail');
        $message->addTo($user->getEmail())
                ->setSubject($translate('member_mail_registration__title'))
                ->setBody($body)
                ->setEncoding("UTF-8");

        $transport = new \Zend\Mail\Transport\Sendmail();
        $transport->send($message);

        return $user;
    }
}