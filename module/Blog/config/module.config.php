<?php
namespace Blog;

return array(
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
                'text_domain' => strtolower(__NAMESPACE__),
            ),
        ),
    ),
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
                    'route' => '[/:lang]/:controller[/:action][/:id][/:fourthparam]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'controller' => '(?:post)|(?:category)|(?:media)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'fourthparam' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'post',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog_media_view' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/media/view[/:slug]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'slug' => '[a-zA-Z0-9_-]+\\.?[a-z]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'media',
                        'action' => 'view',
                    ),
                ),
            ),
            'blog_file' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/file[/:action][/:id][/:fourthparam]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'fourthparam' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'file',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'side_navigation' => 'Blog\Navigation\Service\SideNavigationFactory',
        ),
    ),

    'navigation' => array(
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
