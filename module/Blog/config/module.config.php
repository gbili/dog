<?php
namespace Blog;

return array(
    'controllers' => array(
        'invokables' => array(
            'post' => 'Blog\Controller\PostController',
            'category' => 'Blog\Controller\CategoryController',
            'media' => 'Blog\Controller\MediaController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'blog' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/:controller[/:action][/:id]',
                    'constraints' => array(
                        'controller' => '(?:post)|(?:category)|(?:media)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'post',
                        'action' => 'index',
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
                        'header' => 'Editor',
                        'label' => 'Create',
                        //contorller
                        'route' => 'blog',
                        'controller' => 'post',
                        'action' => 'create',
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
                        'header' => 'Editor',
                        'label' => 'Create',
                        //contorller
                        'route' => 'blog',
                        'controller' => 'category',
                        'action' => 'create',
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

    /*'doctrine' => array(
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'Gedmo\Tree\TreeListener',
                ),
            ),
        ),
        'driver' => array(
            // overriding zfc-user-doctrine-orm's config
            '_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => '_entity',
                ),
            ),
        ),
    ),*/

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
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);
