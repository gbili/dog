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

        $categories = $em->getRepository('Blog\Entity\Category')->findBy(array(), array('name' => 'ASC'));

        return new ViewModel(array('categories' => $categories,));
    }

    public function editAction()
    {
        $objectManager = $this->getEntityManager();

        // Create the form and inject the object manager
        $form = new \Blog\Form\CategoryEdit($objectManager);
        
        //Get a new entity with the id 
        $category = $objectManager->find('Blog\Entity\Category', (integer) $this->params('id'));
        
        $form->bind($category);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                //Save changes
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
        $form = new \Blog\Form\CategoryCreate($objectManager);

        //Create a new, empty entity and bind it to the form
        $blogCategory = new \Blog\Entity\Category();
        $form->bind($blogCategory);

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $objectManager->persist($blogCategory);
                $objectManager->flush();
            }
        }

        return new ViewModel(array(
            'form' => $form,
            'entityId' => $blogCategory->getId(),
        ));
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

        return $this->redirect()->toRoute('category');
    }
}
