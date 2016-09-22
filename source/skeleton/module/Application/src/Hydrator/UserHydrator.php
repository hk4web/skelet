<?php

namespace Hk4w\Member\Hydrator;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Hydrator\ClassMethods;

class UserHydrator extends ClassMethods implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function extract($user)
    {
        $data = parent::extract($user);

        //  Group
        if (isset($data['group']) && $data['group'] instanceof \Hk4w\Member\Entity\Group) {
            $data['group'] = $data['group']->getId();
        }

        //  Site
        if (isset($data['site']) && $data['site'] instanceof \Hk4w\Member\Entity\Site) {
            $data['site'] = $data['site']->getId();
        }
        
        return $data;
    }

    public function hydrate(array $data, $user)
    {
        //  Group
        if (isset($data['group']) && $data['group']) {
            /* @var $groupService \Hk4w\Member\Service\GroupService */
            $groupService = $this->getServiceLocator()->get('Member.Group.Service');
            $data['group'] = $groupService->getRepository()->find($data['group']);
        } else {
            $data['group'] = null;
        }

        //  Site
        if (isset($data['site']) && $data['site']) {
            /* @var $siteService \Hk4w\Member\Service\SiteService */
            $siteService  = $this->getServiceLocator()->get('Member.Site.Service');
            $data['site'] = $siteService->getRepository()->find($data['site']);
        }

        return parent::hydrate($data, $user);
    }
}