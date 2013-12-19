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
        $medias = $em->getRepository('Blog\Entity\Media')->findBy(array(), array('slug' => 'ASC'));

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
    
    /**
     * Create a blog media
     *
     */
    public function createAction()
    {
        $messages = array();
        $objectManager = $this->getEntityManager();

        $files = $this->getEntityManager()->getRepository('Blog\Entity\File')->findAll();
        //If no files, redirect to file uploads
        if (empty($files)) {
            return $this->redirect()->toRoute('blog', array('controller' => 'file', 'action' => 'upload'));
        }
        
        // Create the form and inject the object manager
        $form = new \Blog\Form\MediaCreate($objectManager);

        //If file needs to be preselected in the dropdown
        $fileId = $this->params('id');
        if (null !== $fileId) {
            $files = $objectManager->getRepository('Blog\Entity\File')->findById((integer) $fileId);
            if (!is_array($files)) {
                $fileId = null;
                $messages[] = array('danger' => 'The specified id, does not exist');
            } else {
                current($form->getFieldsets())->turnFileSelectorIntoHidden();
            }
        }

        //Create a new, empty entity and bind it to the form
        $media = new \Blog\Entity\Media();
        $form->bind($media);

        if ($this->request->isPost()) {
            $postData = $this->request->getPost();
            if (null !== $fileId) {
                $postData->media['file'] = $fileId;
            }
            $form->setData($postData);
            
            if ($form->isValid()) {
                $media->setDate(new \DateTime());
                $objectManager->persist($media);
                $objectManager->flush();
                return $this->redirectToMediaView($media);
            }
        }

        return new ViewModel(array(
            'entityId' => $fileId,
            'messages' => $messages,
            'form' => $form,
        ));
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
