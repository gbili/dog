<?php
namespace Blog\Controller;

use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\View\Model\ViewModel;

use Blog\Form\PostForm;
use Blog\Entity\Post;

class FileController extends EntityUsingController
{
    /**
    * Index action
    *
    */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $files = $em->getRepository('Blog\Entity\File')->findBy(array(), array('date' => 'ASC'));

        return new ViewModel(array(
            'files' => $files,
        ));
    }

    /**
    * Edit action
    *
    */
    public function editAction()
    {
        $objectManager = $this->getEntityManager();

        // Create the form and inject the object manager
        $form = new \Blog\Form\FileEdit($objectManager);
        
        //Get a new entity with the id 
        $blogPost = $objectManager->find('Blog\Entity\File', (integer) $this->params('id'));
        
        $form->bind($file);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //Save changes
                $objectManager->flush();
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'post' => $blogPost,
        ));
    }

    /**
     * Create a blog post
     *
     */
    public function uploadAction()
    {
        // Create the form and inject the object manager
        $form = new \Blog\Form\FileUpload(
            $this->getEntityManager(),
            'multiple-file-upload'
        );

        if (!$this->request->isPost()) {
            return new ViewModel(array('form' => $form));
        }

        $filesData = $form->getRemappedFilesDataIfMultiple($this->request->getFiles()->toArray());

        if (!$form->getFileFieldset()->isMultiple()) {
            $filesData = array($filesData);
        }

        $errorMessages = $this->processFilesData($filesData, $form);

        return new ViewModel(array(
            'form' => $form,
            'errorMessages' => $errorMessages,
        ));
    }

    public function processFilesData($filesData, $form)
    {
        $errorMessages = array();
        foreach ($filesData as $fileData) {
            //$form = new \Blog\Form\FileUpload($objectManager, 'multiple-file-upload');
            $form->setData($fileData);
            if (!$this->isValidAndPersisted($form)) {
                $nameOfFileTriggeringError = $fileData[$form->getFileFieldset()->getName()][$form->getFileFieldset()->getFileInputName()];
                $errorMessages[$nameOfFileTriggeringError] = $form->getMessages();
            }
        }
        return $errorMessages;
    }

    public function isValidAndPersisted($form)
    {
        if (!$form->isValid()) {
            return false;
        }
        $file = $this->getHydratedFile($form);
        $this->persistFile($file);
        return true;
    }

    public function getHydratedFile($form)
    {
        $file = new \Blog\Entity\File();
        $form->bind($file);
        $file->setDate(new \DateTime());
        return $file;
    }

    public function persistFile(\Blog\Entity\File $file)
    {
        $this->getEntityManager()->persist($file);
        $this->getEntityManager()->flush();
    }

   /**
    * Delete action
    *
    */
    public function deleteAction()
    {
        $post = $this->getEntityManager()->getRepository('Blog\Entity\Post')->find($this->params('id'));

        if ($post) {
            $em = $this->getEntityManager();
            $em->remove($post);
            $em->flush();

            $this->flashMessenger()->addSuccessMessage('Post Deleted');
        }

        return $this->redirect()->toRoute('post');
    }
}
