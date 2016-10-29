<?php

namespace Docapost\Base\Service;

class HeaderNavigationFactory extends \Zend\Navigation\Service\AbstractNavigationFactory
{
    public function getName()
    {
        return 'header';
    }
}