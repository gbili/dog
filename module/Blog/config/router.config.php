<?php
namespace Blog;
return array(
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
   );
