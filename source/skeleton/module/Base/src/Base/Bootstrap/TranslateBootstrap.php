<?php

namespace Base\Bootstrap;

use Locale;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class TranslateBootstrap implements BootstrapInterface
{
    public function init(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        //  Locale
        $config = $event->getApplication()->getServiceManager()->get('Config');
        if (isset($config['translator']['locale'])) {
            Locale::setDefault($config['translator']['locale']);
        }

        //  Translator
        if ($serviceManager->has('Translator')) {
            AbstractValidator::setDefaultTranslator($serviceManager->get('Translator'));
        }
    }
}