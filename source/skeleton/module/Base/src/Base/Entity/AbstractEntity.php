<?php

namespace Docapost\Base\Entity;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

abstract class AbstractEntity implements ServiceLocatorAwareInterface
{
    use CreateUpdateTrait;
    use ServiceLocatorAwareTrait;
}