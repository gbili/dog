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
        return new \Zend\View\Model\ViewModel(array(
            'files' => $this->getEntities(),
            'form'  => new \Blog\Form\FileBulk('bulk-action'),
        ));
    }

    public function bulkAction()
    {
        return $this->actionBulk('blog_file_route', array('action' => 'index'));
    }

    /**
     * @note called by bulkForm plugin
     */
    public function getEntities()
    {
        return $this->repository()->findBy(array(), array('date' => 'ASC'));
    }

    /**
     * @note mentioned in bulk form and called actionBulk plugin
     */
    public function deleteFiles(array $fileIds)
    {
        if (!$this->identity()->isAdmin()) {
            throw new \Exception('Access denied, cannot delete files if not admin');
        }
        $this->deleteEntitiesByIds($fileIds);
    }


    /**
     * @note called by : 
     *     -actionNonceDelete plugin
     *     -deleteEntitiesByIds plugin
     */
    public function deleteEntity($file)
    {
        if (!$file->delete()) {
            throw new \Exception('Could not delete the actual file');
        }
        $em = $this->em();
        $defaultFileReplacingUnlinkedMediasFile = $this->repository('Blog\Entity\Media')->getDefaultFile();
        $file->unlinkMedias($file->getMedias(), $defaultFileReplacingUnlinkedMediasFile);
        $em->remove($file);
        $em->flush();
    }

    /**
     * Edit action
     *
     */
    public function editAction()
    {
        $em = $this->em();

        // Create the form and inject the object manager
        $form = new \Blog\Form\FileEdit($em);
        
        //Get a new entity with the id 
        $file = $em->find('Blog\Entity\File', (integer) $this->params('id'));
        
        $form->bind($file);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //Save changes
                $file->move($file->getName());
                $em->flush();
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
    * Delete file from get request non-idempotent get violation
    */
    public function noncedeleteAction()
    {
        return $this->actionNonceDelete('blog_file_route', array('action' => 'index'));
    }
}
