<?php
namespace Blog\Controller;

class PostController extends \User\Controller\LoggedInController 
{
    protected $offset = null;
    protected $page   = null;

    protected $posts = null;

    /**
     * Index action
     *
     */
    public function indexAction()
    {
        return new \Zend\View\Model\ViewModel(array(
            'posts' => $this->getPosts(),
            'form'  => $this->getBulkForm(),
            'page'  => $this->getPageSelectorCurrentPage(),
        ));
    }

    public function getPageSelectorCurrentPage()
    {
        if (null === $this->page) {
            $paramPage = $this->params()->fromRoute('id');
            $this->page = ((null === $paramPage)? 0 : $paramPage);
        }
        return $this->page;
    }

    public function getPageSelectorOffset()
    {
        if (null === $this->offset) {
            $this->offset = $this->getPageSelectorCurrentPage() * $this->getPageSelectorLimit();
        }
        return $this->offset;
    }

    public function getPageSelectorLimit()
    {
        return 20;
    }

    public function getPosts()
    {
        if (null !== $this->posts) {
            return $this->posts;
        }
        $this->posts = $this->getEntityManager()->getRepository('Blog\Entity\Post')->findBy(
            array(
                'user' => $this->getUser()->getId(),
            ),
            array(
                'slug' => 'ASC',
            ),
            $this->getPageSelectorLimit(),
            $this->getPageSelectorOffset()
        );
        return $this->posts;
    }

    public function bulkAction()
    {
        if ($this->request->isPost()) {
            return $this->redirectToIndex();
        }

        $form = $this->getBulkForm(true);
        $form->setData($formData = $this->request->getPost());

        if (!$form->isValid()) {
            return $this->redirectToIndex();
        }

        $formValidData = $form->getData();
        $action = $formValidData['action'];
        $this->$action($formValidData['posts']);

        $this->flashMessenger()->addMessage($action . ' succeed');
        return $this->redirectToIndex();
    }

    public function linkTranslations(array $formPostsData)
    {
        $selectedPosts = array();
        foreach ($this->getPosts() as $post) {
            if (!in_array($post->getId(), $formPostsData)) continue;
            $selectedPosts[] = $post;
        }
        $translated = null;
        foreach ($selectedPosts as $post) {
            if (null !== $translated && $post->hasTranslated() && $translated !== $post->getTranslated()) {
                throw new \Exception('In the posts you selected, at least two are already a translation of different translated. If you pursue, one of both translated, will have to be deleted and all posts being a translation of the deleted translated will have to be updated, are you sure this is the behaviour you want? If so, implement it...');
            }
            if ($post->hasTranslated()) {
                $translated = $post->getTranslated();
            }
        }

        // Get new translation
        if (null === $translated) {
            $translated = $post->getTranslated();
        }

        $em = $this->getEntityManager();
        foreach ($selectedPosts as $post) {
            $post->setTranslated($translated);
            $em->persist($post);
        }
        $em->flush();
    }


    public function getBulkForm($populateMulticheck = false)
    {
        $bulkForm = new \Blog\Form\Post('bulk-action');

        if (!$populateMulticheck) {
            return $bulkForm;
        }

        $valueOptions = array();
        foreach ($this->getPosts() as $post) {
            $valueOptions[] = array('label' => '', 'value' => $post->getId());
        }
        $bulkForm->get('posts')->setValueOptions($valueOptions);
        return $bulkForm;
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

        if (!$blogPost->hasMedia()) {
            $medias = $objectManager->getRepository('Blog\Entity\Media')->findBy(array('slug' => 'symptom-thumbnail.jpg', 'locale' => $this->getLocale()));
            $blogPost->setMedia(current($medias));
        }

        $objectManager->persist($blogPost);
        $objectManager->flush();

        return $this->redirect()->toRoute('blog', array('controller' => 'post', 'action' => 'index', 'id' => null), true);
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

        return $this->redirectToIndex();
    }

    public function deletePosts(array $ids)
    {
        foreach ($ids as $id) {
            $this->deletePost($id);
        }
    }

    public function deletePost($id)
    {
        $post = $this->getEntityManager()->getRepository('Blog\Entity\Post')->find($id);
        if ($post) {
            $em = $this->getEntityManager();
            $em->remove($post);
            $em->flush();
        }
    }

    /**
    * Delete action
    *
    */
    public function deleteAction()
    {
        $this->deletePost($this->params('id'));
        return $this->redirectToIndex();
    }

    public function redirectToIndex()
    {
        $reuseMatchedParams = true;
        return $this->redirect()->toRoute('blog', array('controller' => 'post', 'action' => 'index'), $reuseMatchedParams);
    }
}
