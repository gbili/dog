<?php
namespace Blog;
return array(
        'invokables' => array(
            'post_controller'     => 'Blog\Controller\PostController',
            'category_controller' => 'Blog\Controller\CategoryController',
            'media_controller'    => 'Blog\Controller\MediaController',
            'file_controller'     => 'Blog\Controller\FileController',
        ),
    );
