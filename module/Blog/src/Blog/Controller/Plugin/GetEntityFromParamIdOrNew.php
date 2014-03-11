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
class GetEntityFromParamIdOrNew extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    /**
     * Grabs a param from route match by default.
     *
     * @param string $param
     * @param mixed $default
     * @return mixed
     */
    public function __invoke($entityClassname=null)
    {
        $controller = $this->controller;
        if (null === $entityClassname) {
            $entityClassname = $controller->guessControllerEntityClassname();
        }

        $entityId = $controller->params()->fromRoute('id');
        $entity = null;

        if (null !== $entityId) {
            $entity = $controller->em()->find($entityClassname, (integer) $entityId);
        }

        if (null === $entity) {
            return new $entityClassname();
        }
        return $entity;
    }
}
