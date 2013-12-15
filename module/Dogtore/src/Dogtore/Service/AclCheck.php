<?php
namespace Dogtore\Service;

class AclCheck
{
    private $whitelistAcl = null;
    
    private $blacklistAcl = null;

    private $authenticationService = null;

    private $mvcEvent = null;

    public function setBlacklistAcl(\Zend\Permissions\Acl\Acl $acl)
    {
        $this->blacklistAcl = $acl;
    }

    public function getBlacklistAcl()
    {
        if (null === $this->blacklistAcl) {
            throw new \Exception('Blacklist acl not set');
        }
        return $this->blacklistAcl;
    }

    public function hasBlacklistAcl()
    {
        return (null !== $this->blacklistAcl);
    }

    public function setWhitelistAcl(\Zend\Permissions\Acl\Acl $acl)
    {
        $this->whitelistAcl = $acl;
    }

    public function getWhitelistAcl()
    {
        if (null === $this->whitelistAcl) {
            throw new \Exception('Acl not set');
        }
        return $this->whitelistAcl;
    }
    
    public function hasWhitelistAcl()
    {
        return (null !== $this->whitelistAcl);
    }

    public function setMvcEvent(\Zend\Mvc\MvcEvent $e)
    {
        $this->mvcEvent = $e;
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
    }

    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    public function check(\Zend\Mvc\MvcEvent $e)
    {
        $this->setMvcEvent($e);

        $route = $e->getRouteMatch()->getMatchedRouteName();
        $role = ((!$this->getAuthenticationService()->hasIdentity())? 'guest' : $this->getAuthenticationService()->getIdentity()->getRole());

        $credits = $this->checkWhitelist($role, $route) + $this->checkBlacklist($role, $route);
        return (($credits > 0)? $this->allowed() : $this->denied()); 
    }

    public function checkWhitelist($role, $route)
    {
        if (!$this->hasWhitelistAcl()) {
            return 0;
        }
        $acl = $this->getWhitelistAcl();

        if (!$acl->hasRole($role)) {
            return 0; //not whitelist, must be in blacklist;
        }

        if (!$acl->hasResource($route)) {
            return -2; //resource not whitelist role has access to it
        }

        if (!$acl->isAllowed($role, $route)) {
            throw new \Exception('Should never be');
            return -2;
        } else {
            return 2;
        }
    }
    
    public function checkBlacklist($role, $route)
    {
        if (!$this->hasBlacklistAcl()) {
            return 0;
        }
        $acl = $this->getBlacklistAcl();

        if (!$acl->hasRole($role)) {
            return 0; //not blacklisted, must be in whitelist;
        }

        if (!$acl->hasResource($route)) {
            return 2; //resource not blacklisted role has access to it
        }

        if (!$acl->isAllowed($role, $route)) {
            return -2;
        } else {
            throw new \Exception('Should never be');
            return 2;
        }
    }

    public function denied()
    {
        $e = $this->getMvcEvent();
        $response = $e->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/404');
        $response->setStatusCode(404);
    }

    public function allowed()
    {
        //nothing for the moment
    }
}
