<?php
namespace Malouer\Service;

use Malouer\Filter\StringToUnit;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StringToUnitFactory implements FactoryInterface
{
    /**
     * 
     * @param ServiceLocatorInterface $sm
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $em = $sm->get('doctrine.entitymanager.orm_default');
        return new StringToUnit($em);
    }
}
