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
        $form = new \Blog\Form\PostEdit($objectManager);
        
        //Get a new entity with the id 
        $blogPost = $objectManager->find('Blog\Entity\Post', (integer) $this->params('id'));
        
        $form->bind($blogPost);

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
        $fieldsetName = 'multiple-file-upload';
        $form = new \Blog\Form\FileUpload('multiple-file-upload');

        if (!$this->request->isPost()) {
            return new ViewModel(array('form' => $form));
        }

        $filesData = $form->getRemappedFilesDataIfMultiple($this->request->getFiles()->toArray());

        if (!$form->getFileFieldset()->isMultiple()) {
            $filesData = array($filesData);
        }

        $errorMessages = array();
        foreach ($filesData as $fileData) {
            $form->setData($fileData);
            if (!$this->isValidAndPersisted($form)) {
                $nameOfFileTriggeringError = $fileData[$form->getFileFieldset()->getName()][$form->getFileFieldset()->getFileInputName()];
                $errorMessages[$nameOfFileTriggeringError] = $form->getMessages();
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'errorMessages' => $errorMessages,
        ));
    }

    public function isValidAndPersisted($form)
    {
        if (!$form->isValid()) {
            return false;
        }
        $file = $this->getHydratedFile($form->getData());
        $this->persistFile($file);
        return true;
    }

    public function getHydratedFile(array $formData)
    {
        $file = new \Blog\Entity\File();
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
