<?php
namespace Blog;
return array(
        'routes' => array(
            'blog_post_route' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/post[/:action][/:id][/:fourthparam]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'fourthparam' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_post_controller',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog_category_route' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/category[/:action][/:id][/:fourthparam]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'fourthparam' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_category_controller',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog_media_route' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/media[/:action[/:id[/:fourthparam]]]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'fourthparam' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_media_controller',
                        'action' => 'index',
                    ),
                ),
            ),

            'blog_media_delete_route' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/media/delete/:id/:nonce',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'nonce' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_media_controller',
                        'action' => 'delete',
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
                        'controller' => 'blog_media_controller',
                        'action' => 'view',
                    ),
                ),
            ),
            'blog_file_route' => array(
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
                        'controller' => 'blog_file_controller',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
   );
