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
 * Get the repository based on controller or __invoke param
 */
class Repository extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    /**
     * @var array('controllerClass' => repositoryInstance, )
     */
    protected $controllerToRepository = array();

    /**
     * @var array('repositoryClass' => repositoryInstance, )
     */
    protected $preparedRepositories = array();

    /**
     * @var array('repositoryClass' => callback, )
     */
    protected $preparationCallbacks = array();

    /**
     * Grabs a param from route match by default.
     *
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function __invoke($entityName=null)
    {
        if (null === $entityName) {
            return $this->guessRepository();
        }
        return $this->getRepository($entityName);
    }

    /**
     * Get the repository of the entity being represented by the controller
     * E.g: Blog\Controller\PostController -> Blog\Entity\Post repository
     */
    public function guessRepository()
    {
        $controllerClass = get_class($this->controller);

        if ($this->alreadyGuessed($controllerClass)) {
            return $this->controllerToRepository[$controllerClass]; 
        }

        $entityClass = $this->controller->guessControllerEntityClassname();
        $repository = $this->getRepository($entityClass);

        $this->controllerToRepository[$controllerClass] = $repository;
        return $repository;
    }

    protected function alreadyGuessed($controllerClass)
    {
        return isset($this->controllerToRepository[$controllerClass]);
    }

    public function getRepository($entityClass)
    {
        $repository = $this->controller->em()->getRepository($entityClass);
        return $this->prepareRepository($repository);
    }

    public function prepareRepository($repository)
    {
        $repositoryClass = get_class($repository);
        if (!$this->needsPreparation($repositoryClass)) {
            return $repository;
        }

        call_user_func($this->getPreparationCallback($repositoryClass), $repository, $this);

        $this->preparedRepositories[] = $repositoryClass;

        return $repository;
    }

    public function setPreparationCallbacks(array $preparationCallbacks)
    {
        $this->preparationCallbacks = $preparationCallbacks;
        return $this;
    }

    protected function getPreparationCallback($repositoryClass)
    {
        if (!$this->hasPreparationCallback($repositoryClass)) {
            throw new \Exception('No preparation callback, call hasPreparationCallback before this');
        }

        $callback = $this->preparationCallbacks[$repositoryClass];
        while (is_string($callback)) {
            $aliasedCallback = $callback;
            if (!isset($this->preparationCallbacks[$aliasedCallback])) {
                throw new \Exception('Aliased callback not set');
            }
            $callback = $this->preparationCallbacks[$aliasedCallback];
        }

        if (!is_callable($callback)) {
            throw new \Exception('Bad callback');
        }

        return $callback;
    }

    public function needsPreparation($repositoryClass)
    {
        return !$this->isPrepared($repositoryClass) && $this->hasPreparationCallback($repositoryClass);
    }

    public function isPrepared($repositoryClass)
    {
        return in_array($repositoryClass, $this->preparedRepositories);
    }

    public function hasPreparationCallback($repositoryClass)
    {
        return isset($this->preparationCallbacks[$repositoryClass]);
    }
}
