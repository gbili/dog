<?php
return array(
    'whitelist' => array(
        'guest' => array(
            //white list
            'dogtore_index',
            'dogtore_search',
            'dogtore_view',
            'auth',
        ),
    ),
    'blacklist' => array(
        'user' => array(
            //empty blacklist allowed everything
        ),
    ),
);
