<?php
namespace Blog;

return array(
    'blog_media_controller' => array(
        'service' => array(
            'file_hydrator' => 'blogUploadFileHydrator',
            'form_action_route_params' => array(
                'route' => 'blog_media_route',
                'params' => array(
                    'controller' => 'blog_media_controller',
                    'action' => 'upload',
                ),
                'reuse_matched_params' => true,
            ),
            'include_js_script' => realpath(__DIR__ . '/../view/partial') . '/ajax.media_upload.js.phtml', 
        ),
        'controller_plugin' => array(
            'route_success' => array(
                'route'                => 'blog_media_route',
                'params'               => array(
                    'action' => 'index'
                ),
                'reuse_matched_params' => true,
            ),
            /**
             * Create medias with the uploaded files
             */
            'post_upload_callback' => function ($fileUploader, $controller) {
                if ($fileUploader->hasFiles()) {
                    $controller->mediaEntityCreator($fileUploader->getFiles());
                }
            },
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

    'default' => 'blog_file_controller',
);
