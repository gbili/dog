<?php
namespace Blog;
return array(
    'invokables' => array(
        'blog_post_controller'     => 'Blog\Controller\PostController',
        'blog_category_controller' => 'Blog\Controller\CategoryController',
        'blog_media_controller'    => 'Blog\Controller\MediaController',
        'blog_file_controller'     => 'Blog\Controller\FileController',
    ),
);
