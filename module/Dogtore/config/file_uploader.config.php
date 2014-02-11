<?php
namespace Dogtore;

return array(
    'dogtore_dog_controller' => array(
        'service' => array(
            'file_hydrator' => 'blogUploadFileHydrator',
            'form_action_route_params' => array(
                'route' => 'dogtore_dog_upload_route',
                'params' => array(
                    'controller' => 'dogtore_dog_controller',
                    'action' => 'upload',
                ),
                'reuse_matched_params' => true,
            ),
            'include_js_script' => realpath(__DIR__ . '/../view/partial') . '/ajax.dog_add_image_upload.js.phtml', 
        ),

        'controller_plugin' => array(
            'route_success' => array(
                'route'                => 'dogtore_dog_add_route',
                'params'               => array(),
                'reuse_matched_params' => true,
            ),
            /**
             * Create medias with the uploaded files
             */
            'post_upload_callback' => function ($fileUploader, $controller) {
                if (!$fileUploader->hasFiles()) {
                    return;
                }

                $medias = $controller->mediaEntityCreator($fileUploader->getFiles());
                $uploadedMedias = array();
                foreach ($medias as $media) {
                    $uploadedMedias[] = array(
                        'mediaId' => $media->getId(),
                        'mediaSrc' => $media->getSrc(),
                    );
                }
                return $uploadedMedias;
            },
        ),
    ),
);
