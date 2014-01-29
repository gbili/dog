<?php
namespace Blog;
return array(
    'invokables' => array(
        'elementsFlatArray'     => __NAMESPACE__ . '\View\Helper\FieldsetElementFlattener',
        'renderTree'            => __NAMESPACE__ . '\View\Helper\NestedTreeBuilder',
        'nlToBr'                => __NAMESPACE__ . '\View\Helper\NlToBr',
        'paginator'             => __NAMESPACE__ . '\View\Helper\Paginator',
        'message'               => __NAMESPACE__ . '\View\Helper\Message',
        'notify'                => __NAMESPACE__ . '\View\Helper\Notify',
        'highlight'             => __NAMESPACE__ . '\View\Helper\TermsHighlighter',
        'cssClass'              => __NAMESPACE__ . '\View\Helper\CategoryCssClass',
        'renderElement'         => __NAMESPACE__ . '\View\Helper\FormElement',
        'renderForm'            => __NAMESPACE__ . '\View\Helper\Form',
        'formSameActionPrepare' => __NAMESPACE__ . '\View\Helper\FormActionPrepare',
        'numberInLetters'       => __NAMESPACE__ . '\View\Helper\NumberToString',
        'renderNavigation'      => __NAMESPACE__ . '\View\Helper\RenderNavigation',
    ),
    'factories' => array(
        'conditionalNavigation'        => function ($viewHelperPluginManager) {
            $sm = $viewHelperPluginManager->getServiceLocator();
            $navHelper = new View\Helper\ConditionalNavigation;
            $navHelper->setServiceLocator($sm);
            return $navHelper;
        },
    ),
);
