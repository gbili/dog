<?php
namespace GbiliUser;

return array(
    'navigation' => array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => array(
            // And finally, here is where we define our page hierarchy
            'zfcuser' => array(
                'label' => 'User',
                'route' => 'zfcuser',
                'pages' => array(
                    'register' => array(
                        'label' => 'Regiser',
                        'route' => 'zfcuser/register',
                        'action' => 'register',
                    ),
                    'login' => array(
                        'divider' => 'above',
                        'header' => 'Members',
                        'label' => 'Login',
                        //contorller
                        'route' => 'zfcuser/login',
                        //action
                        'action' => 'login',
                    ),
                    'logout' => array(
                        'label' => 'Logout',
                        //contorller
                        'route' => 'zfcuser/logout',
                        //action
                        'action' => 'logout',
                    ),
                ),
            ),
        ),
    ),
    
    'doctrine' => array(
        'driver' => array(
            // overriding zfc-user-doctrine-orm's config
            'zfcuser_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
            ),

            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => 'zfcuser_entity',
                ),
            ),
        ),
    ),

    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class'       => __NAMESPACE__ . '\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),

    'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entity_manager.orm_default',
                'role_entity_class' => __NAMESPACE__ . '\Entity\Role',
            ),
        ),
    ),
);
