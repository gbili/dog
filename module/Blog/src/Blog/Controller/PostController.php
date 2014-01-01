<?php
namespace Blog\Controller;

class PostController extends \User\Controller\LoggedInController 
{
    /**
     * Index action
     *
     */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $posts = $em->getRepository('Blog\Entity\Post')->findBy(
            array(
                'user' => $this->getUser()->getId(),
                'locale' => $this->getLocale(),
            ), 
            array('slug' => 'ASC')
        );

        return new \Zend\View\Model\ViewModel(array(
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

        //Create a new, empty entity and bind it to the form
        $blogPost = $this->getBlogPost();
        
        // Create the form and inject the object manager
        $combinedForm = new \Blog\Form\PostEditor($this->getServiceLocator());
        $combinedForm->bind($blogPost);

        if (!$this->request->isPost()) {
            return new \Zend\View\Model\ViewModel(array(
                'entityId' => $blogPost->getId(),
                'form' => $combinedForm,
            ));
        }

        $httpPostData = $this->request->getPost();
        $combinedForm->setData($httpPostData);

        if (!$combinedForm->isValid()) {
            return new \Zend\View\Model\ViewModel(array(
                'form' => $combinedForm,
                'entityId' => $blogPost->getId(),
            ));
        }

        $blogPostData = $blogPost->getData();
        $blogPostData->setDate(new \DateTime());
        if (!$blogPostData->hasLocale()) {
            $blogPostData->setLocale($this->getLocale());
        }

        $objectManager->persist($blogPostData);
        $objectManager->flush();

        $blogPost->setUser($this->getUser());

        $objectManager->persist($blogPost);
        $objectManager->flush();

        $reuseMatchedParams = true;
        return $this->redirect()->toRoute('blog', array('controller' => 'post', 'action' => 'index'), $reuseMatchedParams);
    }

    public function badRequest($type)
    {
        $reuseMatchedParams = true;
        $this->redirect()->toRoute('auth_logout', array(), $reuseMatchedParams);
    }

    public function getBlogPost()
    {
        $objectManager = $this->getEntityManager();
        $blogPostId = $this->params()->fromRoute('id');
        $blogPost = null;

        if (null !== $blogPostId) {
            $blogPost = $objectManager->find('Blog\Entity\Post', (integer) $blogPostId);
        }

        if (null === $blogPost) {
            return new \Blog\Entity\Post();
        }

        return $blogPost;
    }

    /**
     * Create a blog post
     *
     */
    public function createAction()
    {
        return $this->editAction();
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
            $reuseMatchedParams = true;
            return $this->redirect()->toRoute('blog', array('controller' => 'post', 'action' => 'create'), $reuseMatchedParams);
        }

        // TODO all this is wrong find a way to ignore the postdata fieldset and add a hidden element with the postdata id
        throw new \Exception('TODO all this is wrong find a way to ignore the postdata fieldset and add a hidden element with the postdata id
');
        $postForm     = new \Blog\Form\PostCreate($this->getServiceLocator());
        //Create a new, empty entity and bind it to the form
        $blogPost = new \Blog\Entity\Post();

        if (!$this->request->isPost()) {
            return new \Zend\View\Model\ViewModel(array(
                'entityId' => $blogPost->getId(),
                'form' => $postForm,
            ));
        }

        $postForm->bind($blogPost);
        $postForm->setData($blogPost);

        if (!$postForm->isValid()) {
            return new \Zend\View\Model\ViewModel(array(
                'form' => $postForm,
                'entityId' => $blogPost->getId(),
            ));
        }

        $objectManager->persist($blogPost);
        $objectManager->flush();

        $reuseMatchedParams = true;
        return $this->redirect()->toRoute('blog', array('controller' => 'post', 'action' => 'index'), $reuseMatchedParams);
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

        $reuseMatchedParams = true;
        return $this->redirect()->toRoute('blog', array('controller' => 'post', 'action' => 'index'), $reuseMatchedParams);
    }
}
