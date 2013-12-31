<?php
namespace User\Controller;

use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\View\Model\ViewModel;

class AuthController extends EntityUsingController
{
    protected $messages = array();

    public function addMessage($type, $message)
    {
        $this->messages[] = array($type => $message);
    }

    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Edit action
     *
     */
    public function loginAction()
    {
        if ($this->identity()) {
            return $this->logged();
        }

        $form = new \User\Form\LoginUniquenameOrEmail();

        if (!$this->request->isPost()) {
            return new ViewModel(array(
                'form' => $form,
                'messages' => $this->getMessages(),
            ));
        }
        $form->setData($this->request->getPost());

        if (!$form->isValid()) {
            return new ViewModel(array(
                'form' => $form,
                'messages' => $this->getMessages(),
            ));
        }

        $formData = $form->getData();
        $userEmail = $this->getUserEmail($formData);

        if (!$userEmail || !$this->authenticate($userEmail, $formData['user']['password'])) {
            $this->addMessage('danger', 'The credential/password combination does not exist, try something else or register');
            return new ViewModel(array(
                'form' => $form,
                'messages' => $this->getMessages(),
            ));
        }
        return $this->logged();
    }

    public function getUserEmail($data)
    {
        $formUniquename = new \User\Form\LoginUniquename();
        $data['user']['uniquename'] = $data['user']['uniquenameoremail'];
        $formUniquename->setData($data);
        if ($formUniquename->isValid()) {
            $validData = $formUniquename->getData();
            $users = $this->getEntityManager()->getRepository('User\Entity\User')->findByUniquename($validData['user']['uniquename']);
            if (empty($users)) {
                return false;
            }
            return current($users)->getEmail();
        }

        $formEmail = new \User\Form\LoginEmail();
        $data['user']['email'] = $data['user']['uniquenameoremail'];
        $formEmail->setData($data);
        if ($formEmail->isValid()) {
            $validData = $formEmail->getData();
            return $validData['user']['email'];
        }
        throw new \Exception('Neither uniquename nor email are valid, this is weird');
    }

    public function logged()
    {
        $params = array('uniquename' => $this->identity()->getUniquename());
        $reuseMatchedParams = true;
        return $this->redirect()->toRoute('profile_index', $params, $reuseMatchedParams);
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
        $providedUniquename = $validatedUserData['uniquename'];
        $providedPassword = $validatedUserData['password'];

        if ($this->isEmailAlreadyInUse($providedEmail)) {
            $messages[] = array('danger' => 'A user with this email address is already registered, try to login, or use a different email address');
            return new ViewModel(array(
                'form' => $form,
                'messages' => $messages,
            ));
        }

        if ($this->isUniquenameAlreadyInUse($providedUniquename)) {
            $messages[] = array('danger' => 'This username already exists, try to login, or use a different username');
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

    public function isUniquenameAlreadyInUse($uniquename)
    {
        $objectManager = $this->getEntityManager();
        $users = $objectManager->getRepository('User\Entity\User')->findByUniquename($uniquename);
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
        $this->toLogin();
    }

    public function toLogin()
    {
        $reuseMatchedParams = true;
        $this->redirect()->toRoute('auth_login', array(), $reuseMatchedParams);
    }
}
