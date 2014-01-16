<?php
namespace User;
return array(
    'controllers' => array(
        'invokables' => array(
            'auth' => 'User\Controller\AuthController',
            'profile' => 'User\Controller\ProfileController',
            'admin' => 'User\Controller\AdminController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
        ),
    ),

    'controller_plugins' => include __DIR__ . '/controller_plugins.config.php',
    'doctrine'           => include __DIR__ . '/doctrine.config.php',
    'navigation'         => include __DIR__ . '/navigation.config.php', 
    'router'             => include __DIR__ . '/router.config.php',
    'service_manager'    => include __DIR__ . '/service_manager.config.php',
    'translator'         => include __DIR__ . '/translator.config.php',
);
