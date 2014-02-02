<?php
namespace Upload;
return array(
    'invokables' => array(
        'ajaxFileUpload'    => __NAMESPACE__ . '\View\Helper\AjaxFileUploadProgress',
        'fileUploadMessage' => __NAMESPACE__ . '\View\Helper\FileUploadMessage',
    ),
);
