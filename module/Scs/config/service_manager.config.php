<?php
namespace Dogtore;
return array(
    'factories' => array(
        __NAMESPACE__ . '\Service\Acl' => __NAMESPACE__ . '\Service\AclFactory',
        __NAMESPACE__ . '\Service\AclGuard' => __NAMESPACE__ . '\Service\AclGuardFactory',
    ),
    'aliases' => array(
        'acl_guard' => __NAMESPACE__ . '\Service\AclGuard',
        'acl' => __NAMESPACE__ . '\Service\Acl',
    ),
);

