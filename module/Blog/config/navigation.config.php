<?php
namespace Blog;

return array(
        'default' => array(
            'blog' => array(
                'label' => 'Create',
                'route' => 'blog',
                'controller' => 'post',
                'action' => 'index',
            ),
        ),
        'side' => array(
            'post' => array(
                'label' => 'Posts',
                //contorller
                'route' => 'blog',
                'controller' => 'post',
                'action' => 'index',
            ),
            'post_create' => array(
                'label' => 'New Post',
                //contorller
                'route' => 'blog',
                'controller' => 'post',
                'action' => 'create',
            ),
            'post_link' => array(
                'label' => 'Link Post',
                //contorller
                'route' => 'blog',
                'controller' => 'post',
                'action' => 'link',
            ),
            'category' => array(
                'divider' => 'above',
                'label' => 'Categories',
                //contorller
                'route' => 'blog',
                'controller' => 'category',
                'action' => 'index',
            ),
            'category_create' => array(
                'label' => 'New Category',
                //contorller
                'route' => 'blog',
                'controller' => 'category',
                'action' => 'create',
            ),
            'file' => array(
                'divider' => 'above',
                'header' => 'File Manager',
                'label' => 'Files',
                //contorller
                'route' => 'blog_file',
                'controller' => 'file',
                'action' => 'index',
            ),
            'file_upload' => array(
                'label' => 'Upload Files',
                //contorller
                'route' => 'blog_file',
                'controller' => 'file',
                'action' => 'upload',
            ),
            'media' => array(
                'divider' => 'above',
                'label' => 'Media Library',
                //contorller
                'route' => 'blog',
                'controller' => 'media',
                'action' => 'index',
            ),
            'media_upload' => array(
                'label' => 'Add New Media',
                //contorller
                'route' => 'blog',
                'controller' => 'media',
                'action' => 'upload',
            ),
        ),
);