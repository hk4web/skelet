<?php

namespace Docapost\Base\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function indexAuthAction()
    {
        \Zend\Debug\Debug::dump($this->getServiceLocator()->get('User.Current'));exit;
        return new ViewModel();
    }

    public function indexAclAction()
    {
        $result = array();
        foreach ($this->getServiceLocator()->get('Acl.Role.Repository')->findAll() as $role) {
            foreach ($this->getServiceLocator()->get('Acl.Resource.Repository')->findAll() as $resource) {
                $result[$role->getRoleId()][$resource->getResourceId()] = $this->getServiceLocator()->get('Acl')->isAllowed($role->getRoleId(), $resource->getResourceId());
            }
        }

        \Zend\Debug\Debug::dump($result);
        return new ViewModel();
    }
}