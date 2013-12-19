<?php
namespace User;

return array(
    'controllers' => array(
        'invokables' => array(
            'auth' => 'User\Controller\AuthController',
            'profile' => 'User\Controller\ProfileController',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Authentication\AuthenticationService' => function ($sm) {
                return $sm->get('doctrine.authenticationservice.orm_default');
            },
        ),
    ),
    'router' => array(
        'routes' => array(
            'auth_login' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/login',
                    'defaults' => array(
                        'controller' => 'auth',
                        'action' => 'login',
                    ),
                ),
            ),
            'auth_register' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/register',
                    'defaults' => array(
                        'controller' => 'auth',
                        'action' => 'register',
                    ),
                ),
            ),
            'auth_logout' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'auth',
                        'action' => 'logout',
                    ),
                ),
            ),
            'profile_edit' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/profile/edit',
                    'defaults' => array(
                        'controller' => 'profile',
                        'action' => 'edit',
                    ),
                ),
            ),
            'profile_index' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/profile[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'profile',
                        'action' => 'index',
                    ),
                ),
            ),
            'profile_list' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/profiles',
                    'defaults' => array(
                        'controller' => 'profile',
                        'action' => 'list',
                    ),
                ),
            ),
        ),
    ),

    'navigation' => array(
        'default' => array(
            'profile_view' => array(
                'label' => 'My Profile',
                'route' => 'profile_index',
            ),
            'profile_list' => array(
                'label' => 'Profiles',
                'route' => 'profile_list',
            ),
            'auth_login' => array(
                'label' => 'Login',
                'route' => 'auth_login',
            ),
            'auth_logout' => array(
                'label' => 'Logout',
                'route' => 'auth_logout',
            ),
            'auth_register' => array(
                'label' => 'Register',
                'route' => 'auth_register',
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
        ),
    ),

   'doctrine' => array(
       'authentication' => array(
           'orm_default' => array(
               'object_manager' => 'Doctrine\ORM\EntityManager',
               'identity_class' => 'User\Entity\User',
               'identity_property' => 'email',
               'credential_property' => 'password',
               'credential_callable' => function (\User\Entity\User $user, $passwordGiven) {
                   return $user->isThisPassword($passwordGiven);
               },
           ),
       ),
       'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
);
