<?php
namespace ZfMiner;

// Add these import statements:
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Exception;
use Zend\ModuleManager\Listener\OnBootstrapListener;


use Gbili\Db\Req\AbstractReq;
use PDO;

class Module extends OnBootstrapListener
{
    
    const CONFIG_KEY_DB_REQ     = 'gbili_db_req';
    
    const PDO_FROM_GENERAL_DB   = 'generalDbPdo';
    const PDO_FROM_MODULE_DB    = 'moduleDbPdo';
    const PDO_FROM_ZEND_DB      = 'zendDbPdo';
    const PDO_FROM_DOCTRINE_ORM = 'doctrineORMPdo';
    
    /**
     * Lower case module name
     * @var string
     */
    private $lowerCaseModuleName = null;
    
    private $pdoInstances = array();
    
    /**
     * Application Config
     * @var unknown_type
     */
    private $config = array();
    
    /**
     * 
     * @var ServiceLocatorInterface
     */
    private $serviceManager;
    
    
    /**
     * 
     */
    public function getAutoloaderConfig()
    {
        return array(
                //the application will look into this file to know which files to load
                //however if this file returns an empty array, it will force the app to use
                //the fallback class map (StandardAutoloader), provided by Zf2
                'Zend\Loader\ClassMapAutoloader' => array(
                        __DIR__ . '/autoload_classmap.php',
                ),
                'Zend\Loader\StandardAutoloader' => array(
                        'namespaces' => array(
                                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                        ),
                ),
        );
    }
    
    /**
     * 
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * 
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        /*
         * Bootstrap the Requestor
         */
        $this->serviceManager      = $e->getApplication()->getServiceManager();
        $this->config              = $this->serviceManager->get('Config');
        $this->lowerCaseModuleName = strtolower(__NAMESPACE__);

        if (!isset($this->config[$this->lowerCaseModuleName][self::CONFIG_KEY_DB_REQ])) {
             throw new Exception("Missing database configuration array for {$this->lowerCaseModuleName} module");
        }
        $this->initDbReq();
    }
    
    /**
     * 
     * @param array $this->config
     * @param ServiceLocatorInterface $this->serviceManager
     * @throws Exception
     */
    protected function initDbReq()
    {
        foreach ($this->config[$this->lowerCaseModuleName][self::CONFIG_KEY_DB_REQ] as $prefix => $getPdoFrom) {
            $pdo = $this->getPdoObject($getPdoFrom);
            AbstractReq::setPrefixedAdapter($pdo, $prefix);
        }
    }
    
    /**
     * 
     * @param unknown_type $getPdoFrom
     * @param unknown_type $this->config
     */
    protected function getPdoObject($getPdoFrom)
    {
        if (isset($this->pdoInstances[$getPdoFrom])) {
            return $this->pdoInstances[$getPdoFrom];
        }
        
        switch ($getPdoFrom) {
            case self::PDO_FROM_ZEND_DB:
                $this->pdoInstances[$getPdoFrom] = $this->serviceManager
                    ->get('Zend\Db\Adapter\Adapter')
                    ->getDriver()
                    ->getConnection()->connect()
                    ->getResource();
                break;
            case self::PDO_FROM_DOCTRINE_ORM:
                $this->pdoInstances[$getPdoFrom] = $this->serviceManager
                    ->get('Doctrine\ORM\EntityManager')
                    ->getConnection();
                break;
            case self::PDO_FROM_MODULE_DB:
                $this->config = $this->config[$this->lowerCaseModuleName];
                //no break
            case self::PDO_FROM_GLOBAL_DB:
                //using general config
                //no break
            default:
                $pdoParams = $this->config['db'];
                $this->pdoInstances[$getPdoFrom] = new PDO(
                    $pdoParams['dsn'],
                    $pdoParams['username'],
                    $pdoParams['password'],
                    $pdoParams['driver_options']
                );
                break;
        }
        
        return $this->pdoInstances[$getPdoFrom];
    }
}