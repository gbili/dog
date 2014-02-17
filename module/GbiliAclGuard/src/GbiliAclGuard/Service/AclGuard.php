<?php
namespace GbiliAclGuard\Service;

class AclGuard
{
    const NO_RIGHTS_ROLE = 'guest';

    private $acl = null;

    private $role = null;

    private $authenticationService = null;

    private $matchedRouteName = null; 

    private $mvcEvent = null;

    public function setAcl(\Zend\Permissions\Acl\Acl $acl)
    {
        $this->acl = $acl;
    }

    public function getAcl()
    {
        if (null === $this->acl) {
            throw new \Exception('Acl not set');
        }
        return $this->acl;
    }
    
    public function hasAcl()
    {
        return (null !== $this->acl);
    }

    public function setMvcEvent(\Zend\Mvc\MvcEvent $e)
    {
        $this->mvcEvent = $e;
        return $this;
    }

    public function getMvcEvent()
    {
        if (null === $this->mvcEvent) {
            throw new \Exception('mvc event not set');
        }
        return $this->mvcEvent;
    }

    public function setAuthenticationService(\Zend\Authentication\AuthenticationService $authService)
    {
        $this->authenticationService = $authService;
        return $this;
    }

    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    public function setMatchedRouteName($routename)
    {
        $this->matchedRouteName = $routename;
        return $this;
    }

    public function getMatchedRouteName()
    {
        if (null === $this->matchedRouteName) {
             $this->setMatchedRouteName($this->getMvcEvent()->getRouteMatch()->getMatchedRouteName());
        }
        return $this->matchedRouteName;
    }

    public function hasMatchedRoute()
    {
        return null !== $this->getMvcEvent()->getRouteMatch();
    }

    public function check(\Zend\Mvc\MvcEvent $e)
    {
        if ($this->getAcl()->isAllowed($this->getRole(), $this->getMatchedRouteName())) {
            $this->allowed();
        } else {
            $this->denied();
        }
    }

    public function getRole()
    {
        if (null !== $this->role) {
            return $this->role;
        }
        $aus = $this->getAuthenticationService();
        if (!$aus->hasIdentity()) {
            return $this->role = self::NO_RIGHTS_ROLE;
        }
        if (null === $aus->getIdentity()) {
            //User deleted while he had initiated a session
            $aus->getStorage()->clear();
            return $this->role = self::NO_RIGHTS_ROLE;
        }
        $this->role = $aus->getIdentity()->getRole();
        return $this->role;
    }

    public function denied()
    {
      /*$e = $this->getMvcEvent();
        $e->stopPropagation();
        $controller = $e->getTarget();
        $lang = $this->getMvcEvent()->getApplication()->getServiceManager()->get('lang')->getLang();
        if ($this->getRole() === self::NO_RIGHTS_ROLE) {
            $controller->redirect()->toRoute('auth_login', array('lang' => $lang));
        } else {
            $controller->redirect()->toRoute('snap', array('controller' => 'snap', 'action' => 'forbidden', 'lang' => $lang));
        }*/
        throw new \Exception('Access denied');
    }

    public function allowed()
    {
        //nothing for the moment
    }
}
