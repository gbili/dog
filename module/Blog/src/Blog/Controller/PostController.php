<?php
namespace Blog\Controller;

class PostController extends \Zend\Mvc\Controller\AbstractActionController
{
    protected $posts = null;

    /**
     * Index action
     *
     */
    public function indexAction()
    {
        $paginator = $this->paginator();
        $paginator->setTotalItemsCount($this->repository()->getNestedTreeTotalCount());

        return new \Zend\View\Model\ViewModel(array(
            'posts' => $this->getEntities(),
            'form'  => $this->bulkForm(),
            'paginator' => $paginator,
        ));
    }

    public function getEntities()
    {
        if (null !== $this->posts) {
            return $this->posts;
        }
        $paginator = $this->paginator();
        $this->posts = $this->repository()->findBy(
            array('userdata' => $this->identity()->getData()),
            array('slug' => 'ASC'),
            $paginator->getItemsPerPage(),
            $paginator->getPageFirstItem()
        );
        return $this->posts;
    }

    public function bulkAction()
    {
        return $this->actionBulk('blog_post_route', array('action' => 'index'));
    }

    public function linkTranslations(array $formPostsData)
    {
        $selectedPosts = array();
        foreach ($this->getEntities() as $post) {
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

        $em = $this->em();
        foreach ($selectedPosts as $post) {
            $post->setTranslated($translated);
            $em->persist($post);
        }
        $em->flush();
    }

    /**
     * Edit action
     *
     */
    public function editAction()
    {
        $em           = $this->em();
        $blogPost     = $this->getEntityFromParamIdOrNew();

        $combinedForm = new \Blog\Form\PostEditor($this->getServiceLocator());
        $combinedForm->bind($blogPost);

        if ($blogPost->hasParent()) {
            $combinedForm->get('post')->get('parent')->setValue($blogPost->getParent()->getId());
            $combinedForm->get('post_parent')->get('slug')->setValue($blogPost->getParent()->getSlug());
        }

        if (!$this->request->isPost()) {
            return new \Zend\View\Model\ViewModel(array(
                'firstRendering' => true,
                'entityId' => $blogPost->getId(),
                'form' => $combinedForm,
            ));
        }

        $httpPostData = $this->request->getPost();
        $combinedForm->setData($httpPostData);

        if (!$combinedForm->isValid()) {
            return new \Zend\View\Model\ViewModel(array(
                'firstRendering' => false,
                'form' => $combinedForm,
                'entityId' => $blogPost->getId(),
            ));
        }

        $this->throwIfPostParentChildRelationsIsNotCompliant($blogPost);

        $blogPostData = $blogPost->getData();
        $blogPostData->setDate(new \DateTime());
        if (!$blogPostData->hasLocale()) {
            $blogPostData->setLocale($this->locale());
        }

        $em->persist($blogPostData);
        $em->flush();

        $blogPost->setUser($this->identity());

        if (!$blogPost->hasLocale()) {
            $blogPost->setLocale($this->locale());
        }

        if (!$blogPost->hasMedia()) {
            $medias = $em->getRepository('Blog\Entity\Media')->findBy(array('slug' => 'default-thumbnail.jpg'));
            if (empty($medias)) {
                throw new \Exception('The generic media does not exist');
            }
            $blogPost->setMedia(current($medias));
        }

        $em->persist($blogPost);
        $em->flush();

        return $this->redirect()->toRoute(null, array('controller' => 'blog_post_controller', 'action' => 'index', 'id' => null), true);
    }

    public function throwIfPostParentChildRelationsIsNotCompliant($post)
    {
        if (!$post->hasParent()) {
            return;
        }

        $scs = $this->getServiceLocator()->get('scs');

        if (!$scs->isAllowedParent(
            $post->getParent()->getCategoryslug(), 
            $post->getCategoryslug()
        )) {
            throw new \Exception('Cannot set parent with this category');
        }
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

        $em = $this->em();
        $postDatas = $em->getRepository('Blog\Entity\PostData')->findAll();
        if (empty($postDatas)) {
            $reuseMatchedParams = true;
            return $this->redirect()->toRoute(null, array('controller' => 'blog_post_controller', 'action' => 'create'), $reuseMatchedParams);
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

        $em->persist($blogPost);
        $em->flush();

        return $this->redirect()->toRoute(null, array('controller' => 'blog_post_controller', 'action' => 'index'), false);
    }

   /**
    * Delete action
    * @note: if you were thinking of renaming the actionNoncePlugin
    * to a real action name, thus removing the proxy, remember
    * that plugin needs parameters.
    */
    public function noncedeleteAction()
    {
        return $this->actionNonceDelete('blog_post_route', array('action' => 'index'));
    }

    /**
     * @note mentionned in bulk form and called
     * from actionBulk
     */
    public function deletePosts(array $ids)
    {
        return $this->deleteEntitiesByIds($ids);
    }

    /**
     * @note called by : 
     *     -actionNonceDelete plugin
     *     -deleteEntitiesByIds plugin
     */
    public function deleteEntity($post)
    {
        $em = $this->em();

        $postData = $post->getData();
        $postData->removePost($post);

        if (!$postData->hasPosts()) {
            $em->remove($postData);
        }

        $em->remove($post);
        $em->flush();
    }
}
