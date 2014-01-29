<?php
namespace User\Controller;

class ProfileController extends \Zend\Mvc\Controller\AbstractActionController
{
    protected $paramsUniquename;
    protected $paramsUniquenameUser;

    /**
     * Access logged in user profile
     */
    public function privateAction()
    {
        $user = $this->identity();
        if (!$user->hasProfile()) {
            return $this->redirect()->toRoute('profile_edit', array('uniquename' => $user->getUniquename()), true);
        }
        return $this->displayUserProfile($user);
    }

    public function publicAction()
    {
        $user = $this->getParamsUniquenameUser();
        if (!$user->hasProfile()) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        return $this->displayUserProfile($user);
    }

    protected function displayUserProfile(\User\Entity\User $user)
    {
        $profile = $user->getProfile();        
        $media = $profile->getMedia();

        $messages = $this->messenger()->getMessages();

        $viewVars = array(
            'profile', 
            'media', 
            'user', 
            'messages',
        );

        return new \Zend\View\Model\ViewModel(compact($viewVars));
    }

    public function getParamsUniquename()
    {
        if (null === $this->paramsUniquename) {
            $this->paramsUniquename = $this->params()->fromRoute('uniquename');
        }
        return $this->paramsUniquename;
    }

    public function issetParamsUniquename()
    {
        return false !== $this->getParamsUniquename();
    }

    public function isParamsUniquenameSameAsLoggedInUser()
    {
        $loggedInUser = $this->identity();
        if (false === $loggedInUser) {
            throw new \Exception('not logged in');
        }
        if (!$this->issetParamsUniquename()) {
            throw new \Exception('no params uniquename');
        }
        return $this->getParamsUniquename() === $loggedInUser->getUniquename();
    }

    public function isParamsUniquenameExistingUser()
    {
        if (null === $this->paramsUniquenameUser) {
            $users = $this->em()->getRepository('User\Entity\User')->findByUniquename($this->getParamsUniquename());
            if (!empty($users)) {
                 $this->paramsUniquenameUser = current($users);
            }
        }
        return $this->paramsUniquenameUser instanceof \User\Entity\User;
    }

    public function getParamsUniquenameUser()
    {
        $paramsUniquename = $this->getParamsUniquename();
        if (!$paramsUniquename) {
            throw new \Exception('uniquename route param not set');
        }

        if ($paramsUniquename === $this->identity()->getUniquename()) {
            return $this->paramsUniquenameUser = $this->identity();
        }
        if (!$this->isParamsUniquenameExistingUser()) {
            throw new \Exception('uniquename route param not exist');
        }
        return $this->paramsUniquenameUser;
    }

    /**
     * Show the profile available to friends
     *
     */
    public function friendAction()
    {

    }

    /**
     *
     */
    public function listAction()
    {
        $profiles = $this->em()->getRepository('User\Entity\Profile')->findAll();

        return new \Zend\View\Model\ViewModel(array(
            'profiles' => $profiles,
        ));
    }

    /**
     * Create profile 
     */
    public function editAction()
    {
        if (!$this->isParamsUniquenameSameAsLoggedInUser()) {
            return $this->redirect()->toRoute($routename, array('uniquename' => $this->identity()->getUniquename()), true);
        }

        $objectManager = $this->em();
        $user          = $this->identity();
        $profile       = $user->getProfile();

        $profileForm   = new \User\Form\ProfileEdit($objectManager);
        $profileForm->bind($profile);

        if (!$this->request->isPost()) {
            return new \Zend\View\Model\ViewModel(array(
                'entityId' => $profile->getId(),
                'form' => $profileForm,
                'messages' => $this->messenger()->getMessages(),
            ));
        }

        $httpPostData = $this->request->getPost();
        $profileForm->setData($httpPostData);

        if (!$profileForm->isValid()) {
            return new \Zend\View\Model\ViewModel(array(
                'entityId' => $profile->getId(),
                'form' => $profileForm,
            ));
        }

        $profile->setDate(new \DateTime());
        $profile->setUser($user);
        
        if (!$profile->hasMedia()) {
            $media = $objectManager->getRepository('Blog\Entity\Media')->findBySlug('profile-thumbnail.jpg');
            $profile->setMedia(current($media));
        }

        $objectManager->persist($profile);
        $objectManager->flush();

        return $this->redirect()->toRoute('profile_publicly_available', array('uniquename' => (string) $user->getUniquename()), true);
    }
}
