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
            'auth' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/auth[/:action][/:id][/:fourthparam]',
                    'constraints' => array(
                        'action' => '(?:login)|(?:logout)|(?:register)',
                        //some9_person.99-at-some-domain.tld
                        'id' => '[a-z0-9_\\.]+-at-(?:[a-z0-9]+-?[a-z0-9]+)+\\.[a-z]',
                        'fourthparam' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'auth',
                        'action' => 'login',
                    ),
                ),
            ),
            'profile' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/profile[/:action][/:id][/:fourthparam]',
                    'constraints' => array(
                        'action' => '(?:edit)',
                        //some9_person.99-at-some-domain.tld
                        'id' => '[a-z0-9_\\.]+-at-(?:[a-z0-9]+-?[a-z0-9]+)+\\.[a-z]',
                        'fourthparam' => '[a-zA-Z0-9_-]+',
                    ),
                    'defaults' => array(
                        'controller' => 'profile',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),

    'navigation' => array(
        'default' => array(
            'auth' => array(
                'label' => 'User',
                'route' => 'auth',
                'pages' => array(
                    'register' => array(
                        'label' => 'Register',
                        'route' => 'auth',
                        'action' => 'register',
                    ),
                    'login' => array(
                        'label' => 'Login',
                        'route' => 'auth',
                        'action' => 'login',
                    ),
                    'logout' => array(
                        'label' => 'Logout',
                        'route' => 'auth',
                        'action' => 'logout',
                    ),
                ),
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
