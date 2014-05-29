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
        // Override the overall dog controller behaviour in
        // specific actions
        'action_override' => array(
            'viewmydog' => array( //tell uploader to set the form route to different than controller
                'view_helper' => array(
                    //overrides the on success, to add medias to wall
                    'include_js_script_from_basename' => 'ajax_media_upload.js.phtml', 
                ),
                'service' => array(
                    'form_action_route_params' => array(
                        'route' => 'dogtore_dog_upload_my_dog_medias_route',
                        'params' => array(
                            'controller' => 'dogtore_dog_controller',
                            'action' => 'uploaddogmedias',
                        ),
                        // this will set the dogname_underscored which is 
                        // required to add each media to the proper dog
                        // inside the controller_plugin:post_upload_callback
                        'reuse_matched_params' => true,
                    ),
                ),
            ),
            'uploadmydogmedias' => array(
                'controller_plugin' => array(
                    // For each uploaded media, add it to the dog medias
                    'post_upload_callback' => function ($fileUploader, $controller) {
                        if (!$fileUploader->hasFiles()) {
                            return;
                        }

                        $medias = $controller->mediaEntityCreator($fileUploader->getFiles());
                        $em = $controller->em();

                        $dogname = $controller->routeParamTransform('dogname_underscored')->underscoreToSpace();

                        $dogs = $controller->repository()->findBy(array(
                                'user' => $controller->identity(), 
                                'name' => $dogname,
                            )
                        );

                        if (empty($dogs)) {
                            throw new \Exception('No such dog');
                        }

                        $dog = current($dogs);

                        $uploadedMedias = array();

                        foreach ($medias as $media) {
                            $dog->addMedia($media);

                            $uploadedMedias[] = array(
                                'mediaId' => $media->getId(),
                                'mediaSrc' => $media->getSrc(),
                            );
                        }
                        $em->persist($dog);
                        $em->flush();

                        return $uploadedMedias;
                    },
                ),
            ),
        ),
    ),
);
