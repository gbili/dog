<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog\Controller\Plugin;

/**
 *
 */
class Repository extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    protected $repository;

    /**
     * Grabs a param from route match by default.
     *
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function __invoke($entityName=null)
    {
        return $this->getRepository($entityName);
    }

    /**
     * Get the repository of the entity being represented by the controller
     * E.g: PostController -> 
     */
    public function getRepository($entityName = null)
    {
        if (null === $entityName && null !== $this->repository) {
            return $this->repository;
        }
        
        $controller = $this->getController();
        if (null === $entityName) {
            $controllerFQCN = get_class($controller);
            $controllerFQCNParts = explode('\\', $controllerFQCN);
            $controllerCN = end($controllerFQCNParts);
            $entityName = 'Blog\Entity\\' . substr($controllerCN, 0, -10);
        }

        $repo = $controller->em()->getRepository($entityName);
        if (!$repo instanceof \Blog\Entity\Repository\NestedTreeFlat) {
            return $repo;
        }

        if (!$controller->identity()->isAdmin()) {
            $repo->setLocale($controller->locale());
        }
        $paginator = $controller->paginator();
        $repo->setFirstResult($paginator->getPageFirstItem());
        $repo->setMaxResults($paginator->getItemsPerPage());
        $this->repository = $repo;
        return $repo;
    }
}
