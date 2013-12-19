<?php
namespace Blog\Controller;

use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\View\Model\ViewModel;

use Blog\Form\PostForm;
use Blog\Entity\Post;

class PostController extends \User\Controller\LoggedInController 
{

    /**
    * Index action
    *
    */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $posts = $em->getRepository('Blog\Entity\Post')->findBy(array('user' => $this->getUser()->getId()), array('slug' => 'ASC'));

        return new ViewModel(array(
            'posts' => $posts,
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

        if (empty($blogPost)) {
            return $this->badRequest('NotExists');
        }

        if ($blogPost->getUser() !== $this->getUser()) {
            return $this->badRequest('NotMine');
        }
        
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
            'entityId' => $blogPost->getId(),
        ));
    }

    public function badRequest($type)
    {
        $this->redirect()->toRoute('auth_logout');
    }

    /**
     * Create a blog post
     *
     */
    public function createAction()
    {

        $objectManager = $this->getEntityManager();
        // Create the form and inject the object manager
        $combinedForm = new \Blog\Form\PostAndPostDataCombinedCreate($objectManager);

        //Create a new, empty entity and bind it to the form
        $blogPostData = new \Blog\Entity\PostData();
        $blogPost = new \Blog\Entity\Post();

        if (!$this->request->isPost()) {
            return new ViewModel(array(
                'entityId' => $blogPost->getId(),
                'form' => $combinedForm,
            ));
        }

        $httpPostData = $this->request->getPost();
        $combinedForm->setData($httpPostData);

        if (!$combinedForm->isValid()) {
            return new ViewModel(array(
                'form' => $combinedForm,
                'entityId' => $blogPost->getId(),
            ));
        }

        $postDataForm = new \Blog\Form\PostDataCreate($objectManager);
        $postDataForm->bind($blogPostData);
        $postDataForm->setData($httpPostData);

        if ($postDataForm->isValid()) {
            $blogPostData->setDate(new \DateTime());
            $objectManager->persist($blogPostData);
            $objectManager->flush();
        }

        $postForm     = new \Blog\Form\PostCreate($objectManager);
        $postForm->bind($blogPost);

        $postForm->setData($httpPostData);

        if ($postForm->isValid()) {
            $blogPost->setData($blogPostData);
            $blogPost->setUser($this->getUser());
            $objectManager->persist($blogPost);
            $objectManager->flush();
            return $this->redirect()->toRoute('blog', array('controller' => 'post', 'action' => 'index'));
        }
    }

    /**
     * Create a blog post
     *
     */
    public function linkAction()
    {

        $objectManager = $this->getEntityManager();
        $postDatas = $objectManager->getRepository('Blog\Entity\PostData')->findAll();
        if (empty($postDatas)) {
            return $this->redirect()->toRoute('blog', array('controller' => 'post', 'action' => 'create'));
        }

        // Create the form and inject the object manager
        $postForm     = new \Blog\Form\PostCreate($objectManager);
        //Create a new, empty entity and bind it to the form
        $blogPost = new \Blog\Entity\Post();

        if (!$this->request->isPost()) {
            return new ViewModel(array(
                'entityId' => $blogPost->getId(),
                'form' => $postForm,
            ));
        }

        $postForm->bind($blogPost);
        $postForm->setData($blogPost);

        if (!$postForm->isValid()) {
            return new ViewModel(array(
                'form' => $postForm,
                'entityId' => $blogPost->getId(),
            ));
        }

        $objectManager->persist($blogPost);
        $objectManager->flush();

        return $this->redirect()->toRoute('blog', array('controller' => 'post', 'action' => 'index'));
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

        return $this->redirect()->toRoute('blog', array('controller' => 'post', 'action' => 'index'));
    }
}
