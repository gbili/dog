<?php
namespace Dogtore;

return array(
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Controller\\' . __NAMESPACE__ => __NAMESPACE__ . '\Controller\\' . __NAMESPACE__ . 'Controller',
        ),
        'factories' => array(
            __NAMESPACE__ . '\Controller\EditorController' => __NAMESPACE__ . '\Service\EditorControllerFactory',
            __NAMESPACE__ . '\Controller\ListController'   => __NAMESPACE__ . '\Service\ListControllerFactory',
        ),
        'aliases' => array(
            'editor' => __NAMESPACE__ . '\Controller\EditorController',
            'list' => __NAMESPACE__ . '\Controller\ListController',
        ),
    ),

    // The mapping of a URL to a particular action is done using routes that
    // are defined in the module's module.config.php file.
    // Before adding this, it showed a 404 not found
    'router' => array(
        'routes' => array(
            'list' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/[:action]',
                    'constraints' => array(
                        'action' => '(?:symptoms)|(?:causes)|(?:solutions)',
                    ),
                    'defaults' => array(
                        'controller'    => 'list',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
            'editor' => array( // name of the route
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/editor[/:action[/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'editor',
                        'action'     => 'index',
                        'id'         => '0',
                    ),
                ),
            ),
        ),
    ),

    'navigation' => array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => array(
            // And finally, here is where we define our page hierarchy
            'dogtore_symptom' => array(
                'label' => 'Symptoms',
                'route' => 'list',
                'action' => 'symptoms',
            ),
            'dogtore_cause' => array(
                'label' => 'Causes',
                'route' => 'list',
                'action' => 'causes',
            ),
            'dogtore_solution' => array(
                'label' => 'Solutions',
                'route' => 'list',
                'action' => 'solutions',
            ),
            'editor' => array(
                'label' => 'Editor',
                'route' => 'editor',
                'pages' => array(
                    'editor' => array(
                        'label' => 'Editor',
                        //contorller
                        'route' => 'editor',
                        'action' => 'index',
                    ),
                    'create' => array(
                        'divider' => 'above',
                        'header' => 'Doggies',
                        'label' => 'Create',
                        //contorller
                        'route' => 'editor',
                        'action' => 'create',
                    ),
                    'edit' => array(
                        'label' => 'Edit',
                        //contorller
                        'route' => 'editor',
                        'action' => 'edit',
                    ),
                    'delete' => array(
                        'label' => 'Delete',
                        //contorller
                        'route' => 'editor',
                        'action' => 'delete',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            strtolower(__NAMESPACE__) => __DIR__ . '/../view',
        ),
    ),

    'doctrine' => array(
        'eventmanager' => array(
            'orm_default' => array(
                'subscribers' => array(
                    'Gedmo\Tree\TreeListener',
                ),
            ),
        ),
        'driver' => array(
            // overriding zfc-user-doctrine-orm's config
            '_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => '_entity',
                ),
            ),
        ),
    ),
);
