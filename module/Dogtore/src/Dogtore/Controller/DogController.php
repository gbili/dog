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
        $dogname = $this->routeParamTransform('dogname_underscored')->underscoreToSpace();

        $dogs = $this->getDogs($uniquename, $dogname);

        $viewVars = array(
            'dogs'
        );
        return new \Zend\View\Model\ViewModel(compact($viewVars));
    }

    /**
     * Upload images
     */
    public function uploadAction()
    {
        return $this->fileUploader();
    }

    /**
     * Edit uniquename user's dog with name
     * :uniquename and :dogname_underscored are enforced by route
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
     * Uses route params :dogname_underscored
     * if :dogname_underscored is not provided then it creates a new
     * dog to edit
     */
    public function editor()
    {
        $dogname = $this->routeParamTransform('dogname_underscored')->underscoreToSpace();
        $user    = $this->identity();
        $em      = $this->em();
        $locale  = $this->locale();

        if ($dogname) {
            $dogname = preg_replace();
            $dogs = $em->getRepository('Dogtore\Entity\Dog')->findBy(array('user' => $user, 'name' => $dogname));
        }

        if (empty($dogs)) {
            if (false !== $dogname) {
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
                'firstRendering' => true,
                'messages' => $this->messenger()->getMessages(),
            ));
        }

        $httpPostData = $this->request->getPost();
        $dogForm->setData($httpPostData);

        if (!$dogForm->isValid()) {
            return new \Zend\View\Model\ViewModel(array(
                'form' => $dogForm,
                'firstRendering' => false,
                'messages' => $this->messenger()->getMessages(),
            ));
        }

        $dog->setLocale($locale); //of course! Dogs speak some language...
        $user->addDog($dog);
        
        if (!$dog->hasMedia()) {
            //TODO fix this, make sure there is allways the default media.
            // should it be a non displayable media?
            $media = $this->repository('Blog\Entity\Media')->getDefaultMedia($dog);
            $dog->setMedia($media);
        }

        $em->persist($dog);
        $em->flush();

        $dognameUnder = $this->string()->spaceToUnderscore($dog->getName());
        return $this->redirect()->toRoute('dog_view_user_dog', array('uniquename' => (string) $user->getUniquename(), 'dogname_underscored' => $dognameUnder), true);
    }

    protected function getDogs($userUniquename, $dogname = null)
    {
        $req = new \Dogtore\Req\Dog();
        $conditions = [];

        $conditions[] = array('user_uniquename' => array('=' => $userUniquename));
        if (null !== $dogname) {
            $conditions[] = array('dog_name' => array('=' => $dogname));
        }

        return $req->getDogs(((empty($conditions))? [] : ['and' => $conditions]));
    }
}
