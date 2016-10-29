<?php

namespace Docapost\Base\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class Identity extends \Zend\View\Helper\AbstractHelper implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __invoke()
    {
        return $this->render();
    }

    public function render()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('Identity');
    }
}