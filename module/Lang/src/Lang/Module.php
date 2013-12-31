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
    }
    
    public function injectLang($e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, function ($e) {
            $sm = $e->getApplication()->getServiceManager();
            $viewHelperManager = $sm->get('ViewHelperManager');

            $lang = $sm->get('lang')->getLang();
            $viewHelperManager->get('lang')->setLang($lang);

            $translator = $sm->get('translator');
            $translator->setFallbackLocale('en');
            $translator->setLocale($lang);
            $viewHelperManager->get('translate')->setTranslator($translator);
        });
    }
}
