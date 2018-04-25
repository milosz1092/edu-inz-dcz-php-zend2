<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\InputFilter\Factory as InputFactory;

use Users\Form\RegisterForm;
use Users\Form\RegisterFilter;

use Application\Functions\DirectoryFunctions;

use Users\Model\User;
use Users\Model\UserTable;

class UserManagerController extends AbstractActionController
{

    public function indexAction()
    {
        $userTable = $this->getServiceLocator()->get('UserTable');
        $viewModel = new ViewModel(array('users' => $userTable->fetchAll()));
       
        return $viewModel;
    }

    public function editAction()
    {
        $userTable = $this->getServiceLocator()->get('UserTable');
        $user = $userTable->getUser($this->params()->fromRoute('id'));

        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $form = $formManager->get('Admin\Form\UserEditForm');
        
        $form->bind($user);
        
        $viewModel = new ViewModel(array('form' => $form, 'user_id' => $this->params()->fromRoute('id')));
        
        return $viewModel;
    }

    public function processAction()
    {
        if(!$this->request->isPost()) {
            return $this->redirect()->toRoute('admin/user-manager', array('action' => 'edit'));
        }
        
        $post = $this->request->getPost();
        $userTable = $this->getServiceLocator()->get('UserTable');
        $user = $userTable->getUser($post->id); 
        
        $original_passwd = $user->password;
        $original_email = $user->email;
        
        $filter = $this->getServiceLocator()->get('Admin\Form\UserEditFilter');
        $inputFilter = $filter->getInputFilter();
        
        if ($post->email != $original_email && $userTable->getUserByEmail($post->get('email'))) {
            $factory = new InputFactory();
            $dbAdapter = $this->getServiceLocator()->get('DbAdapter');
            
            $inputFilter->add($factory->createInput(array(
               'name' => 'email',
               'required' => true,
               'validators' => array(
                    array(
                        'name' => 'Db\NoRecordExists',
                        'options' => array(
                            'message' => 'Podany adres e-mail jest zajęty',
                            'table'   => 'user',
                            'field'   => 'email',
                            'adapter' => $dbAdapter
                        ),
                    ),
               )
            )));
        }
        
        
        $form = $this->getServiceLocator()->get('UserEditForm');
        $form->bind($user);
        $form->setInputFilter($inputFilter);
        $form->setData($post);
        
        if(!$form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form'  => $form,
            ));
            
            $model->setTemplate('admin/user-manager/edit');
            return $model;
        }
        
        // Domyślnie - ustawienie starego hasła
        $user->password = 0;
		
        // Sprawdzenie hasła
        if($post->new_password != '' && $post->new_password != NULL) {
            $user->password = $post->new_password;
            $post->password = $post->new_password;

            // Tutaj monitujemy usera o nowym haśle
            //$um = new UserMail($userTable);
            //$um->toUser((int)$user->id)->fromMail('mmanaj@softgraf.pl')->setSubject("Zmiana hasła")->send("Witaj, Twoje nowe hasło to: ".$post->new_password);
        }
        $user->role = $post->role;
        
        $this->getServiceLocator()->get('UserTable')->saveUser($user);
        
        return $this->redirect()->toRoute('admin/user-manager');
    }

    public function deleteAction() {
        

        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $memberIllnessTable = $this->getServiceLocator()->get('MemberIllnessTable');
        $documentTable = $this->getServiceLocator()->get('DocumentTable');
        $userTable = $this->getServiceLocator()->get('UserTable');
        
        $rowset = $memberTable->getUserMembers($this->params()->fromRoute('id'));
        
            $deleted_member_errors = 0;
            foreach ($rowset as $row) {
                if ($memberTable->deleteMember($row->id)) {
                    // deleting member documents
                    $documentTable->deleteMemberDocuments($row->id);
                    // deleting member illness
                    $memberIllnessTable->deleteMemberIllnesses($row->id);
                    // deleting member directory
                    DirectoryFunctions::deleteWithFiles('private/source/members/'.$row->id);
                } else {
                    $deleted_member_errors++;
                }
            }
            
        
        if (!$deleted_member_errors && $this->getServiceLocator()->get('UserTable')->deleteUser($this->params()->fromRoute('id'))) {
            DirectoryFunctions::deleteWithFiles('public/source/users/'.$this->params()->fromRoute('id'));
        }
        
        return $this->redirect()->toRoute('admin/user-manager');
    }

}

