<?php
namespace Dogtore;

return array(
    'acl_lists' => array(
        'whitelist' => array(
            'guest' => array(
                //white list
                'scs_scs_index_route',
                'scs_scs_search_route',
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
    ),
);
