<?php
namespace GbiliAclGuard\Service;

class AclGuardFactory implements \Zend\ServiceManager\FactoryInterface
{
    /**
     * 
     * @param ServiceLocatorInterface $sm
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $sm)
    {
        $guard = new AclGuard();
        $guard->setAcl($sm->get('acl'));
        $guard->setAuthenticationService($sm->get('Zend\Authentication\AuthenticationService'));
        $guard->setMvcEvent($sm->get('Application')->getMvcEvent());
        return $guard;
    }
}
