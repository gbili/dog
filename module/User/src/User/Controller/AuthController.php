<?php
namespace User\Controller;

use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\View\Model\ViewModel;

class AuthController extends EntityUsingController
{
    /**
     * Edit action
     *
     */
    public function loginAction()
    {
        if ($this->identity()) {
            return $this->logged();
        }

        $messages = array();

        $form = new \User\Form\Login($this->getEntityManager());

        if (!$this->request->isPost()) {
            return new ViewModel(array(
                'form' => $form,
                'messages' => $messages,
            ));
        }
        $form->setData($data = $this->request->getPost());

        if (!$form->isValid()) {
            return new ViewModel(array(
                'form' => $form,
                'messages' => $messages,
            ));
        }

        $formData = $form->getData();
        $userFormData = $formData['user'];

        if (!$this->authenticate($userFormData['email'], $userFormData['password'])) {
            $messages[] = array('danger' => 'The email password combination does not exist, try another password or register');
            return new ViewModel(array(
                'form' => $form,
                'messages' => $messages,
            ));
        }

        return $this->logged();
    }

    public function logged()
    {
        return $this->redirect()->toRoute('profile_index', array('id' => $this->identity()->getId()));
    }

    public function authenticate($email, $plainPassword)
    {
        $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        $authAdapter = $authService->getAdapter();
        $authAdapter->setIdentityValue($email);
        $authAdapter->setCredentialValue($plainPassword);
        $authResult = $authService->authenticate();
        if (!$authResult->isValid()) {
            return false;
        }
        $authService->getStorage()->write($authResult->getIdentity());
        return true;
    }

    /**
     * Link media to a post 
     */
    public function registerAction()
    {
        if ($this->identity()) {
            return $this->logged();
        }
        $messages = array();

        $form = new \User\Form\Register();

        if (!$this->request->isPost()) {
            return new ViewModel(array(
                'form' => $form,
                'messages' => $messages,
            ));
        }

        $form->setData($this->request->getPost());

        //TODO add filters to the fieldset 
        if (!$form->isValid()) {
            return new ViewModel(array(
                'form' => $form,
                'messages' => $messages,
            ));
        }

        $formData = $form->getData();
        $validatedUserData = $formData['user'];
        $providedEmail = $validatedUserData['email'];
        $providedPassword = $validatedUserData['password'];

        if ($this->isEmailAlreadyInUse($providedEmail)) {
            $messages[] = array('danger' => 'A user with this email address is already registered, try to login, or use a different email address');
            return new ViewModel(array(
                'form' => $form,
                'messages' => $messages,
            ));
        }

        $this->persistUser($validatedUserData);

        if ($this->authenticate($providedEmail, $providedPassword)) {
            return $this->logged();
        }
    }

    public function persistUser($validatedUserData)
    {
        $user = new \User\Entity\User();
        $user->hydrate($validatedUserData);
        $user->setRole('user');
        $objectManager = $this->getEntityManager();
        $objectManager->persist($user);
        $objectManager->flush();
    }

    public function isEmailAlreadyInUse($email)
    {
        $objectManager = $this->getEntityManager();
        $users = $objectManager->getRepository('User\Entity\User')->findByEmail($email);
        return !empty($users);
    }

    /**
     * Link media to a post 
     *
     */
    public function logoutAction()
    {
        if ($this->identity()) {
            $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
            $authStorage = $authService->getStorage();
            $authStorage->clear();
        }
        $this->redirect()->toRoute('auth_login');
    }

    /**
     * Delete action
     *
     */
    public function deleteAction()
    {
        $media = $this->getEntityManager()->getRepository('Blog\Entity\Media')->find($this->params('id'));

        if ($media) {
            $em = $this->getEntityManager();
            $em->remove($media);
            $em->flush();

            $this->flashMessenger()->addSuccessMessage('Media Deleted');
        }
        return $this->redirect()->toRoute('blog', array('controller' => 'media', 'action' => 'index'));
    }
}
