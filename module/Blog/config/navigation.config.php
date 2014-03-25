<?php
namespace Blog;

return array(
    'default' => array(
        'my_account' => array(
            'label' => 'My Account',
            'iconClass' => 'glyphicon glyphicon-home',
            'route' => 'profile_private',
            'action' => 'index',
        ),
    ),
    'side_2' => array(
        'post' => array(
            'label' => 'Posts',
            'route' => 'blog_post_route',
            'action' => 'index',
            'pages' => array(
                'post_create' => array(
                    'label' => 'New Post',
                    'route' => 'blog_post_route',
                    'iconClass' => 'glyphicon glyphicon-plus',
                    'action' => 'create',
                ),
            ),
        ),
        'category' => array(
            'divider' => 'above',
            'label' => 'Categories',
            'route' => 'blog_category_route',
            'action' => 'index',
            'pages' => array(
                'category_create' => array(
                    'label' => 'New Category',
                    'route' => 'blog_category_route',
                    'action' => 'create',
                ),
            ),
        ),
        'media' => array(
            'divider' => 'above',
            'label' => 'Medias',
            'route' => 'blog_media_route',
            'iconClass' => 'glyphicon glyphicon-picture',
            'action' => 'index',
            'pages' => array(
                'media_upload' => array(
                    'label' => 'Upload Media',
                    //contorller
                    'route' => 'blog_media_route',
                    'iconClass' => 'glyphicon glyphicon-upload',
                    'action' => 'upload',
                ),
            ),
        ),
    ),

    'side_3' => array(
        'file' => array(
            'divider' => 'above',
            'header' => 'File Manager',
            'label' => 'Files',
            'iconClass' => 'glyphicon glyphicon-file',
            //contorller
            'route' => 'blog_file_route',
            'action' => 'index',
            'pages' => array(
                'file_upload' => array(
                    'label' => 'Upload Files',
                    //contorller
                    'route' => 'blog_file_route',
                    'iconClass' => 'glyphicon glyphicon-upload',
                    'action' => 'upload',
                ),
            ),
        ),
    ),
);
