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
                        'action' => 'search',
                    ),
                    'defaults' => array(
                        'controller'    => 'list',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
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
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            strtolower(__NAMESPACE__) => __DIR__ . '/../view',
        ),
    ),
);
