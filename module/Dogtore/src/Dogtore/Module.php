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
        $this->attachAclGuard($e);
        $this->injectLocaleIntoTranslator($e);
    }
    
    public function injectLocaleIntoTranslator($e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, function ($e) {
            $lang = $e->getRouteMatch()->getParam('lang');
            $translator = $e->getApplication()->getServiceManager()->get('translator');
            $translator->setFallbackLocale('en_US');
            if (null !== $lang) {
                $translator->setLocale($lang);
            }
        });
    }

    public function attachAclGuard(\Zend\Mvc\MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();

        $aclGuard = $e->getApplication()->getServiceManager()->get('acl_guard')->setMvcEvent($e);
        $e->getViewModel()->aclGuard = $aclGuard;

        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, array($aclGuard, 'check'));
    }
}
