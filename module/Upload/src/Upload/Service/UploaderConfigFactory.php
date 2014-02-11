<?php
namespace Upload\Service;

class UploaderConfigFactory implements \Zend\ServiceManager\FactoryInterface
{
    const ERROR_MISSING_CONFIG = 'Missing config with key "file_uploader"';

    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $sm)
    {
        $config = $sm->get('Config');

        if (!isset($config['file_uploader'])) {
            throw new \Exception(self::ERROR_MISSING_CONFIG);
        }

        $service = new UploaderConfig($config['file_uploader']);
        $service->setServiceLocator($sm);

        $controllerKey = $sm->get('Application')->getMvcEvent()->getRouteMatch()->getParam('controller');
        if (null === $controllerKey) {
            throw new \Exception('Trying to use Uploader service before routing has occured');
        }

        $service->setControllerKey($controllerKey);
        return $service;
    }

}
