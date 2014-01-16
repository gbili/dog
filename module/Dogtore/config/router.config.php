<?php
namespace Dogtore;
return array(
    'routes' => array(
        'dogtore_index' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '/[:lang][/][:post_slug][/:related]',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                    'post_slug' => '[a-z0-9]+[a-z0-9-]+[a-z0-9]+',
                    'related' => '(?:children)|(?:parent)',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'doggy',
                    'action'        => 'index',
                ),
            ),
            'may_terminate' => true,
        ),
        'dogtore_search_handy' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang][/get-me-:category{-}[-talking-about-:terms]]',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                    'category' => '(?:(?:symptom)|(?:cause)|(?:solution))s?',
                    'terms' => '[a-zA-Z0-9]+[a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    'lang'       => 'en',
                    'controller' => 'doggy',
                    'action'     => 'search',
                ),
            ),
            'may_terminate' => true,
        ),
        'dogtore_search' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/search',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                ),
                'defaults' => array(
                    'lang'       => 'en',
                    'controller' => 'doggy',
                    'action'     => 'search',
                ),
            ),
            'may_terminate' => true,
        ),
        'dogtore_view' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/view[-:slug{-}][-:category]',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                    'category' => '(?:symptom)|(?:cause)|(?:solution)',
                    'slug' => '[a-zA-Z0-9-_]+',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'doggy',
                    'action'        => 'view',
                ),
            ),
            'may_terminate' => true,
        ),
    ),
);
