<?php
namespace Blog;
return array(
    'invokables' => array(
        'em'                 => __NAMESPACE__ . '\Controller\Plugin\EntityManager',
        'paginator'          => __NAMESPACE__ . '\Controller\Plugin\Paginator',
        'repository'         => __NAMESPACE__ . '\Controller\Plugin\Repository',
        'messenger'          => __NAMESPACE__ . '\Controller\Plugin\Messenger',
        'mediaEntityCreator' => __NAMESPACE__ . '\Controller\Plugin\MediaEntityCreator',
        'string'             => __NAMESPACE__ . '\Controller\Plugin\ExpressivePregTransform',
        'routeParamTransform'=> __NAMESPACE__ . '\Controller\Plugin\RouteParamsTransformer',
    ),
);
