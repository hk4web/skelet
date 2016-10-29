<?php

namespace Docapost\Base\Service;

use Docapost\Base\Exception\Exception;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

abstract class AbstractService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @return Logger
     */
    protected function getLog()
    {
        if (!$this->getServiceLocator()->has('Log.Application')) {
            throw new Exception("No 'Log.Application' found");
        }

        return $this->getServiceLocator()->get('Log.Application');
    }
}