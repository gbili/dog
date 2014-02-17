<?php
namespace Scs;

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
    }
}
