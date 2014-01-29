<?php
namespace User;
return array(
        'invokables' => array(
            'auth' => 'User\Controller\AuthController',
            'profile' => 'User\Controller\ProfileController',
            'admin' => 'User\Controller\AdminController',
        ),
    );
