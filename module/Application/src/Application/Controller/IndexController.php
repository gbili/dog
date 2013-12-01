<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        //return new ViewModel();
        $entity_manager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $postRoot = new \Dogtore\Entity\Post();
        $postRoot->setTitle('Symptom - My Root Cool Cool Post');
        $postRoot->setContent('Hello this is some cool content.');

        $postChild = new \Dogtore\Entity\Post();
        $postChild->setTitle('Cause - My child cool post');
        $postChild->setContent('My child cool content');
        $postChild->setParent($postRoot);

        $postGrandChild = new \Dogtore\Entity\Post();
        $postGrandChild->setTitle('Solution - My grand child cool post');
        $postGrandChild->setContent('My grand child cool content');
        $postGrandChild->setParent($postChild);

        $entity_manager->persist($postRoot);
        $entity_manager->persist($postChild);
        $entity_manager->persist($postGrandChild);
        $entity_manager->flush();

        $post_repo = $entity_manager->getRepository('Dogtore\Entity\Post');
        $m = $post_repo->findOneByTitle('Cause - My child cool post');
        echo $post_repo->childCount($m);
    }
}
