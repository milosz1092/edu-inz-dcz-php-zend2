<?php

namespace Users\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Zend\Session\SessionManager;

use Users\Model\User;
use Users\Model\UserTable;
use Users\Form\LoginForm;

class LoginController extends AbstractActionController
{
    protected $authservice;
    
    public function getAuthService() {
        if (!$this->authservice) {
            $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'user', 'email', 'password', 'MD5(?)');
            
            $authService = new AuthenticationService();
            $authService->setAdapter($dbTableAuthAdapter);
            $this->authservice = $authService;
        }
        
        return $this->authservice;
    }

    public function indexAction()
    {
        if (!$this->getAuthService()->hasIdentity()) {
            $form = new LoginForm();
            $viewModel = new ViewModel(array('form' => $form));

            return $viewModel;
        }

        return $this->redirect()->toRoute(NULL, array('controller' => 'login', 'action' => 'confirm'));

    }

    public function processAction()
    {
                
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(NULL, array('controller' => 'login', 'action' => 'index'));
        }

        $post = $this->request->getPost();

        $form = new LoginForm();
        $inputFilter = new \Users\Form\LoginFilter();
        $form->setInputFilter($inputFilter);
        $form->setData($post);

        if(!$form->isValid()) {
            $model = new ViewModel(array(
               'error' => true,
               'form' => $form,
            ));

            $model->setTemplate('users/login/index');
            return $model;
        }
        
        $sessionManager = new SessionManager;
        $sessionManager->rememberMe();
        
        $adapter = $this->getAuthService()->getAdapter();
        $this->getAuthService()->getAdapter()->setIdentity($this->request->getPost('email'))->setCredential($this->request->getPost('password'));
        $result = $this->getAuthService()->authenticate();
        
        if ($result->isValid()) {
                $data = $adapter->getResultRowObject(array(
                    'id',
                    'email',
                    'role'
                ));

                //Zend_Auth::getInstance()->getStorage()->write($data);
            
            $this->getAuthService()->getStorage()->write($data);
            return $this->redirect()->toUrl('/');
        }
        else {
            $form->get('email')->setMessages(array('Nieprawidłowe dane logowania'));
            
            $model = new ViewModel(array(
               'error' => true,
               'form' => $form,
            ));

            $model->setTemplate('users/login/index');
            return $model;
        }
    }

    public function confirmAction()
    {
        if ($this->getAuthService()->getIdentity()->email) {
            $user_email = $this->getAuthService()->getIdentity()->email;
            $viewModel = new ViewModel(array('user_email' => $user_email));
            
            return $viewModel;
        }
        
        return $this->redirect()->toRoute(NULL, array('controller' => 'login', 'action' => 'index'));
    }


}

