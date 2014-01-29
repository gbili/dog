<?php
namespace Dogtore;
return array(
    'routes' => array(

        'dogtore_index' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '/[:lang][/][:post_slug][/:related]',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'post_slug' => '[a-z0-9]+[a-z0-9-]+[a-z0-9]+',
                    'related' => '(?:children)|(?:parent)',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'scs',
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
                    'controller' => 'scs',
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
                    'controller' => 'scs',
                    'action'     => 'search',
                ),
            ),
            'may_terminate' => true,
        ),

        'dog_view_user_dog' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/:uniquename[/[:dogname]]',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'uniquename' => $preConfig['regex_patterns']['uniquename'],
                    'dogname' => $preConfig['regex_patterns']['dogname'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dog',
                    'action'        => 'view',
                ),
            ),
            'may_terminate' => true,
        ),

        'dog_user_dog_edit' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/:dogname/edit',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'dogname' => $preConfig['regex_patterns']['dogname'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dog',
                    'action'        => 'edit',
                ),
            ),
            'may_terminate' => true,
        ),

        'dog_add_my_dog' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/add',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'dogname' => $preConfig['regex_patterns']['dogname'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dog',
                    'action'        => 'add',
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
                    'controller'    => 'dog',
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
                    'controller'    => 'dog',
                    'action'        => 'listuserdogs',
                ),
            ),
            'may_terminate' => true,
        ),
    ),
);
