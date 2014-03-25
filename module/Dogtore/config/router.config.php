<?php
namespace Dogtore;
return array(
    'routes' => array(
        'dog_view_user_dog' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/:uniquename/:dogname_underscored',
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


        'dog_user_dog_edit' => array( //user logged in
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/edit/:dogname_underscored',
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

        'dogtore_dog_noncedelete_route' => array( //using nonce
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/dog/delete/:id/:nonce',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'id' => '[0-9]+',
                    'nonce' => '[a-zA-Z0-9_-]+',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dogtore_dog_controller',
                    'action'        => 'noncedelete',
                ),
            ),
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

        'dogtore_dog_upload_my_dog_medias_route' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/uploadmedias/:dogname_underscored/',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'dogname_underscored' => $preConfig['regex_patterns']['dogname_underscored'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dogtore_dog_controller',
                    'action'        => 'uploadmydogmedias',
                ),
            ),
            'may_terminate' => true,
        ),

        'dog_view_my_dog' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/view/:dogname_underscored',
                'constraints' => array(
                    'lang' => $preConfig['regex_patterns']['lang'],
                    'dogname_underscored' => $preConfig['regex_patterns']['dogname_underscored'],
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller'    => 'dogtore_dog_controller',
                    'action'        => 'viewmydog',
                ),
            ),
            'may_terminate' => true,
        ),

        'dog_list_my_dogs' => array(
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => array(
                'route'    => '[/:lang]/dog/list[/]',
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
