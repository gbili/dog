<?php
namespace Dogtore\Service;

class AclCheckFactory implements \Zend\ServiceManager\FactoryInterface
{

    private $rolesAlreadyMentioned = array();

    /**
     * 
     * @param ServiceLocatorInterface $sm
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $sm)
    {
        $aclChecker =  new AclCheck();
        $this->setupAclCheck($aclChecker); 
        $aclChecker->setAuthenticationService($sm->get('Zend\Authentication\AuthenticationService'));
        return $aclChecker;
    }

    public function setupAclCheck(AclCheck $aclChecker)
    {
        $aclRules = include __DIR__ . '/../../../config/acl.role.resources.php';

        foreach ($aclRules as $listType => $rules) {
            $setAclType = 'set' . ucfirst($listType) . 'Acl';
            $aclTypes[] = $this->initAclListType($listType, $rules);
            $aclChecker->$setAclType(end($aclTypes));
        }
    }

    public function initAclListType($listType, array $list)
    {
        if (!in_array($listType, array('blacklist', 'whitelist'))) {
            throw new \Exception('ListType is not supported, use either whitelist or blacklist');
        }

        $general = (('blacklist' === $listType)? 'allow' : 'deny' );
        $specific = (('blacklist' === $listType)? 'deny' : 'allow' );

        $acl = new \Zend\Permissions\Acl\Acl();
        $acl->$general();

        foreach ($list as $role => $routes) {
            $this->setRoleAsMentioned($role);
            $role = new \Zend\Permissions\Acl\Role\GenericRole($role);
            $acl->addRole($role);
            foreach ($routes as $controledAccessRoute) {
                if (!$acl->hasResource($controledAccessRoute)) {
                    $acl->addResource(new \Zend\Permissions\Acl\Resource\GenericResource($controledAccessRoute));
                }
                $acl->$specific($role, $controledAccessRoute);
            }
        }
        return $acl;
    }

    public function getRolesAlreadyMentioned()
    {
        return $this->rolesAlreadyMentioned;
    }

    public function setRoleAsMentioned($role)
    {
        if ($this->isRoleAlreadyMentioned($role)) {
            throw new \Exception('Role cannot be mentioned in different listTypes');
        }
        return $this->rolesAlreadyMentioned[] = $role;
    }

    public function isRoleAlreadyMentioned($role)
    {
        return in_array($role, $this->getRolesAlreadyMentioned());
    }

}
