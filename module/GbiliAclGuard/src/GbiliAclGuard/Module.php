<?php
namespace GbiliAclGuard;

class Module 
{
    const ACL_CHECK_PRIORITY = 2;

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
        $this->attachAclGuard($e);
    }

    public function attachAclGuard(\Zend\Mvc\MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();

        $aclGuard = $e->getApplication()->getServiceManager()->get('acl_guard');
        $e->getViewModel()->aclGuard = $aclGuard;

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($aclGuard, 'check'), self::ACL_CHECK_PRIORITY);
    }
}
