<?php
if (!isset($_SERVER['RDS_HOSTNAME'])) {
    return array();
}
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'configuration' => 'orm_default',
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => $_SERVER['RDS_HOSTNAME'],
                    'port'     => $_SERVER['RDS_PORT'],
                    'dbname'   => $_SERVER['RDS_DB_NAME'],
                    'password' => $_SERVER['RDS_PASSWORD'],
                    'user'     => $_SERVER['RDS_USERNAME'],
                ),
            ),
        ),
        'cache' => array(
            'class' => 'Doctrine\Common\Cache\ApcCache'
        ),
        'configuration' => array(
            'orm_default' => array(
                'metadata_cache' => 'apc',
                'query_cache'    => 'apc',
                'result_cache'   => 'apc',
                'generate_proxies' => false,
            ),
        ),
    ),
);
