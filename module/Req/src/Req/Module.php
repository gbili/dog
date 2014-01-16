<?php
namespace Req;

class Module 
{
    public function getConfig()
    {
        return array();
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        $this->injectDbAdapter($e);
    }
    
    public function injectDbAdapter($e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $em = $sm->get('Doctrine\ORM\EntityManager');
        $pdo = $em->getConnection();
        \Gbili\Db\Req\AbstractReq::setAdapter($pdo);
        return;
    }
}
