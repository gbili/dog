<?php
namespace Dogtore\Service;

class DoggyControllerFactory implements \Zend\ServiceManager\FactoryInterface
{
    /**
     * 
     * @param ServiceLocatorInterface $sm
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $controllerManager)
    {
        $sm = $controllerManager->getServiceLocator();
        $controller = new \Dogtore\Controller\DoggyController();
        return $controller->setEntityManager($sm->get('doctrine.entitymanager.orm_default'));
    }
}
