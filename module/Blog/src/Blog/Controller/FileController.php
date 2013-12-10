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
        $file = $objectManager->find('Blog\Entity\File', (integer) $this->params('id'));
        
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
            'entity' => $file,
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
            'multiple-file-upload'
        );

        $errorMessages = array();
        if (!$this->request->isPost()) {
            return new ViewModel(array(
                'form' => $form,
                'errorMessages' => $errorMessages,
            ));
        }

        $filesData = $form->getRemappedFilesDataIfMultiple($this->request->getFiles()->toArray());

        if (!$form->getFileFieldset()->isMultiple()) {
            $filesData = array($filesData);
        }

        $errorMessages = $this->processFilesData($filesData, $form);
        if (!empty($errorMessages)) {
            return new ViewModel(array(
                'form' => $form,
                'errorMessages' => $errorMessages,
            ));
        }
        return $this->redirect()->toRoute('blog', array('controller' => 'file', 'action' => 'index'));
    }

    public function processFilesData($filesData, $form)
    {
        $errorMessages = array();
        foreach ($filesData as $fileData) {
            $form = new \Blog\Form\FileUpload('multiple-file-upload');
            $form->setData($fileData);
            if (!$this->isValidAndPersisted($form)) {
                $fileName = $fileData[$form->getFileFieldset()->getName()][$form->getFileFieldset()->getFileInputName()]['name'];
                $errors = $form->getMessages();
                $unwrappedErrors = $errors[$form->getFileFieldset()->getName()][$form->getFileFieldset()->getFileInputName()];
                $errorMessages[$fileName] = $unwrappedErrors;
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
        $formData = $form->getData();
        $formData = $formData[$form->getFileFieldset()->getName()][$form->getFileFieldset()->getFileInputName()];
        $persistableData = array_intersect_key($formData, array_flip(array('name', 'type', 'date', 'tmp_name', 'size')));
        $persistableData['date'] = new \DateTime();

        $file = new \Blog\Entity\File();
        $file->hydrate($persistableData);
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
        $file = $this->getEntityManager()->getRepository('Blog\Entity\File')->find($this->params('id'));
        if ($file && $file->delete()) {
            $em = $this->getEntityManager();
            $em->remove($file);
            $em->flush();
            $this->flashMessenger()->addSuccessMessage('File Deleted');
        }

        return $this->redirect()->toRoute('blog', array('controller' => 'file', 'action' => 'index'));
    }
}
