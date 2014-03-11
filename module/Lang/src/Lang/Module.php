<?php
namespace Lang;

class Module 
{
    public function getConfig()
    {
        $preConfig = include __DIR__ . '/../../config/module.pre_config.php';
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
        $this->injectTextdomain($e);
        $this->missingTranslationListener($e);
    }

    public function missingTranslationListener($e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $translator = $sm->get('MvcTranslator');
        $translator->enableEventManager();
        $eventManager = $translator->getEventManager();
        $eventManager->attach(\Zend\I18n\Translator\Translator::EVENT_MISSING_TRANSLATION, function ($e) use ($sm){ 
            $params                    = $e->getParams();
            $translator                = $e->getTarget();
            $translationStorageService = $sm->get('translationStorage');
            $translationStorageService->setTranslation($params['text_domain'], $params['locale'], $params['message'], $translation='', $overwrite=false);
        });
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

    public function injectTextdomain($e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH, function ($e) {
            $sm = $e->getApplication()->getServiceManager();
            $textdomainService = $sm->get('textdomain');
            $textdomainService->setController($e->getTarget());
        });
    }
}
