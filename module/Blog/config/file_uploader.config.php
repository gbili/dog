<?php
namespace Blog;

return array(
    'media_controller' => array(
        'file_hydrator' => __NAMESPACE__ . '\Service\UploadFileHydrator',
        'include_js_script' => realpath(__DIR__ . '/../view/partial') . '/ajax.media_upload.js.phtml', 
        'route_success' => 'blog_media',
        'route_success_params' => array('action' => 'index'),
        'route_success_reuse' => true,
        /**
         * Create medias with the uploaded files
         */
        'post_upload_callback' => function ($fileUploader, $controller) {
            if ($fileUploader->hasFiles()) {
                $controller->mediaEntityCreator($fileUploader->getFiles());
            }
        },
    ),

    'file_controller' => array(
        'file_hydrator' => __NAMESPACE__ . '\Service\UploadFileHydrator',
        'route_success' => 'blog_file',
        'route_success_params' => array('action' => 'index'),
        'route_success_reuse' => true,
    ),
);
