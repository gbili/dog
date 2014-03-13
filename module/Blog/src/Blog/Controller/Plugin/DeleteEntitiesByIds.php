<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Blog\Controller\Plugin;

class DeleteEntitiesByIds extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    /**
     * Nonce based delete action
     * @return mixed
     */
    public function __invoke(array $ids, $entityClassname = null)
    {
        $controller = $this->controller;
        if (null === $entityClassname) {
            $entityClassname = $controller->guessControllerEntityClassname();
        }

        $qb = $controller->em()->createQueryBuilder();
        $qb->select('e')
           ->from($entityClassname, 'e')
           ->where($qb->expr()->in('e.id', $ids));
        $entities = $qb->getQuery()->getResult();

        $identity = $controller->identity();
        foreach ($entities as $entity) {
            if ((!$identity->isAdmin()) || ($identity !== $entity->getUser())) continue;
            $controller->deleteEntity($entity);
        }
    }
}
