<?php
return array(
        'default' => array(
            'profile_list' => array(
                'label' => 'Profiles',
                'route' => 'profile_list',
            ),
            'auth_login' => array(
                'label' => 'Login',
                'route' => 'auth_login',
            ),
            'auth_logout' => array(
                'label' => 'Logout',
                'route' => 'auth_logout',
            ),
            'auth_register' => array(
                'label' => 'Register',
                'route' => 'auth_register',
            ),
        ),

        'side_1' => array(
            'profile_private' => array(
                'label' => 'My Profile',
                'route' => 'profile_private',
                'order' => -1,
            ),
        ),
    );
