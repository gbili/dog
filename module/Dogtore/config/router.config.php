<?php
namespace Dogtore;
return array(
    'routes' => array(
        'dogtore_index' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '/[:lang/][:post_slug/][:related/]',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'post_slug' => '[a-z0-9]+[a-z0-9-]+[a-z0-9]+',
                    'related' => '(?:children)|(?:parent)',
                ),
                'defaults' => array(
                    'lang'          => 'en',
                    'controller'    => 'dogtore_scs_controller',
                    'action'        => 'index',
                ),
            ),
            'may_terminate' => true,
        ),

        /*'dogtore_search_handy' => array(
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

        'dogtore_search' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/search',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                ),
                'defaults' => array(
                    'lang'       => 'en',
                    'controller' => 'dogtore_scs_controller',
                    'action'     => 'search',
                ),
            ),
            'may_terminate' => true,
        ),

        'dog_view_user_dog' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/:uniquename[/[:dogname_underscored]]',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'uniquename' => $preConfig['regex_patterns']['uniquename'],
                    'dogname_underscored' => $preConfig['regex_patterns']['dogname_underscored'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dogtore_dog_controller',
                    'action'        => 'view',
                ),
            ),
            'may_terminate' => true,
        ),

        'dog_user_dog_edit' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/:dogname_underscored/edit',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'dogname_underscored' => $preConfig['regex_patterns']['dogname_underscored'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dogtore_dog_controller',
                    'action'        => 'edit',
                ),
            ),
            'may_terminate' => true,
        ),

        'dogtore_dog_add_route' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/add',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dogtore_dog_controller',
                    'action'        => 'add',
                ),
            ),
            'may_terminate' => true,
        ),

        'dogtore_dog_upload_route' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/upload',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dogtore_dog_controller',
                    'action'        => 'upload',
                ),
            ),
            'may_terminate' => true,
        ),

        'dog_list_my_dogs' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dogs[/]',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dogtore_dog_controller',
                    'action'        => 'listmydogs',
                ),
            ),
            'may_terminate' => true,
        ),

        'dog_list_user_dogs' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dogs/:uniquename',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'uniquename' => $preConfig['regex_patterns']['uniquename'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dogtore_dog_controller',
                    'action'        => 'listuserdogs',
                ),
            ),
            'may_terminate' => true,
        ),
    ),
);
