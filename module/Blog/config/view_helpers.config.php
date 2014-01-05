<?php
namespace Blog;
return array(
    'invokables' => array(
        'elementsFlatArray'  => __NAMESPACE__ . '\View\Helper\FieldsetElementFlattener',
        'renderTree'     => __NAMESPACE__ . '\View\Helper\NestedTreeBuilder',
        'nlToBr'     => __NAMESPACE__ . '\View\Helper\NlToBr',
    ),
);
