<?php
namespace Dogtore;

return array(
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

    'controllers'     => include __DIR__ . '/controllers.config.php',
    'doctrine'        => include __DIR__ . '/doctrine.config.php',
    'gbiliaclguard'   => include __DIR__ . '/gbiliaclguard.config.php',
    'file_uploader'   => include __DIR__ . '/file_uploader.config.php',
    'navigation'      => include __DIR__ . '/navigation.config.php',
    'router'          => include __DIR__ . '/router.config.php',
    'service_manager' => include __DIR__ . '/service_manager.config.php',
    'translator'      => include __DIR__ . '/translator.config.php',
    'view_helpers'    => include __DIR__ . '/view_helpers.config.php',
);
