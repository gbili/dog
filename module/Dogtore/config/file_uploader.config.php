<?php
namespace Dogtore;

return array(
    'dogtore_dog_controller' => array(
        'alias' => 'ajax_media_upload',
        'service' => array(
            'form_action_route_params' => array(
                'route' => 'dogtore_dog_upload_route',
                'params' => array(
                    'controller' => 'dogtore_dog_controller',
                    'action' => 'upload',
                ),
                'reuse_matched_params' => true,
            ),
        ),

        'controller_plugin' => array(
            'route_success' => array(
                'route'                => 'dogtore_dog_add_route',
                'params'               => array(),
                'reuse_matched_params' => true,
            ),
        ),
    ),
);
