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
            $lang = $e->getRouteMatch()->getParam('lang');
            $sm = $e->getApplication()->getServiceManager();
            $translator = $sm->get('translator');
            $translator->setFallbackLocale('en');
            if (null !== $lang) {
                $translator->setLocale($lang);
            }
            $sm->get('ViewHelperManager')->get('translate')->setTranslator($translator);
            $sm->get('ViewHelperManager')->get('lang')->setLang(((null !== $lang)? $lang : 'en'));
        });
    }
}
