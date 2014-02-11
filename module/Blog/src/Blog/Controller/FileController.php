<?php
namespace Blog\Controller;

class FileController extends \Zend\Mvc\Controller\AbstractActionController 
    implements \Upload\ConfigKeyAwareInterface
{
    protected $configKey;

    public function getConfigKey()
    {
        return $this->configKey;
    }

    public function setConfigKey($configKey)
    {
        $this->configKey = $configKey;
        return $this;
    }

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
            'form'       => new \Blog\Form\FileBulk('bulk-action'),
        ));
    }

    public function bulkAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('blog_file_route', array('controller' => 'blog_file_controller', 'action' => 'index'));
        }

        $form = new \Blog\Form\FileBulk('bulk-action');

        $em = $this->em();
        $files = $em->getRepository('Blog\Entity\File')->findBy(array(), array('date' => 'DESC'));

        $form->hydrateValueOptions($files);

        $form->setData($formData = $this->request->getPost());

        if (!$form->isValid()) {
            return $this->redirect()->toRoute('blog_file_route', array('controller' => 'blog_file_controller', 'action' => 'index'));
        }

        $formValidData = $form->getData();
        $action = $form->getSelectedAction();

        $this->$action($formValidData['files']);

        $this->flashMessenger()->addMessage($action . ' succeed');
        return $this->redirectToCategoriesList();
    }

    public function deleteFiles(array $filesIds)
    {
        //translations is limited to admin
        if (!$this->identity()->isAdmin()) {
            return $this->redirect()->toRoute('blog_file_route', array('controller' => 'blog_file_controller', 'action' => 'index'));
        }

        $em = $this->em();
        foreach ($filesIds as $id) {
            $file = $this->em()->getRepository('Blog\Entity\File')->find($id);
            if ($file && $file->delete()) {
                $em = $this->em();
                $em->remove($file);
                $em->flush();
            }
        }
        $this->flashMessenger()->addSuccessMessage('Files Deleted');
        return $this->redirect()->toRoute('blog_file_route', array('controller' => 'blog_file_controller', 'action' => 'index'));
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
        return $this->fileUploader();
    }

   /**
    * Delete action
    *
    */
    public function deleteAction()
    {
        $file = $this->em()->getRepository('Blog\Entity\File')->find($this->params('id'));
        return $this->deleteFiles(array($id));
    }
}
