<?php
namespace Scs;

return array(
    'factories' => array(
        'scs' => function ($sm) {
            $service = new Service\Scs($sm);
            return $service;
        },
    ),
);

