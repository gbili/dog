<?php
namespace Dogtore\Controller;

/**
 * Dogs are identifiable by their master's uniquename and their name
 * To get a dog, we get the user with uniquename, and try to find one
 * of its dogs that has the name
 *
 */
class DogController extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     * View uniquename user's name dog 
     * :uniquename and :dogname are enforced by route
     */
    public function viewAction()
    {
        $uniquename = $this->params()->fromRoute('uniquename');
        $dogName    = $this->params()->fromRoute('dogname', null);

        $dogs = $this->getDogs($uniquename, $dogName);

        $viewVars = array(
            'dogs'
        );
        return new \Zend\View\Model\ViewModel(compact($viewVars));
    }

    /**
     * Edit uniquename user's dog with name
     * :uniquename and :dogname are enforced by route
     */
    public function editAction()
    {
        return $this->editor();
    }

    /**
     * Create new dog for uniquename user's 
     * :uniquename is enforced by route
     */
    public function addAction()
    {
        return $this->editor();
    }

    /**
     * View uniquename user's dogs list
     * :uniquename is enforced by route
     */
    public function listuserdogsAction()
    {
        $user       = $this->identity();
        $uniquename = $this->params()->fromRoute('uniquename');
        $dogs       = $this->getDogs($uniquename);

        $viewVars = array(
            'user', 
            'dogs',
        );

        return new \Zend\View\Model\ViewModel(compact($viewVars));
    }

    /**
     * View logged in user dogs list
     */
    public function listmydogsAction()
    {
        $user       = $this->identity();
        $dogs       = $this->getDogs($user->getUniquename());

        $viewVars = array(
            'user', 
            'dogs',
        );

        return new \Zend\View\Model\ViewModel(compact($viewVars));
    }

    /**
     * Editor for dogs 
     * Uses route params :dogname
     * if :dogname is not provided then it creates a new
     * dog to edit
     */
    public function editor()
    {
        $dogName    = $this->params()->fromRoute('dogname', false);
        $user       = $this->identity();
        $em         = $this->em();
        $locale     = $this->locale();

        if (false !== $dogName) {
            $dogs = $em->getRepository('Dogtore\Entity\Dog')->findBy(array('owner' => $user, 'name' => $dogName));
        }

        if (empty($dogs)) {
            if (false !== $dogName) {
                $this->messenger()->addMessage('The requested dog does not exist', 'danger');
                return $this->getResponse()->setStatusCode(404);
            }
            $dogs = array(new \Dogtore\Entity\Dog());
        }

        $dog = current($dogs);

        $dogForm = new \Dogtore\Form\DogEditor($this->getServiceLocator());
        $dogForm->bind($dog);

        if (!$this->request->isPost()) {
            return new \Zend\View\Model\ViewModel(array(
                'form' => $dogForm,
                'messages' => $this->messenger()->getMessages(),
            ));
        }

        $httpPostData = $this->request->getPost();
        $dogForm->setData($httpPostData);

        if (!$dogForm->isValid()) {
            return new \Zend\View\Model\ViewModel(array(
                'form' => $dogForm,
                'messages' => $this->messenger()->getMessages(),
            ));
        }

        $dog->setLocale($locale); //of course! Dogs speak some language...
        $user->addDog($dog);
        
        if (!$dog->hasMedia()) {
            $media = $em->getRepository('Blog\Entity\Media')->findBySlug('profile-thumbnail.jpg');
            $dog->setMedia(current($media));
        }

        $em->persist($dog);
        $em->flush();

        return $this->redirect()->toRoute('dog_view_user_dog', array('uniquename' => (string) $user->getUniquename(), 'dogname' => $dog->getName()), true);
    }

    protected function getDogs($userUniquename, $dogName = null)
    {
        $req = new \Dogtore\Req\Dog();
        $conditions = [];

        $conditions[] = array('owner_uniquename' => array('=' => $userUniquename));
        if (null !== $dogName) {
            $conditions[] = array('dog_name' => array('=' => $dogName));
        }

        return $req->getDogs(((empty($conditions))? [] : ['and' => $conditions]));
    }
}
