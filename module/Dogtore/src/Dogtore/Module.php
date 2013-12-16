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
        $eventManager = $e->getApplication()->getEventManager();

        $aclGuard = $e->getApplication()->getServiceManager()->get('acl_guard');
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($aclGuard, 'check'));
    }
}
