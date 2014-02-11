<?php
namespace User;
return array(
    'invokables' => array(
        'User\Service\Nonce' => __NAMESPACE__ . '\Service\Nonce',
    ),

    'factories' => array(
        'Zend\Authentication\AuthenticationService' => function ($sm) {
            return $sm->get('doctrine.authenticationservice.orm_default');
        },
    ),
);
