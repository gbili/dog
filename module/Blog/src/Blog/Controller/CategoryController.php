<?php
namespace Blog\Controller;

use Zend\View\Model\ViewModel;

use Blog\Form\CategoryForm;
use Blog\Entity\Category;

class CategoryController extends \User\Controller\LoggedInController
{
    /**
    *
    *
    */
    public function indexAction()
    {
        $em = $this->getEntityManager();

        $categories = $em->getRepository('Blog\Entity\Category')->findBy(array('locale' => $this->getLocale()), array('name' => 'ASC'));

        return new ViewModel(array('categories' => $categories,));
    }

    public function editAction()
    {
        $objectManager = $this->getEntityManager();

        // Create the form and inject the object manager
        $form = new \Blog\Form\CategoryEdit($this->getServiceLocator());
        
        //Get a new entity with the id 
        $category = $objectManager->find('Blog\Entity\Category', (integer) $this->params('id'));
        
        $form->bind($category);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //Save changes
                $category->setLocale($this->getLocale());
                $objectManager->persist($category);
                $objectManager->flush();
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'entityId' => $category->getId(),
        ));
    }

    public function createAction()
    {
        $objectManager = $this->getEntityManager();
        // Create the form and inject the object manager
        $form = new \Blog\Form\CategoryCreate($this->getServiceLocator());

        //Create a new, empty entity and bind it to the form
        $blogCategory = new \Blog\Entity\Category();
        $form->bind($blogCategory);

        if (!$this->request->isPost()) {
            return new ViewModel(array(
                'form' => $form,
                'entityId' => $blogCategory->getId(),
            ));
        }

        $form->setData($this->request->getPost());

        if (!$form->isValid()) {
            return new ViewModel(array(
                'form' => $form,
                'entityId' => $blogCategory->getId(),
            ));
        }

        $blogCategory->setLocale($this->getLocale());
        $objectManager->persist($blogCategory);
        $objectManager->flush();

        return $this->redirectToCategoriesList();

    }

    public function deleteAction()
    {
        $category = $this->getEntityManager()->getRepository('Blog\Entity\Category')->find($this->params('id'));

        if ($category) {
            $em = $this->getEntityManager();
            $em->remove($category);
            $em->flush();

            $this->flashMessenger()->addSuccessMessage('Category Deleted');
        }
        return $this->redirectToCategoriesList();
    }

    public function redirectToCategoriesList()
    {
        return $this->redirect()->toRoute(
            'blog', 
            array(
                'controller' => 'category', 
                'action' => 'index', 
            ), 
            true
        );
    }
}
