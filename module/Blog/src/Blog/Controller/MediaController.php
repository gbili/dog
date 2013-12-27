<?php
namespace Blog\Controller;

use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\View\Model\ViewModel;

class MediaController extends \User\Controller\LoggedInController 
{
    /**
    * Index action
    *
    */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $medias = $em->getRepository('Blog\Entity\Media')->findBy(array('locale' => $this->getLocale()), array('date' => 'DESC'));

        return new ViewModel(array(
            'medias' => $medias,
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
        $form = new \Blog\Form\MediaEdit($objectManager);
        
        //Get a new entity with the id 
        $media = $objectManager->find('Blog\Entity\Media', (integer) $this->params('id'));
        
        $form->bind($media);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //Save changes
                $objectManager->flush();
                return $this->redirectToMediaView($media);
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'entityId' => $media->getId(),
        ));
    }

    /**
     * Link media to a post 
     *
     */
    public function linkAction()
    {
        $objectManager = $this->getEntityManager();

        // Create the form and inject the object manager
        $form = new \Blog\Form\MediaLink($objectManager);
        
        //Get a new entity with the id 
        $media = $objectManager->find('Blog\Entity\Media', (integer) $this->params('id'));
        $form->bind($media);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //Save changes
                $objectManager->flush();
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'entityId' => $media->getId(),
        ));
    }

    /**
     * Link media to a post 
     *
     */
    public function unlinkAction()
    {
        $objectManager = $this->getEntityManager();

        // Create the form and inject the object manager
        $form = new \Blog\Form\MediaLink($objectManager);
        
        //Get a new entity with the id 
        $mediaId = (integer) $this->params('id');
        $postId  = (integer) $this->params('fourthparam');

        $media = $objectManager->find('Blog\Entity\Media', $mediaId);
        $post  = $objectManager->find('Blog\Entity\Post', $postId);

        if ($media && $post) {
            $media->removePost($post);
            $objectManager->flush();

            $this->flashMessenger()->addSuccessMessage('Media and post unlinked');
        }
        return $this->redirect()->toRoute('blog', array('controller' => 'media', 'action' => 'index'));
    }

    public function uploadAction()
    {
        $fileUploader = new \Blog\Service\FileEntityUploader();

        if (!$this->getRequest()->isPost()) {
            return new ViewModel(array(
                'messages' => array(),
                'form' => $fileUploader->getFormCopy(),
            ));
        }

        $fileUploader->setFileInputName('file')
                     ->setEntityManager($this->getEntityManager())
                     ->setRequest($this->getRequest());

        if (!$fileUploader->uploadFiles()) {
            if ($fileUploader->hasFiles()) {
                $this->createMedias($fileUploader->getFiles());
            }
            return new ViewModel(array(
                'messages' => $fileUploader->getMessages(),
                'form' => $fileUploader->getFormCopy(),
            ));
        }
        $this->createMedias($fileUploader->getFiles());

        return $this->redirect()->toRoute('blog', array('controller' => 'media', 'action' => 'index'));
    }

    public function createMedias(array $files)
    {
        $objectManager = $this->getEntityManager();
        $locale = $this->getLocale();
        foreach ($files as $file) {
            $media = new \Blog\Entity\Media();
            $basename = $file->getBasename();
            $media->setSlug($basename);
            $media->setAlt($basename);
            $media->setFile($file);
            $media->setLocale($locale);
            $media->setDate(new \DateTime());
            $objectManager->persist($media);
            $objectManager->flush();
        }
    }

    public function redirectToMediaView(\Blog\Entity\Media $media)
    {
        return $this->redirect()->toRoute('blog_media_view', array(
            'controller' => 'media',
            'action' => 'view',
            'slug' => $media->getSlug()
        ));
    }

    /**
    * Delete action
    *
    */
    public function deleteAction()
    {
        $media = $this->getEntityManager()->getRepository('Blog\Entity\Media')->find($this->params('id'));

        if ($media) {
            $em = $this->getEntityManager();
            $em->remove($media);
            $em->flush();

            $this->flashMessenger()->addSuccessMessage('Media Deleted');
        }
        return $this->redirect()->toRoute('blog', array('controller' => 'media', 'action' => 'index'));
    }

    public function viewAction()
    {   
        $slug = $this->params('slug');

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('m')
            ->from('Blog\Entity\Media', 'm')
            ->where('m.slug = ?1')
            ->setParameter(1, $slug); 
        $media = current($queryBuilder->getQuery()->getResult());

        return new ViewModel(array(
            'entity' => $media,
        ));
    }
}
