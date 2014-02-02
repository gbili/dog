<?php
namespace Blog\Controller;

class MediaController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $em = $this->em();
        $medias = $em->getRepository('Blog\Entity\Media')->findBy(array('locale' => $this->locale()), array('date' => 'DESC'));

        return new \Zend\View\Model\ViewModel(array(
            'medias' => $medias,
        ));
    }

    /**
     * Edit action
     */
    public function editAction()
    {
        $objectManager = $this->em();

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
        $objectManager = $this->em();

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
        $objectManager = $this->em();

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

        return $this->redirectToMediaLibrary();
    }

    public function uploadAction()
    {
        $config = $this->getServiceLocator()->get('Config');
        $fileUploaderConfig = $config['file_uploader']['media_controller'];
        return $this->fileUploader($fileUploaderConfig);
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
        ), true);
    }

    public function redirectToMediaLibrary()
    {
        return $this->redirect()->toRoute(null, array(
            'action'     => 'index', 
        ), true);
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        $media = $this->em()->getRepository('Blog\Entity\Media')->find($this->params()->fromRoute('id'));

        if ($media) {
            $em = $this->em();
            $em->remove($media);
            $em->flush();

            $this->flashMessenger()->addSuccessMessage('Media Deleted');
        }

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
