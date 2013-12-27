<?php
namespace Dogtore;

return array(
    'controllers' => array(
        'factories' => array(
            __NAMESPACE__ . '\Controller\DoggyController'   => __NAMESPACE__ . '\Service\DoggyControllerFactory',
        ),
        'aliases' => array(
            'doggy' => __NAMESPACE__ . '\Controller\DoggyController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            strtolower(__NAMESPACE__) => __DIR__ . '/../view',
        ),
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
        ),
    ),

    'view_helpers'    => include __DIR__ . '/view_helpers.config.php',
    'translator'      => include __DIR__ . '/translator.config.php',
    'service_manager' => include __DIR__ . '/service_manager.config.php',
    'router'          => include __DIR__ . '/router.config.php',
    'navigation'      => include __DIR__ . '/navigation.config.php',
);
