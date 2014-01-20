<?php
namespace Lang;
return array(
    'factories' => array(
        'lang' => __NAMESPACE__ . '\Service\LangFactory',
        'textdomain' => function ($sm) {
            return new Service\TextDomain($sm);
        },
    ),

    'aliases' => array(
        'locale'             => 'lang',
    ),
);
