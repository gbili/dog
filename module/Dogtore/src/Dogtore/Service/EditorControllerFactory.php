<?php
namespace Dogtore\Service;

use Dogtore\Controller\EditorController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EditorControllerFactory implements FactoryInterface
{
    /**
     * 
     * @param ServiceLocatorInterface $sm
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $sm = $controllerManager->getServiceLocator();
        $controller = new EditorController();
        return $controller->setEntityManager($sm->get('doctrine.entitymanager.orm_default'));
    }
}
