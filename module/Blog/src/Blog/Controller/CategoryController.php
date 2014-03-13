<?php
namespace Blog\Controller;

class CategoryController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     * Avoids recalculating it on every call to getCategoriesTreeAsFlatArray()
     */
    protected $categoriesTreeAsFlatArray = null;

    /**
     * Index action
     *
     */
    public function indexAction()
    {
        $categories = $this->getCategoriesTreeAsFlatArray();
        $paginator = $this->paginator();
        $paginator->setTotalItemsCount($this->repository()->getNestedTreeTotalCount());

        return new \Zend\View\Model\ViewModel(array(
            'user'       => $this->identity(),
            'categories' => $categories,
            'form'       => new \Blog\Form\CategoryBulk('bulk-action'),
            'paginator'  => $paginator,
        ));
    }

    public function bulkAction()
    {
        return $this->actionBulk('blog_category_route', array('action' => 'index'));
    }

    public function noncedeleteAction()
    {
        return $this->actionNonceDelete('blog_category_route', array('action' => 'index'));
    }

    /**
     * @note mentioned in bulk form as action value, therefor
     * called by actionBulk plugin
     */
    public function deleteCategories(array $categoriesIds)
    {
        return $this->deleteEntitiesByIds($categoriesIds);
    }

    /**
     * @note called by : 
     *     -actionNonceDelete plugin
     *     -deleteEntitiesByIds plugin
     */
    public function deleteEntity($category)
    {
        $em = $this->em();
        $em->remove($category);
        $em->flush();
    }

    /**
     * @note called by bulkForm plugin
     */
    public function getEntities()
    {
        return $this->getCategoriesTreeAsFlatArray();
    }

    public function linkTranslations(array $categoriesIds)
    {
        if (!$this->identity()->isAdmin()) {
            return $this->redirect()->toRoute('blog_category_route', array(), true);
        }

        $em         = $this->em();
        $repo       = $this->repository();
        $categories = $repo->getFromIds($categoriesIds);
        $translated = $repo->getNewOrUniqueReusedTranslated($categories);

        foreach ($categories as $category) {
            $category->setTranslated($translated);
            $em->persist($category);
        }
        $em->flush();
    }

    public function getCategoriesTreeAsFlatArray()
    {
        if (null === $this->categoriesTreeAsFlatArray) {
            $this->categoriesTreeAsFlatArray = $this->repository()->getTreeAsFlatArray();
        }
        return $this->categoriesTreeAsFlatArray;
    }

    /**
     * Edit action
     *
     */
    public function editAction()
    {
        $em = $this->em();
        // Create the form and inject the object manager
        $form = new \Blog\Form\CategoryEditor($this->getServiceLocator());

        //Create a new, empty entity and bind it to the form
        $category = $this->getEntityFromParamIdOrNew();

        $form->bind($category);

        if (!$this->request->isPost()) {
            return new \Zend\View\Model\ViewModel(array(
                'firstRendering' => true,
                'form' => $form,
                'entityId' => $category->getId(),
            ));
        }

        $form->setData($this->request->getPost());

        if (!$form->isValid()) {
            return new \Zend\View\Model\ViewModel(array(
                'firstRendering' => false,
                'form' => $form,
                'entityId' => $category->getId(),
            ));
        }

        if (!$category->hasLocale()) {
            $category->setLocale($this->locale());
        }
        if ($category->getLocale() !== $this->locale()) {
            throw new Exception\BadRequest('Cannot edit a category in a different editor locale than the category\'s locale, it would break parent relationship');
        }

        if (!$category->hasUser()) {
            $category->setUser($this->identity());
        }
        if (!$category->isOwnedBy($this->identity())) {
            throw new \Exception('Cannot edit a category that belongs to someone else');
        }

        $em->persist($category);
        $em->flush();

        return $this->redirect()->toRoute('blog_category_route', array('id' => null), true);
    }

    public function createAction()
    {
        return $this->editAction();
    }
}
