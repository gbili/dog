<?php
namespace ZfMiner;
use PDO;
use Gbili\Db\Req\AbstractReq;

return array(
    
    'navigation' => array(
        // The DefaultNavigationFactory we configured in (1) uses 'default' as the sitemap key
        'default' => array(
            // And finally, here is where we define our page hierarchy
            'engine' => array(
                'label' => 'Engine',
                'route' => 'engine',
                'pages' => array(
                    'dashboard' => array(
                        'label' => 'Dashboard',
                        'route' => 'engine',
                    ),
                    'install' => array(
                        'divider' => 'above',
                        'header' => 'Actions',
                        'label' => 'Install',
                        //contorller
                        'route' => 'engine/install',
                        //action
                        'action' => 'install',
                    ),
                    'uninstall' => array(
                        'label' => 'Uninstall',
                        //contorller
                        'route' => 'engine/uninstall',
                        //action
                        'action' => 'uninstall',
                    ),
                ),
            ),
        ),
    ),

    'zfminer' => array(
        'db' => array (                  //create from array, reuse global usename and password (otherwise add keys to local like gbili_db_req => array('username=>>'', 'password =>''))
            'driver'         => 'Pdo',
            'dsn'            => 'mysql:dbname=miner;host=localhost',
            'driver_options' => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            ),
        ),
        Module::CONFIG_KEY_DB_REQ => array(
            //'Gbili\Miner' => Module::PDO_FROM_GENERAL_DB, //use the config db key and create a new pdo instance
            'ZfMiner'                            => Module::PDO_FROM_DOCTRINE_ORM, //get from Zend\Db\Adapter\Adapter
            AbstractReq::FALLBACK_ADAPTER_PREFIX => Module::PDO_FROM_DOCTRINE_ORM,
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'ZfMiner\Controller\Engine' => 'ZfMiner\Controller\EngineController',
            'ZfMiner\Controller\Blueprint' => 'ZfMiner\Controller\BlueprintController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'engine' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/engine[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ZfMiner\Controller\Engine',
                        'action' => 'index',
                    ),
                ),
            ),
            'blueprint' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/blueprint[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'ZfMiner\Controller\Blueprint',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'zfminer' => __DIR__ . '/../view',
        ),
    ),
);