<?php
namespace Dogtore\Controller;

class ListController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     *
     * @var \Doctrine\ORM\EntityMangager
     */
    protected $em;

    /**
     *
     * @return \Doctrine\ORM\EntityMangager
     */
    public function getEntityManager()
    {
        if (null === $this->em) {
           throw new \Dogtore\Exception\GetBeforeSetException('Must be set from factory'); 
        }
        return $this->em;
    }

    /**
     *
     * @param \Doctrine\ORM\EntityMangager $em
     * @return \Malouer\Controller\MeasurementController
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
        return $this;
    }

    /**
     *
     *
     */
    public function indexAction()
    {
        return new \Zend\View\Model\ViewModel(array(
            'posts' => $this->getEntityManager()->getRepository('Dogtore\Entity\Post')->findAll()
        ));
    }

    public function symptomsAction()
    {
        return new \Zend\View\Model\ViewModel(array(
            'posts' => $this->getEntityManager()->getRepository('Dogtore\Entity\Post')->findAll()
        ));
    }
    
    public function causesAction()
    {
        return new \Zend\View\Model\ViewModel(array(
            'posts' => $this->getEntityManager()->getRepository('Dogtore\Entity\Post')->findAll()
        ));
    }

    public function solutionsAction()
    {
        return new \Zend\View\Model\ViewModel(array(
            'posts' => $this->getEntityManager()->getRepository('Dogtore\Entity\Post')->findAll()
        ));
    }
}
