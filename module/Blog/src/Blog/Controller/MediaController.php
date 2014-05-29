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
            'userdata' => $this->identity()->getData(),
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
        $postId  = (integer) $this->params('post_id');

        $media = $em->find('Blog\Entity\Media', $mediaId);
        $post  = $em->find('Blog\Entity\Post', $postId);

        if ($media && $post) {
            $media->removePost($post);
            $em->flush();

            $this->messenger()->addMessage('Media and post unlinked', 'success');
        }

        return $this->redirect()->toRoute('blog_media_route', array('action'=>'index', 'lang'=>$this->locale()), false);
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

        return $this->redirect()->toRoute('blog_media_route', array('action'=>'index', 'lang'=>$this->locale()), false);
 
    }

    public function redirectToMediaView(\Blog\Entity\Media $media)
    {
        return $this->redirect()->toRoute('blog_media_view', array(
            'action' => 'view',
            'slug' => $media->getSlug(),
        ), true);
    }

    /**
     * Delete action
     */
    public function noncedeleteAction()
    {
        return $this->actionNonceDelete('blog_media_route', array('action' => 'index'));
    }

    public function deleteEntity($media)
    {
        $em = $this->em();

        $media->removeAllMetadatas();
        $this->giveBackMediaDependenciesToDefaultMedia($media);

        $em->remove($media);
        $em->flush();
    }

    /**
     * When media is removed we need to change each dependant's media
     * otherwise deletion is not allowed, or it will cascade
     */
    protected function giveBackMediaDependenciesToDefaultMedia($media)
    {
        $em = $this->em();
        $genericMedia = current($this->em()->getRepository('Blog\Entity\Media')->findBy(array('slug' => 'default-thumbnail.jpg')));
        if (!$genericMedia) {
            throw new \Exception('Missing generic media');
        }

        if ($media === $genericMedia) {
            throw new \Exception('You are trying to delete the generic media. If we did so there would be no media to give back dependencies to. Thereafter all dependencies would be deleted.');
        }

        $mediaDependingPropertiesUCFirstNames = array('Dog', 'Metadata', 'Profile', 'Post');
        foreach ($mediaDependingPropertiesUCFirstNames as $dependantName) {
            $dependants = $media->{"get{$dependantName}s"}();
            foreach ($dependants as $dependant) {
                $dependant->setMedia($genericMedia);
                $em->persist($dependant);
            }
        }
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
