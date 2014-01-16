<?php
namespace Blog\Controller;

class FileController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
    * Index action
    *
    */
    public function indexAction()
    {
        $em = $this->em();
        $files = $em->getRepository('Blog\Entity\File')->findBy(array(), array('date' => 'ASC'));

        return new \Zend\View\Model\ViewModel(array(
            'files' => $files,
        ));
    }

    /**
     * Edit action
     *
     */
    public function editAction()
    {
        $objectManager = $this->em();

        // Create the form and inject the object manager
        $form = new \Blog\Form\FileEdit($objectManager);
        
        //Get a new entity with the id 
        $file = $objectManager->find('Blog\Entity\File', (integer) $this->params('id'));
        
        $form->bind($file);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //Save changes
                $file->move($file->getName());
                $objectManager->flush();
            }
        }
        return new \Zend\View\Model\ViewModel(array(
            'form' => $form,
            'entity' => $file,
            'entityId' => $file->getId(),
        ));
    }

    public function uploadAction()
    {
        $fileUploader = new \Blog\Service\FileEntityUploader();

        if ($this->getRequest()->isPost()) {
            $fileUploader->setFileInputName('file')
                         ->setEntityManager($this->em())
                         ->setRequest($this->getRequest());

            if ($fileUploader->uploadFiles()) {
                return $this->redirect()->toRoute('blog', array('controller' => 'file', 'action' => 'index'));
            }
            $messages = $fileUploader->getMessages();
        }
        return new \Zend\View\Model\ViewModel(array(
            'messages' => ((isset($messages))? $messages : array()),
            'form' => $fileUploader->getFormCopy(),
        ));
    }

   /**
    * Delete action
    *
    */
    public function deleteAction()
    {
        $file = $this->em()->getRepository('Blog\Entity\File')->find($this->params('id'));
        if ($file && $file->delete()) {
            $em = $this->em();
            $em->remove($file);
            $em->flush();
            $this->flashMessenger()->addSuccessMessage('File Deleted');
        }

        return $this->redirect()->toRoute('blog', array('controller' => 'file', 'action' => 'index'));
    }
}
