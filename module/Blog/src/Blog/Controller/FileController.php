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
                $file->move($file->getName());
                $objectManager->flush();
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'entity' => $file,
        ));
    }

    public function uploadAction()
    {
        $formName = 'file-form';
        $form = new \Blog\Form\Html5MultiUpload($formName);
        $file = new \Blog\Entity\File();

        $messages = array();

        if ($this->getRequest()->isPost()) {
            $data = array_merge_recursive(
                $this->getRequest()->getPost()->toArray(),
                $this->getRequest()->getFiles()->toArray()
            );

            $messages = $this->tryToUploadOneFileAtATime($formName, 'file', $data);

            if ($this->areAllFilesUploaded($messages)) {
                return $this->redirect()->toRoute('blog', array('controller' => 'file', 'action' => 'index'));
            }
        }

        return new ViewModel(array(
            'messages' => $messages,
            'form' => $form,
            'entity' => $file,
        ));
    }

    public function areAllFilesUploaded(array $messages)
    {
        foreach ($messages as $message) {
            if (!isset($message['success'])) {
                return false; 
            }
        }
        return true;
    }

    public function tryToUploadOneFileAtATime($formName, $fileInputName, array $data)
    {
        $messages = array();
        foreach ($data[$fileInputName] as $fileData) {
            $singleData = array($fileInputName => $fileData);
            $fileName = $fileData['name'];
            $singleFileFormData = $data;
            $singleFileFormData[$fileInputName] = $fileData;
            $messages[$fileName] = $this->uploadOneFile($formName, $fileInputName, $singleFileFormData);
        }
        return $messages;
    }

    public function uploadOneFile($formName, $fileInputName, array $data)
    {
        $form = new \Blog\Form\Html5MultiUpload($formName);
        $form->setData($data);
        if (!$form->isValid()) {
            return $form->getMessages();
        }
        //array('otherinput'=>'inputval', 
        //      'file' => array(
        //           name=>filename.jpg, 
        //           tmp_upload=>'/something/asdfasdf/LKGO'
        //           ...
        //       )
        //       otherinput ...
        //);
        $formData = $form->getData();
        
        //array(
        //    name=>filename.jpg,
        //    tmp_upload=>'/something/asdfasdf/LKGO'
        //    ...
        // );
        $fileData = $formData[$fileInputName];
        $this->saveFile($fileData);
        return array('success' => 'File Uploaded');
    }

    public function saveFile(array $fileData)
    {
        $file = $this->getHydratedFile($fileData);
        $this->persistFile($file);
    }

    public function getHydratedFile($fileData)
    {
        $persistableData = array_intersect_key($fileData, array_flip(array('name', 'type', 'date', 'tmp_name', 'size')));
        $persistableData['date'] = new \DateTime();

        $file = new \Blog\Entity\File();
        $file->hydrateWithFormData($persistableData);
        if ($file->getName() !== $file->getBasename()) {
            $file->move($file->getName());
        }
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
