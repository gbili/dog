<?php
namespace Blog\Controller;

class MediaController extends \Zend\Mvc\Controller\AbstractActionController
{

    /**
     * Index action
     */
    public function indexAction()
    {
        return new \Zend\View\Model\ViewModel(array(
            'messages' => $this->messenger()->getMessages(),
            'medias' => $this->getMedias(),
        ));
    }

    public function getMedias($id = null)
    {
        $em = $this->em();
        $criteria = array(
            'user' => $this->identity(),
        );

        if (null !== $id) {
            $criteria['id'] = $id;
        }

        $medias = $em->getRepository('Blog\Entity\Media')->findBy(
            $criteria, 
            array('date' => 'DESC')
        );

        return $medias;
    }

    /**
     * Edit action
     */
    public function editAction()
    {
        $em = $this->em();

        // Create the form and inject the object manager
        $form = new \Blog\Form\MediaEdit($em);
        
        //Get a new entity with the id 
        $medias = $this->getMedias($this->params('id'));

        if (empty($medias)) {
            throw new \Exception('There is no such media');
        }

        $media = current($medias);
        
        $form->bind($media);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //Save changes
                $em->flush();
                return $this->redirectToMediaView($media);
            }
        }

        return new \Zend\View\Model\ViewModel(array(
            'form' => $form,
            'entityId' => $media->getId(),
        ));
    }

    /**
     * Link media to a post 
     */
    public function linkAction()
    {
        $em = $this->em();

        // Create the form and inject the object manager
        $form = new \Blog\Form\MediaLink($em);
        
        //Get a new entity with the id 
        $medias = $this->getMedias($this->params('id'));

        if (empty($medias)) {
            throw new \Exception('There is no such media');
        }

        $media = current($medias);
        $form->bind($media);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //Save changes
                $em->flush();
            }
        }

        return new \Zend\View\Model\ViewModel(array(
            'form' => $form,
            'entityId' => $media->getId(),
        ));
    }

    /**
     * Link media to a post 
     */
    public function unlinkAction()
    {
        $em = $this->em();

        // Create the form and inject the object manager
        $form = new \Blog\Form\MediaLink($em);
        
        //Get a new entity with the id 
        $mediaId = (integer) $this->params('id');
        $postId  = (integer) $this->params('fourthparam');

        $media = $em->find('Blog\Entity\Media', $mediaId);
        $post  = $em->find('Blog\Entity\Post', $postId);

        if ($media && $post) {
            $media->removePost($post);
            $em->flush();

            $this->messenger()->addMessage('Media and post unlinked', 'success');
        }

        return $this->redirectToMediaLibrary();
    }

    public function uploadAction()
    {
        return $this->fileUploader();
    }

    /**
     * Create media from file id passed as route param or form
     */
    public function createAction()
    {
        $id = $this->params()->fromRoute('id');
        if (null === $id) {
            throw new \Exception('Need to create a media form where files can be selected as ids and send it to this action');
        }
        $this->mediaEntityCreator($this->em()->getRepository('Blog\Entity\File')->findById( (integer) $id));

        return $this->redirectToMediaLibrary();
    }

    public function redirectToMediaView(\Blog\Entity\Media $media)
    {
        return $this->redirect()->toRoute('blog_media_view', array(
            'action' => 'view',
            'slug' => $media->getSlug(),
        ), true);
    }

    public function redirectToMediaLibrary()
    {
        return $this->redirect()->toRoute('blog_media_route', array(
            'action'     => 'index', 
            'lang' => $this->locale(),
        ), false);
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        if (!$this->nonce()->isValid()) {
            $this->messenger()->addMessage(implode(', ', $this->nonce()->getLastValidator()->getMessages()), 'danger');
            return $this->redirectToMediaLibrary();
        }

        $media = $this->em()->getRepository('Blog\Entity\Media')->find($this->params()->fromRoute('id'));
        $genericMedia = current($this->em()->getRepository('Blog\Entity\Media')->findBy(array('slug' => 'symptom-thumbnail.jpg', 'locale' => $this->lang())));

        if (!$media) {
            $this->messenger()->addMessage('Media does not exist', 'danger');
            return $this->redirectToMediaLibrary();
        }

        $loggedInUser = $this->identity();
        if (!$media->isOwnedBy($loggedInUser) && !$loggedInUser->isAdmin()) {
            $this->messenger()->addMessage('You don\'t have the right to delete something that does not belong to you', 'danger');
            return $this->redirectToMediaLibrary();
        }

        $em = $this->em();
        $posts = $media->getPosts();

        $em->remove($media);

        foreach ($posts as $post) {
            $post->setMedia($genericMedia);
            $em->persist($post);
        }

        $em->flush();

        $this->messenger()->addMessage('Media Deleted', 'success');

        return $this->redirectToMediaLibrary();
    }

    public function viewAction()
    {   
        $slug = $this->params('slug');

        $queryBuilder = $this->em()->createQueryBuilder();
        $queryBuilder->select('m')
            ->from('Blog\Entity\Media', 'm')
            ->where('m.slug = ?1')
            ->setParameter(1, $slug); 
        $media = current($queryBuilder->getQuery()->getResult());

        return new \Zend\View\Model\ViewModel(array(
            'entity' => $media,
        ));
    }
}
