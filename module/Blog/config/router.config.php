<?php
namespace Blog;
return array(
        'routes' => array(
            'blog_post_route' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/post[/:action][/:id][/:nonce]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'nonce' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_post_controller',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog_post_delete_route' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/post/delete/:id/:nonce',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'nonce' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_post_controller',
                        'action' => 'noncedelete',
                    ),
                ),
            ),
            'blog_category_route' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/category[/:action][/:id][/:nonce]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'nonce' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_category_controller',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog_category_delete_route' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/category/delete/:id/:nonce',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'nonce' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_category_controller',
                        'action' => 'noncedelete',
                    ),
                ),
            ),
            'blog_media_route' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/media[/:action[/:id[/:post_id]]]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'nonce' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_media_controller',
                        'action' => 'index',
                    ),
                ),
            ),

            'blog_media_delete_route' => array( //using nonce
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
                        'action' => 'noncedelete',
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
                    'route' => '[/:lang]/file[/:action][/:id][/:nonce]',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'nonce' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_file_controller',
                        'action' => 'index',
                    ),
                ),
            ),
            'blog_file_delete_route' => array( //delete using nonce
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:lang]/file/delete/:id/:nonce',
                    'constraints' => array(
                        'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                        'id' => '[0-9]+',
                        'nonce' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'lang' => 'en',
                        'controller' => 'blog_file_controller',
                        'action' => 'noncedelete',
                    ),
                ),
            ),
        ),
   );
