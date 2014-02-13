<?php
namespace Blog;
return array(
    'invokables' => array(
        'em'                 => __NAMESPACE__ . '\Controller\Plugin\EntityManager',
        'paginator'          => __NAMESPACE__ . '\Controller\Plugin\Paginator',
        'messenger'          => __NAMESPACE__ . '\Controller\Plugin\Messenger',
        'mediaEntityCreator' => __NAMESPACE__ . '\Controller\Plugin\MediaEntityCreator',
        'string'             => __NAMESPACE__ . '\Controller\Plugin\ExpressivePregTransform',
        'routeParamTransform'=> __NAMESPACE__ . '\Controller\Plugin\RouteParamsTransformer',
    ),
    'factories' => array(
        'repository'         => function ($controllerPluginManager) {
            $sm = $controllerPluginManager->getServiceLocator();
            $plugin = new Controller\Plugin\Repository;
            $config = $sm->get('Config');
            $plugin->setPreparationCallbacks($config['controller_plugin_repository']['preparation_callbacks']);
            return $plugin;
        },
    ),
);
