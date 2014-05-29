<?php
namespace GbiliAclGuard\Service;

class AclFactory implements \Zend\ServiceManager\FactoryInterface
{
    private $routenames = array();

    private $serviceManager = null;
    
    private $acl = null;

    /**
     * 
     * @param ServiceLocatorInterface $sm
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $sm)
    {
        $this->serviceManager = $sm;
        $this->configureAcl(); 
        return $this->getAcl();
    }

    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    public function getAcl()
    {
        if (null === $this->acl) {
            $this->acl = new \Zend\Permissions\Acl\Acl();
        }
        return $this->acl;
    }

    public function getRoutenames()
    {
        if (empty($this->routenames)) {
            $acl = $this->getAcl();
            foreach ($this->getServiceManager()->get('router')->getRoutes() as $routename => $route) {
                $this->addRouteToAcl($routename);
                $this->routenames[] = $routename;
            }
        }
        return $this->routenames;
    }

    public function addRouteToAcl($routename)
    {
        $acl = $this->getAcl();
        if ($acl->hasResource($routename)) {
            throw new \Exception('RepeatedRoute: ' . $routename);
        }
        $acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($routename));
    }

    public function configureAcl()
    {
        $config = $this->serviceManager->get('Config');
        if (!isset($config['gbiliaclguard']['acl_lists'])) {
            throw new \Exception('Missing config[gbiliaclguard][acl_lists]');
        }
        $aclRules = $config['gbiliaclguard']['acl_lists'];
        foreach ($aclRules as $listType => $rules) {
            $this->configureAclFromListType($listType, $rules);
        }
    }

    public function configureAclFromListType($listType, array $list)
    {
        if (!in_array($listType, array('blacklist', 'whitelist'))) {
            throw new \Exception('ListType is not supported, use either whitelist or blacklist');
        }
        $notSpecified = (('blacklist' === $listType)? 'allow' : 'deny' );
        $specified = (('blacklist' === $listType)? 'deny' : 'allow' );

        foreach ($list as $role => $routes) {
            $this->configureRole($role, $routes, $specified, $notSpecified);
        }
    }

    public function configureRole($role, $controlledRoutes, $specified, $notSpecified) 
    {
        $acl = $this->getAcl();
        if ($acl->hasRole($role)) {
            throw new \Exception('You cannot mention the same role in both list types: ' . $role);
        }
        $role = new \Zend\Permissions\Acl\Role\GenericRole($role);
        $acl->addRole($role);

        $allRoutes = $this->getRoutenames();
        foreach ($allRoutes as $route) {
            $allowOrDeny = ((in_array($route, $controlledRoutes))?$specified:$notSpecified);
            $acl->$allowOrDeny($role, $route);
        }
    }
}
