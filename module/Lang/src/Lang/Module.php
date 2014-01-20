<?php
namespace Lang;

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
        $this->injectLang($e);
        $this->injectTextDomain($e);
    }
    
    public function injectLang($e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, function ($e) {
            $sm = $e->getApplication()->getServiceManager();
            $currentLang = $sm->get('lang')->getLang();

            $translator = $sm->get('MvcTranslator');
            $translator->setFallbackLocale('en');
            $translator->setLocale($currentLang);
        });
    }

    public function injectTextDomain($e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, function ($e) {
            $sm = $e->getApplication()->getServiceManager();
            $sm->get('textdomain')->setController($e->getTarget());
        });
    }
}
