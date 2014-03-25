<?php
namespace Scs;
return array(
    'routes' => array(
        'scs_scs_index_route' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '/[:lang/]',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                ),
                'defaults' => array(
                    'lang'          => 'en',
                    'controller'    => 'scs_scs_controller',
                    'action'        => 'index',
                ),
            ),
            'may_terminate' => true,
        ),

        'scs_scs_related_route' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '/[:lang/][:related/]of/[:post_slug/]',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'post_slug' => '[a-z0-9]+[a-z0-9-]+[a-z0-9]+',
                    'related' => '(?:children)|(?:parent)',
                ),
                'defaults' => array(
                    'lang'          => 'en',
                    'controller'    => 'scs_scs_controller',
                    'action'        => 'related',
                ),
            ),
            'may_terminate' => true,
        ),

        'scs_scs_ajax_search_posts_relatable_to_categorized_post_as_children_route' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '/[:lang/]scs/asprtcpac/[:category_slug/]', //first letters
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'category_slug' => '(?:symptom)|(?:cause)|(?:solution)',
                ),
                'defaults' => array(
                    'lang'          => 'en',
                    'controller'    => 'scs_scs_controller',
                    'action'        => 'ajaxSearchPostsAllowedToBeRelatedToCategorizedPostAsChildren',
                ),
            ),
            'may_terminate' => true,
        ),

        'scs_scs_ajax_search_posts_relatable_to_categorized_post_as_parent_route' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '/[:lang/]scs/asprtcpap/[:category_slug/]', //first letters
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'category_slug' => '(?:symptom)|(?:cause)|(?:solution)',
                ),
                'defaults' => array(
                    'lang'          => 'en',
                    'controller'    => 'scs_scs_controller',
                    'action'        => 'ajaxSearchPostsAllowedToBeRelatedToCategorizedPostAsParent',
                ),
            ),
            'may_terminate' => true,
        ),

        /*'scs_scs_search_handy' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang][/get-me-:category{-}[-talking-about-:terms]]',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'category' => $preConfig['regex_patterns']['category'],
                    'terms' => '[a-zA-Z0-9]+[a-zA-Z0-9_-]*',
                ),
                'defaults' => array(
                    'lang'       => 'en',
                    'controller' => 'dogtore_scs_controller',
                    'action'     => 'search',
                ),
            ),
            'may_terminate' => true,
        ),*/

        'scs_scs_search_route' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/search',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                ),
                'defaults' => array(
                    'lang'       => 'en',
                    'controller' => 'scs_scs_controller',
                    'action'     => 'search',
                ),
            ),
            'may_terminate' => true,
        ),
    ),
);
