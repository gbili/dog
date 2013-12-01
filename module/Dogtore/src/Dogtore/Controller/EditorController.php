<?php
namespace Dogtore\Controller;

class EditorController extends \Zend\Mvc\Controller\AbstractActionController
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

    public function indexAction()
    {
        return new \Zend\View\Model\ViewModel(array(
            'posts' => $this->getEntityManager()->getRepository('Dogtore\Entity\Post')->findAll()
        ));
    }

    public function createAction()
    {
        $form    = new \Dogtore\Form\PostCreate();
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->showForm($form);
        } else {
            $inputFilter = new \Dogtore\InputFilter\PostCreate();
            $inputFilter->init();
            $form->setInputFilter($inputFilter);
            return $this->validateFormInput($form, $request);
        }
    }

    protected function showForm($form)
    {
        $form->prepare();
        return array('form' => $form);
    }

    protected function validateFormInput($form, $request)
    {
        $form->setData($request->getPost());
        if ($form->isValid()) {
            $this->savePost($form->getData());
            //Redirect to list measurements
            return $this->redirect()->toRoute(array('Malouer', 'Measurement', 'index'));
        } else {
            return $this->showForm($form);
        }
    }

    protected function savePost($formData)
    {
        var_dump($formData);
        $measurementType = $formData['type'];
        $measurement = new $measurementType();
        $measurement->populate($form->getData());
        $this->getEntityManager()->persist($measurement);
        $this->getEntityManager()->flush();
    }
    
    public function deleteAction()
    {
        return new \Zend\View\Model\ViewModel();
    }

    public function editAction()
    {
        return new \Zend\View\Model\ViewModel();
    }
}
