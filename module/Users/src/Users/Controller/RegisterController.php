<?php

namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Form\RegisterForm;
use Zend\Db\TableGateway\TableGateway;

use Zend\Authentication\AuthenticationService;

use Users\Model\User;
use Users\Model\UserTable;

class RegisterController extends AbstractActionController
{
    protected $authservice;
    
    public function __construct() {
        $this->authservice = new AuthenticationService();
    }

    public function indexAction()
    {
        if ($this->authservice->hasIdentity()) {
            $user_email = $this->authservice->getIdentity()->email;
            
            $model = new ViewModel(array('user_email' => $user_email));
            $model->setTemplate('users/login/confirm');
            
            return $model;
        }
        else {
            $form = new RegisterForm();
            $viewModel = new ViewModel(array('form' => $form));

            return $viewModel;
        }
    }

    public function confirmAction()
    {
        return new ViewModel();
    }
    
    protected function createUser(array $data) {
        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        
        $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new User);
        
        $tableGateway = new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
        
        $user = new User();
        $user->exchangeArray($data);
        
        $userTable = new UserTable($tableGateway);
        $new_id = $userTable->saveUser($user);
        
        // creating public directory for user
        mkdir('public/source/users/'.$new_id, 0775, true);
        mkdir('public/source/users/'.$new_id.'/source', 0775, true);
        
        return true;
    }

    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(null, array('controller' => 'register', 'action' => 'index'));
        }
        
        $post = $this->request->getPost();
        $form = new RegisterForm();
        
        $filter = $this->getServiceLocator()->get('Users\Form\RegisterFilter');
        $form->setInputFilter($filter->getInputFilter());
        $form->setData($post);
        
        if(!$form->isValid()) {
            $model = new ViewModel(array(
               'error' => true,
               'form' => $form,
            ));
            
            $model->setTemplate('users/register/index');
            return $model;
        }
        
        $this->createUser($form->getData());
        
        
        
        
        
        return $this->redirect()->toRoute(null, array('controller' => 'register', 'action' => 'confirm'));
    }


}