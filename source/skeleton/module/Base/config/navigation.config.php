<?php

return array(
    'navigation' => array(
        'header' => array(
        ),
        'default' => array(
            //  Home
            'home' => array(
                'label' => 'navigation__home',
                'title' => 'page_title__home',
                'route' => 'home',
                'visible' => false,
                'order' => 0,
            ),
            //  Administration
            'admin' => array(
                'label' => 'navigation__admin',
                'title' => 'page_title__admin',
                'uri' => '',
                'visible' => false,
                'order' => 900,
                'pages' => array(
                    'other' => array(
                        'label' => 'navigation__admin_other',
                        'title' => 'page_title__admin_other',
                        'uri' => '',
                        'visible' => false,
                        'order' => 900,
                    ),
                ),
            ),
        ),
    ),
);