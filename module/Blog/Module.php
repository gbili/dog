<?php
namespace Blog;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e)
    {
        $em = $e->getApplication()->getEventManager();

        $em->attach(\Zend\Mvc\MvcEvent::EVENT_RENDER, function($e) {
            $flashMessenger = new \Zend\Mvc\Controller\Plugin\FlashMessenger();
            if ($flashMessenger->hasSuccessMessages()) {
                $e->getViewModel()->setVariable('successMessages', $flashMessenger->getSuccessMessages());
            }
        });

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
}
