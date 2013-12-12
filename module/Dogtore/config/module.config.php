<?php
namespace Dogtore;

return array(
    'controllers' => array(
        'factories' => array(
            __NAMESPACE__ . '\Controller\DoggyController'   => __NAMESPACE__ . '\Service\DoggyControllerFactory',
        ),
        'aliases' => array(
            'doggy' => __NAMESPACE__ . '\Controller\DoggyController',
        ),
    ),

    // The mapping of a URL to a particular action is done using routes that
    // are defined in the module's module.config.php file.
    // Before adding this, it showed a 404 not found
    'router' => array(
        'routes' => array(
            'dogtore_index' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/[:category]',
                    'constraints' => array(
                        'category' => '(?:(?:symptom)|(?:cause)|(?:solution))s?',
                    ),
                    'defaults' => array(
                        'controller'    => 'doggy',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
            'dogtore_search' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/get-me-:category{-}[-talking-about-:terms]',
                    'constraints' => array(
                        'category' => '(?:(?:symptom)|(?:cause)|(?:solution))s?',
                        'terms' => '[a-zA-Z0-9]+[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller'    => 'doggy',
                        'action'        => 'search',
                    ),
                ),
                'may_terminate' => true,
            ),
            'dogtore_view' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/view[-:slug{-}][-:category]',
                    'constraints' => array(
                        'category' => '(?:symptom)|(?:cause)|(?:solution)',
                        'slug' => '[a-zA-Z0-9-_]+',
                    ),
                    'defaults' => array(
                        'controller'    => 'doggy',
                        'action'        => 'view',
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
                'route' => 'dogtore_index',
                'params' => array('category' => 'symptoms'),
            ),
            'dogtore_cause' => array(
                'label' => 'Causes',
                'route' => 'dogtore_index',
                'params' => array('category' => 'causes'),
            ),
            'dogtore_solution' => array(
                'label' => 'Solutions',
                'route' => 'dogtore_index',
                'params' => array('category' => 'solutions'),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            strtolower(__NAMESPACE__) => __DIR__ . '/../view',
        ),
    ),
);
