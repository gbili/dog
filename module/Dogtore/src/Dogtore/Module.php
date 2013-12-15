<?php

namespace Dogtore;

class Module 
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
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
        $this->attachAcl($e);
    }

    public function attachAcl(\Zend\Mvc\MvcEvent $e)
    {
        $aclChecker = $e->getApplication()->getServiceManager()->get('aclcheck');
        $e->getApplication()->getEventManager()->attach('route', array($aclChecker, 'check'));
    }
}
