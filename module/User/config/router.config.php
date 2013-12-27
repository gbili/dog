<?php
namespace User;
return array(
    'routes' => array(
        'auth_login' => array(
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/login',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller' => 'auth',
                    'action' => 'login',
                ),
            ),
        ),
        'auth_register' => array(
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/register',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller' => 'auth',
                    'action' => 'register',
                ),
            ),
        ),
        'auth_logout' => array(
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/logout',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller' => 'auth',
                    'action' => 'logout',
                ),
            ),
        ),
        'profile_edit' => array(
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/profile/edit',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller' => 'profile',
                    'action' => 'edit',
                ),
            ),
        ),
        'profile_index' => array(
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/profile[/:id]',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                    'id' => '[0-9]+',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller' => 'profile',
                    'action' => 'index',
                ),
            ),
        ),
        'profile_list' => array(
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/profiles',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller' => 'profile',
                    'action' => 'list',
                ),
            ),
        ),
    ),
);
