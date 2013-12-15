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
    'router' => array(
        'routes' => array(
            'blog' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/:controller[/:action][/:id][/:fourthparam]',
                    'constraints' => array(
                        'controller' => '(?:file)|(?:post)|(?:category)|(?:media)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'fourthparam' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'post',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog_media_view' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/media/view[/:slug]',
                    'constraints' => array(
                        'slug' => '[a-zA-Z0-9_-]+\\.?[a-z]+',
                    ),
                    'defaults' => array(
                        'controller' => 'media',
                        'action' => 'view',
                    ),
                ),
            ),
        ),
    ),

    'navigation' => array(
        'default' => array(
            'blog' => array(
                'label' => 'Blogging',
                'route' => 'blog',
                'pages' => array(
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
                        'route' => 'blog',
                        'controller' => 'file',
                        'action' => 'index',
                    ),
                    'file_upload' => array(
                        'label' => 'Upload Files',
                        //contorller
                        'route' => 'blog',
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
                    'media_create' => array(
                        'label' => 'New Media',
                        //contorller
                        'route' => 'blog',
                        'controller' => 'media',
                        'action' => 'create',
                    ),
                    'media_edit' => array(
                        'label' => 'Edit',
                        //contorller
                        'route' => 'blog',
                        'controller' => 'media',
                        'action' => 'edit',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'blog' => __DIR__ . '/../view',
        ),
    ),

   'doctrine' => array(
       'eventmanager' => array(
           'orm_default' => array(
               'subscribers' => array(
                   'Gedmo\Tree\TreeListener',
               ),
           ),
       ),
       'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
);
