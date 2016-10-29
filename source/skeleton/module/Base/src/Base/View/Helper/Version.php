<?php

namespace Docapost\Base\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\View\Helper\AbstractHelper;

class Version extends AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __invoke()
    {
        if ($this->getServiceLocator()->getServiceLocator()->has('Version')) {
            return $this->getServiceLocator()->getServiceLocator()->get('Version');
        }

        if ($this->getServiceLocator()->getServiceLocator()->has('Config')) {
            $config = $this->getServiceLocator()->getServiceLocator()->get('Config');
            if (isset($config['application']['version']) && $config['application']['version']) {
                return $config['application']['version'];
            }
        }

        return '0.0.0';
    }
}