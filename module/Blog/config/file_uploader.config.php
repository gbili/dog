<?php
namespace Blog;

return array(
    'blog_media_controller' => array(
        'alias' => 'ajax_media_upload',
        'view_helper' => array(
            'display_form_as_popup'      => false,
        ),
    ),

    'blog_post_controller' => array(
        'alias' => 'ajax_media_upload',
        'controller_plugin' => array(
            'route_success' => array(
                'route'  => 'blog_post_route',
                'params' => array(
                    'action' => 'create'
                ),
                'reuse_matched_params' => true,
            ),
        ),
    ),

    'blog_file_controller' => array(
        'service' => array(
            'file_hydrator' => 'blogUploadFileHydrator',
            'form_action_route_params' => array(
                'route' => 'blog_file_route',
                'params' => array(
                    'controller' => 'blog_file_controller',
                    'action' => 'upload',
                ),
                'reuse_matched_params' => true,
            ),
        ),
        'controller_plugin' => array(
            'route_success' => array(
                'route' => 'blog_file_route',
                'params' => array(
                    'action' => 'index'
                ),
                'reuse_matched_params' => true,
            ),
        ),
    ),
);
