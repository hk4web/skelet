<?php

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Base\Controller\Index',
                        'action' => 'index',
                        'resource' => 'default',
                        'privilege' => 'read',
                        'redirect' => 'login',
                    ),
                ),
            ),
        ),
    ),
);