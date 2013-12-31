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
        'admin_index' => array(
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/admin[/]',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller' => 'admin',
                    'action' => 'index',
                ),
            ),
        ),
        'admin_edit' => array(
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/admin/edit[/:id]',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                    'id' => '[0-9]+',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller' => 'admin',
                    'action' => 'edit',
                ),
            ),
        ),
        'admin_delete' => array(
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/admin/delete/:id',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                    'id' => '[0-9]+',
                ),
                'defaults' => array(
                    'lang' => 'en',
                    'controller' => 'admin',
                    'action' => 'delete',
                ),
            ),
        ),
        'profile_edit' => array(
            'type' => 'segment',
            'options' => array(
                'route' => '[/:lang]/profile/edit[/:id]',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                    'id' => '[0-9]+',
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
                'route' => '[/:lang]/profile[/:uniquename]',
                'constraints' => array(
                    'lang' => '(?:en)|(?:es)|(?:fr)|(?:de)|(?:it)',
                    'uniquename' => '[A-Za-z0-9]+(?:[-_.]?[A-Za-z0-9]+){4,}',
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
