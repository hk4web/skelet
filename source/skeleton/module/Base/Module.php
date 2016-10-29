<?php

namespace Base;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/Base',
                ),
            ),
        );
    }

    public function getConfig()
    {
        return array_merge_recursive(
            include __DIR__ . '/config/module.config.php',
            include __DIR__ . '/config/navigation.config.php',
            include __DIR__ . '/config/router.config.php',
            include __DIR__ . '/config/service.config.php',
            include __DIR__ . '/config/view.config.php'
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $config = $e->getApplication()->getServiceManager()->get('Config');

        //  Init PHP Settings
        if (isset($config['phpSettings']) && is_array($config['phpSettings'])) {
            foreach ($config['phpSettings'] as $option => $value) {
                ini_set($option, $value);
            }
        }

        //  Init Bootstrap Services
        if (isset($config['bootstrap']) && is_array($config['bootstrap'])) {
            foreach ($config['bootstrap'] as $service => $enable) {
                if ($enable) {
                    $e->getApplication()->getServiceManager()->get($service)->init($e);
                }
            }
        }
    }
}