<?php
return array(
    'whitelist' => array(
        'guest' => array(
            //white list
            'dogtore_index',
            'dogtore_search',
            'dogtore_view',
            'profile_view',
            'auth_login',
            'auth_register',
        ),
    ),
    'blacklist' => array(
        'user' => array(
            //empty blacklist allowed everything
            'auth_login',
            'auth_register',
        ),
    ),
);
