<?php
return array(
    'whitelist' => array(
        'guest' => array(
            //white list
            'dogtore_index',
            'dogtore_search',
            'dogtore_view',
            'profile_publicly_available',
            'auth_login',
            'auth_register',
        ),
    ),
    'blacklist' => array(
        'user' => array(
            'auth_login',
            'auth_register',
            'blog_file_route',
            'admin_index',
            'admin_delete',
            'admin_edit',
            //empty blacklist allowed everything
        ),
        'admin' => array(
            'auth_login',
            'auth_register',
            //empty blacklist allowed everything
        ),
    ),
);
