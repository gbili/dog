<?php
namespace Blog;

return array(
    'controllers' => array(
        'invokables' => array(
            'post' => 'Blog\Controller\PostController',
            'category' => 'Blog\Controller\CategoryController',
            'media' => 'Blog\Controller\MediaController',
            'file' => 'Blog\Controller\FileController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'blog' => __DIR__ . '/../view',
        ),
    ),

    'controller_plugins' => include __DIR__ . '/controller_plugins.config.php',
    'doctrine'           => include __DIR__ . '/doctrine.config.php',
    'navigation'         => include __DIR__ . '/navigation.config.php',
    'router'             => include __DIR__ . '/router.config.php',
    'service_manager'    => include __DIR__ . '/service_manager.config.php',
    'translator'         => include __DIR__ . '/translator.config.php',
    'view_helpers'       => include __DIR__ . '/view_helpers.config.php',
);
