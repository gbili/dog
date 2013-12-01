<?php
namespace Dogtore\Service;

use Dogtore\Controller\ListController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ListControllerFactory implements FactoryInterface
{
    /**
     * 
     * @param ServiceLocatorInterface $sm
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $sm = $controllerManager->getServiceLocator();
        $controller = new ListController();
        return $controller->setEntityManager($sm->get('doctrine.entitymanager.orm_default'));
    }
}
