<?php
namespace Dogtore;

return array(
        'invokables' => array(
            'dogtore_scs_controller' => __NAMESPACE__ . '\Controller\ScsController',
        ),
        'factories' => array(
            'dogtore_dog_controller' => 'Upload\Controller\ConfigKeyAwareControllerFactory',
        ),
    );

