<?php
namespace Blog;
return array(
        'invokables' => array(
            'blog_post_controller'     => 'Blog\Controller\PostController',
            'blog_category_controller' => 'Blog\Controller\CategoryController',
        ),
        'factories' => array(
            'blog_media_controller' => 'Upload\Controller\ConfigKeyAwareControllerFactory',
            'blog_file_controller'  => 'Upload\Controller\ConfigKeyAwareControllerFactory',
        ),
    );
