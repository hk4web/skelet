<?php

namespace Hk4w\Member\Hydrator\Auth;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Hydrator\ClassMethods;

class ForgotPasswordHydrator extends ClassMethods implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function extract($object)
    {
        $data = parent::extract($object);

        \Zend\Debug\Debug::dump($data);exit;

        return isset($data['identity']) ? $data['identity'] : '';
    }
}