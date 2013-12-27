<?php
namespace Lang;
return array(
    'invokables' => array(
        'patternTranslate' => __NAMESPACE__ . '\View\Helper\PatternTranslate',
        'translate'        => __NAMESPACE__ . '\View\Helper\TranslateWriter',
        'lang'             => __NAMESPACE__ . '\View\Helper\Lang',
    ),
);
