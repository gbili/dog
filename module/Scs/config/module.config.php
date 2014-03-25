<?php
namespace Scs;

return array(
    'view_manager' => array(
        'template_path_stack' => array(
            strtolower(__NAMESPACE__) => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'controllers'     => include __DIR__ . '/controllers.config.php',
    'router'          => include __DIR__ . '/router.config.php',
    'translator'      => include __DIR__ . '/translator.config.php',
    'service_manager' => include __DIR__ . '/service_manager.config.php',
    'view_helpers'    => include __DIR__ . '/view_helpers.config.php',
);
