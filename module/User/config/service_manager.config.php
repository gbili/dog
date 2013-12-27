<?php
namespace User;
return array(
    'factories' => array(
        'Zend\Authentication\AuthenticationService' => function ($sm) {
            return $sm->get('doctrine.authenticationservice.orm_default');
        },
    ),
);
