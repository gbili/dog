<?php
namespace Blog;

return array(
    'factories' => array(
        'side_1' => __NAMESPACE__ . '\Service\SideNavigation1Factory',
        'side_2' => __NAMESPACE__ . '\Service\SideNavigation2Factory',
    ),
    'invokables' => array(
        'uploadFileHydrator' => __NAMESPACE__ . '\Service\UploadFileHydrator',
    ),
);
