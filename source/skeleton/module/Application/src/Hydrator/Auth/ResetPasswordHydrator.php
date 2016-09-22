<?php

namespace Hk4w\Member\Hydrator\Auth;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Hydrator\ClassMethods;

class ResetPasswordHydrator extends ClassMethods implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function extract($object)
    {
        $data = parent::extract($object);
        \Zend\Debug\Debug::dump($data);

        return isset($data['password']) ? $data['password'] : '';
    }
}