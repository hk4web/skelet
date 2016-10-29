<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Base\Controller\Index' => 'Docapost\Base\Controller\IndexController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'flashMessenger' => 'Docapost\Base\Controller\Plugin\FlashMessenger',
            'view' => 'Docapost\Base\Controller\Plugin\View',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'form' => 'Docapost\Base\View\Helper\Form',
            'identity' => 'Docapost\Base\View\Helper\Identity',
            'translate' => 'Docapost\Base\View\Helper\Translate',
            'version' => 'Docapost\Base\View\Helper\Version',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'error/404'             => __DIR__ . '/../view/error/404.phtml',
            'error/index'           => __DIR__ . '/../view/error/index.phtml',
            'layout/layout'         => __DIR__ . '/../view/layout/layout.phtml',
            'layout/modal'          => __DIR__ . '/../view/layout/modal.phtml',
            'partial/header'        => __DIR__ . '/../view/layout/partial/header.phtml',
            'partial/header-form'   => __DIR__ . '/../view/layout/partial/header-form.phtml',
            'partial/nav-menu'      => __DIR__ . '/../view/layout/partial/navigation-menu.phtml',
            'partial/nav-submenu'   => __DIR__ . '/../view/layout/partial/navigation-submenu.phtml',
            'partial/footer'        => __DIR__ . '/../view/layout/partial/footer.phtml',
            'partial/message'       => __DIR__ . '/../view/layout/partial/message.phtml',
            'modal/header'          => __DIR__ . '/../view/layout/modal/header.phtml',
            'modal/footer'          => __DIR__ . '/../view/layout/modal/footer.phtml',
            'modal/button'          => __DIR__ . '/../view/layout/modal/button.phtml',
        ),
        'controller_map' => array(
            'Docapost\Base' => true,
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);