<?php
namespace Blog;
return array(
        'invokables' => array(
            'post' => 'Blog\Controller\PostController',
            'category' => 'Blog\Controller\CategoryController',
            'media' => 'Blog\Controller\MediaController',
            'file' => 'Blog\Controller\FileController',
        ),
    );
