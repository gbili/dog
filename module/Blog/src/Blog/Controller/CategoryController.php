<?php
namespace Blog\Controller;

class CategoryController extends \Zend\Mvc\Controller\AbstractActionController
{
    protected $categoriesTreeAsFlatArray = null;
    protected $categoryRepository = null;

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
        if (!$this->request->isPost()) {
            return $this->redirectToCategoriesList();
        }

        $form = new \Blog\Form\CategoryBulk('bulk-action');
        $form->hydrateValueOptions($this->getCategoriesTreeAsFlatArray());

        $form->setData($formData = $this->request->getPost());

        if (!$form->isValid()) {
            return $this->redirectToCategoriesList();
        }

        $formValidData = $form->getData();
        $action = $form->getSelectedAction();

        $this->$action($formValidData['categories']);

        $this->flashMessenger()->addMessage($action . ' succeed');
        return $this->redirectToCategoriesList();
    }

    public function linkTranslations(array $categoriesIds)
    {
        //translations is limited to admin
        if (!$this->identity()->isAdmin()) {
            $this->redirectToCategoriesList();
        }

        $repo = $this->em()->getRepository('Blog\Entity\Category');
        $categories = $repo->getFromIds($categoriesIds);
        $translated = $repo->getNewOrUniqueReusedTranslated($categories);

        $em = $this->em();
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

    public function editAction()
    {
        $objectManager = $this->em();

        // Create the form and inject the object manager
        $form = new \Blog\Form\CategoryEdit($this->getServiceLocator());
        
        //Get a new entity with the id 
        $category = $objectManager->find('Blog\Entity\Category', (integer) $this->params('id'));
        
        //TODO should the user own the category to be able to edit it? add an owner to the category and control that

        if (empty($category)) {
            throw new Exception\BadRequest('There is no category with that id. Please use links, dont try to be too creative');
        }

        if ($category->hasLocale() && $category->getLocale() !== $this->locale()) {
            throw new Exception\BadRequest('Cannot edit a category in a different editor locale than the category\'s locale, it would break parent relationship');
        }

        $form->bind($category);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //Save changes
                if (!$category->hasLocale()) {
                    $category->setLocale($this->locale());
                }
                $objectManager->persist($category);
                $objectManager->flush();
            }
        }

        return new \Zend\View\Model\ViewModel(array(
            'form' => $form,
            'entityId' => $category->getId(),
        ));
    }

    public function createAction()
    {
        $objectManager = $this->em();
        // Create the form and inject the object manager
        $form = new \Blog\Form\CategoryCreate($this->getServiceLocator());

        //Create a new, empty entity and bind it to the form
        $blogCategory = new \Blog\Entity\Category();
        $form->bind($blogCategory);

        if (!$this->request->isPost()) {
            return new \Zend\View\Model\ViewModel(array(
                'firstRendering' => true,
                'form' => $form,
                'entityId' => $blogCategory->getId(),
            ));
        }

        $form->setData($this->request->getPost());

        if (!$form->isValid()) {
            return new \Zend\View\Model\ViewModel(array(
                'firstRendering' => false,
                'form' => $form,
                'entityId' => $blogCategory->getId(),
            ));
        }

        $blogCategory->setLocale($this->locale());
        $objectManager->persist($blogCategory);
        $objectManager->flush();

        return $this->redirectToCategoriesList();

    }

    public function deleteAction()
    {
        $this->nonce()->setNonceParamName('fourthparam');
        if (!$this->nonce()->isValid()) {
            throw new \Exception('500 access denied');
        }
        $category = $this->em()->getRepository('Blog\Entity\Category')->find($this->params('id'));

        if ($category) {
            $em = $this->em();
            $em->remove($category);
            $em->flush();

            $this->flashMessenger()->addSuccessMessage('Category Deleted');
        }
        return $this->redirectToCategoriesList($overrideParams);
    }

    public function redirectToCategoriesList(array $overrideParams=array())
    {
        $params = array(
            'action' => 'index', 
        );
        if (!empty($overrideParams)) {
            $params = array_merge($params, $overrideParams);
        }
        return $this->redirect()->toRoute(null, $params, true);
    }
}
