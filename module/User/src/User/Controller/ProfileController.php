<?php
namespace User\Controller;

class ProfileController extends LoggedInController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if (!$user->hasProfile()) {
            return $this->redirect()->toRoute('profile_edit');
        }
        $profile = $user->getProfile();        
        $media = $profile->getMedia();
        return new \Zend\View\Model\ViewModel(array(
            'profile' => $profile, 
            'media' => $media, 
            'entity' => $user, 
        ));
    }

    /**
     *
     */
    public function listAction()
    {
        $profiles = $this->getEntityManager()->getRepository('User\Entity\Profile')->findAll();

        return new \Zend\View\Model\ViewModel(array(
            'profiles' => $profiles,
        ));
    }

    /**
     * Create a blog post
     */
    public function editAction()
    {
        $objectManager = $this->getEntityManager();
        $user          = $this->identity();
        $profile       = $user->getProfile();

        $profileForm   = new \User\Form\ProfileEdit($objectManager);
        $profileForm->bind($profile);

        if (!$this->request->isPost()) {
            return new \Zend\View\Model\ViewModel(array(
                'entityId' => $profile->getId(),
                'form' => $profileForm,
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

        return $this->redirect()->toRoute('profile_index', array('id' => (string) $user->getId()));
    }
}
