<?php
namespace Blog;
return array(
        'routes' => array(
            'blog_post' => array(
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
                        'controller' => 'post_controller',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog_category' => array(
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
                        'controller' => 'category_controller',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog_media' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/media[/:action][/:id][/:fourthparam]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'fourthparam' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'media_controller',
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
                        'controller' => 'media_controller',
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
                        'controller' => 'file_controller',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
   );
