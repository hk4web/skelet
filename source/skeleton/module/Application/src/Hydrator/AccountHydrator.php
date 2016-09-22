<?php

namespace Hk4w\Member\Hydrator;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Hydrator\ClassMethods;

class AccountHydrator extends ClassMethods implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function extract($user)
    {
        $data = parent::extract($user);

        //  Group
        if (isset($data['group']) && $data['group'] instanceof \Hk4w\Member\Entity\Group) {
            $data['group'] = $data['group']->getId();
        }

        return $data;
    }

    public function hydrate(array $data, $user)
    {
        //  Group
        if (isset($data['group']) && $data['group']) {
            /* @var $groupService \Hk4w\Member\Service\GroupService */
            $groupService  = $this->getServiceLocator()->get('Member.Group.Service');
            $data['group'] = $groupService->getRepository()->find($data['group']);
        }

        return parent::hydrate($data, $user);
    }
}