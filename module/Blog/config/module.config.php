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

    'translator' => include __DIR__ . '/translator.config.php',
    'router' => include __DIR__ . '/router.config.php',
    'navigation' => include __DIR__ . '/navigation.config.php',
    'doctrine' => include __DIR__ . '/doctrine.config.php',
    'service_manager' => include __DIR__ . '/service_manager.config.php',
);
