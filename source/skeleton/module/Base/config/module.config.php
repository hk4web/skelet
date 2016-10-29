<?php

return array(
    'application' => array(
        'files' => array(
            'css' => array(
                'bootstrap' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',
                'font-awesome' => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
                'base' => '/css/base.css',
            ),
            'js' => array(
                'jquery' => 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js',
                'bootstrap' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js',
                'html5shiv' => 'https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js',
                'respond' => 'https://oss.maxcdn.com/respond/1.4.2/respond.min.js',
                'base' => '/js/base.js',
            ),
        ),
    ),
    'bootstrap' => array(
        'Bootstrap.View' => true,
        'Bootstrap.Navigation' => true,
        'Bootstrap.Translate' => true,
        'Bootstrap.Exception.Logger' => true,
    ),
    'doctrine' => array(
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'Gedmo\Timestampable\TimestampableListener',
                    'Gedmo\SoftDeleteable\SoftDeleteableListener',
                ),
            ),
        ),
    ),
    'translator' => array(
        'locale' => 'fr_FR',
        'translation_file_patterns' => array(
            array(
                'type' => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.php',
            ),
        ),
    ),
);