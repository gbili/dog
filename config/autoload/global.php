<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 t
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'dbname'   => 'dog',
                ),
            ),
        ),
    ),
/*    Charset not working, using Doctrine PDOConnection instead
 *    'gbili' => array(
        'db_req' => array(
            'driver_class'   => '\PDO',
            'dsn'            => 'mysql:dbname=dog;host=localhost;charset=utf8',
            'driver_options' => array(
                //\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
            ),
        ),
    ),/*
    /*'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),*/
);
