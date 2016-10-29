<?php

namespace Base\Bootstrap;

use Zend\Mvc\MvcEvent;

interface BootstrapInterface
{
    public function init(MvcEvent $event);
}