<?php
namespace Blog;

return array(
    'default' => array(
        'my_account' => array(
            'label' => 'My Account',
            'route' => 'blog_post',
            'action' => 'index',
        ),
    ),
    'side_2' => array(
        'post' => array(
            'label' => 'Posts',
            'route' => 'blog_post',
            'action' => 'index',
        ),
        'post_create' => array(
            'label' => 'New Post',
            'route' => 'blog_post',
            'action' => 'create',
        ),
        'category' => array(
            'divider' => 'above',
            'label' => 'Categories',
            'route' => 'blog_category',
            'action' => 'index',
        ),
        'category_create' => array(
            'label' => 'New Category',
            'route' => 'blog_category',
            'action' => 'create',
        ),
        'file' => array(
            'divider' => 'above',
            'header' => 'File Manager',
            'label' => 'Files',
            //contorller
            'route' => 'blog_file',
            'action' => 'index',
        ),
        'file_upload' => array(
            'label' => 'Upload Files',
            //contorller
            'route' => 'blog_file',
            'action' => 'upload',
        ),
        'media' => array(
            'divider' => 'above',
            'label' => 'Media Library',
            'route' => 'blog_media',
            'action' => 'index',
        ),
        'media_upload' => array(
            'label' => 'Add New Media',
            //contorller
            'route' => 'blog_media',
            'action' => 'upload',
        ),
    ),
);
