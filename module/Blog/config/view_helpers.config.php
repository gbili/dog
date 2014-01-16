<?php
namespace Blog;
return array(
    'invokables' => array(
        'elementsFlatArray'  => __NAMESPACE__ . '\View\Helper\FieldsetElementFlattener',
        'renderTree'     => __NAMESPACE__ . '\View\Helper\NestedTreeBuilder',
        'nlToBr'     => __NAMESPACE__ . '\View\Helper\NlToBr',
        'paginator'     => __NAMESPACE__ . '\View\Helper\Paginator',
        'message'     => __NAMESPACE__ . '\View\Helper\Message',
        'highlight'     => __NAMESPACE__ . '\View\Helper\TermsHighlighter',
        'cssClass'     => __NAMESPACE__ . '\View\Helper\CategoryCssClass',
    ),
);
