<?php
namespace Blog\Controller;

use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\View\Model\ViewModel;

class MediaController extends EntityUsingController
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
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'entity' => $media,
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

        var_dump($media->getPosts()->getKeys());
        return new ViewModel(array(
            'form' => $form,
            'entity' => $media,
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
        $objectManager = $this->getEntityManager();

        // Create the form and inject the object manager
        $form = new \Blog\Form\MediaCreate($objectManager);

        //Create a new, empty entity and bind it to the form
        $media = new \Blog\Entity\Media();
        $form->bind($media);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $media->setDate(new \DateTime());
                $objectManager->persist($media);
                $objectManager->flush();
            }
        }

        return new ViewModel(array(
            'entity' => $media,
            'form' => $form,
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
}
