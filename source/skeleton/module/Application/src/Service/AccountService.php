<?php

namespace Hk4w\Member\Service;

use Hk4w\Base\Service\EntityService;
use Hk4w\Member\Exception\Exception;

class AccountService extends EntityService
{
    const FORM_PASSWORD = 'password';
    const FORM_PROFILE  = 'profile';

    protected $identity = null;

    public function getUserService()
    {
        return $this->getServiceLocator()->get('Member.User.Service');
    }

    public function getIdentity()
    {
        if (null === $this->identity) {
            $this->identity = $this->getServiceLocator()->get('Member.Identity');
        }

        return $this->identity;
    }

    public function getForm($type = self::FORM_DEFAULT)
    {
        $form = parent::getForm($type);
        if (!$form->hasValidated()) {
            $form->bind($this->getIdentity());
        }

        return $form;
    }

    public function create()
    {
        throw new Exception("Function " . __FUNCTION__ . " not available for " . __CLASS__);
    }

    public function update($user = null)
    {
        return $this->getUserService()->update($user);
    }

    public function delete()
    {
        throw new Exception("Function " . __FUNCTION__ . " not available for " . __CLASS__);
    }
}