<?php
namespace Lang;
return array(
    'factories' => array(
        'lang' => __NAMESPACE__ . '\Service\LangFactory',
    ),

    'aliases' => array(
        'locale'             => 'lang',
    ),
);
