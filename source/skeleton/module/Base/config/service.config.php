<?php

use Zend\ServiceManager\ServiceManager;

return array(
    'service_manager' => array(
        'aliases' => array(),
        'abstract_factories' => array(
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            //  Generic
            /*'Mail' => function (ServiceManager $sm) {
                $mail = new \Docapost\Base\Mail\Message();
                $mail->setServiceLocator($sm)
                     ->init();
                return $mail;
            },*/
            'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
//            'Navigation.Header' => 'Docapost\Base\Service\HeaderNavigationFactory',
            'Translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'Session' => 'Zend\Session\Service\SessionManagerFactory',
        ),
        'invokables' => array(
            //  Bootstrap
            'Bootstrap.View' => 'Base\Bootstrap\ViewBootstrap',
            'Bootstrap.Navigation' => 'Base\Bootstrap\NavigationBootstrap',
            'Bootstrap.Translate' => 'Base\Bootstrap\TranslateBootstrap',
            'Bootstrap.Exception.Logger' => 'Base\Bootstrap\ExceptionLoggerBootstrap',
            //  Tool
            'Tool.Exception' => 'Base\Tool\Exception',
        ),
        'shared' => array(
            'Mail' => false,
        ),
        'initializers' => array(
            function($instance, Zend\ServiceManager\ServiceLocatorInterface $sm) {
                //  ServiceLocator
//                Zend_Debug::dump($sm);die;
                if ($instance instanceof \Zend\ServiceManager\ServiceLocatorAwareInterface) {
                    $instance->setServiceLocator($sm);
                }
            },
        ),
    ),
    'form_elements' => array(
        'invokables' => array(),
    ),
);