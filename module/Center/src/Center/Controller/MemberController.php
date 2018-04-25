<?php

namespace Center\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use Center\Form\MemberCreateForm;
use Center\Form\MemberEditForm;
use Center\Model\Member;


class MemberController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function createAction()
    {
        $form = new MemberCreateForm();
        $viewModel = new ViewModel(array('form' => $form));

        return $viewModel;
    }

    public function processCreateMemberAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(null, array('controller' => 'Center', 'action' => 'index'));
        }

        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $filter = $this->getServiceLocator()->get('Center\Form\MemberCreateFilter');
        $form = new MemberCreateForm();
        $post = $this->request->getPost();

        // change letters name, surname
        $post['name'] = \Application\Functions\StringFunctions::my_mb_ucfirst(mb_strtolower($post['name']));
        $post['surname'] = \Application\Functions\StringFunctions::my_mb_ucfirst(mb_strtolower($post['surname']));
        
        $form->setInputFilter($filter->getInputFilter());
        $form->setData($post);

        if(!$form->isValid()) {
            $model = new ViewModel(array(
               'error' => true,
               'form' => $form,
            ));

            $model->setTemplate('center/member/create');
            return $model;
        }

        $member = new Member();
        $member->exchangeArray($post);

        $new_id = $memberTable->saveMember($member, $authService->getIdentity()->id);

        // creating directories for member
        mkdir('private/source/members/'.$new_id, 0775, true);
        mkdir('private/source/members/'.$new_id.'/docs', 0775, true);
        
        $empty_form = new MemberCreateForm();

        $viewModel = new ViewModel(array('success' => true, 'form' => $empty_form, 'created_member_id' => $new_id));
        $viewModel->setTemplate('center/member/create');

        return $viewModel;
    }

    public function profileEditAction()
    {
        $context = $this->params()->fromRoute('context');
        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $member = $memberTable->getMember($this->params()->fromRoute('id'));
        
        if ($member->user_id != $authService->getIdentity()->id) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $formManager = $this->getServiceLocator()->get('FormElementManager');
        
        switch($context) {
            case 'profile':
                $form = $formManager->get('Center\Form\MemberEditForm');
                $form->bind($member);
            break;
            case 'illness':
                $form = $formManager->get('Center\Form\MemberIllnessEditForm');
                
                $memberIllnessTable = $this->getServiceLocator()->get('MemberIllnessTable');
                $memberIllnessesArray = $memberIllnessTable->getIllnesses($member->id);
                
                $form->setData($memberIllnessesArray);
            break;
        }


        if (file_exists('private/source/members/'.$member->id.'/avatar.png')) {
            $avatar = '/files/member-avatar/'.$member->id.'/avatar.png';
        } else
            $avatar = '/img/member_avatar.png';

        return array(
            'avatar'    => $avatar,
            'form'      => $form,
            'member'    => $member,
            'context'   => $context
        );

    }

    public function processProfileEditAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(null, array('controller' => 'Center', 'action' => 'index'));
        }

        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
        }

        $context = $post['context'];
        
        $member_row = $memberTable->getMember($post['id']);
        
        if ($member_row->user_id != $authService->getIdentity()->id) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $formManager = $this->getServiceLocator()->get('FormElementManager');

        switch($context) {
            case 'profile':
                $filter = $this->getServiceLocator()->get('Center\Form\MemberEditFilter');
                
                $form = $formManager->get('Center\Form\MemberEditForm');
                $form->setInputFilter($filter->getInputFilter());
                $form->setData($post);

                if (file_exists('private/source/members/'.$member_row->id.'/avatar.png')) {
                    $avatar = '/files/member-avatar/'.$member_row->id.'/avatar.png';
                } else
                    $avatar = '/img/member_avatar.png';
                
                // change letters name, surname
                $post['name'] = \Application\Functions\StringFunctions::my_mb_ucfirst(mb_strtolower($post['name']));
                $post['surname'] = \Application\Functions\StringFunctions::my_mb_ucfirst(mb_strtolower($post['surname']));
                
                if ($form->isValid()) { 
                    $member = new Member();
                    $member->exchangeArray($post);

                    // save member
                    $memberTable->saveMember($member, $authService->getIdentity()->id);

                    $formManager = $this->getServiceLocator()->get('FormElementManager');
                    $empty_form = $formManager->get('Center\Form\MemberEditForm');
                    $empty_form->bind($member);

                    $viewModel = new ViewModel(array('avatar' => $avatar, 'member' => $member, 'success' => true, 'form' => $empty_form, 'context'   => $context));
                    $viewModel->setTemplate('center/member/profile-edit');

                    return $viewModel;
                }
                else {
                    $member = new Member();
                    $member->exchangeArray($member_row);
                    
                    $model = new ViewModel(array(
                        'error' => true,
                        'avatar' => $avatar,
                        'form' => $form,
                        'context'   => $context,
                        'member' => $member
                    ));

                    $model->setTemplate('center/member/profile-edit');
                    return $model;
                }
            break;
            case 'illness':
                $form = $formManager->get('Center\Form\MemberIllnessEditForm');
                $memberIllnessTable = $this->getServiceLocator()->get('MemberIllnessTable');
                
                if (isset($post['illnesses'])) {
                    $illnessesArray = $post['illnesses'];
                } else {
                    $illnessesArray = array();
                }
                
                $addedIllnessesArray = $memberIllnessTable->updateIllnesses($illnessesArray, $member_row->id);
                
                $form->setData($addedIllnessesArray);
                
                $viewModel = new ViewModel(array('post' => $post, 'member' => $member_row, 'success' => true, 'form' => $form, 'context'   => $context));
                $viewModel->setTemplate('center/member/profile-edit');

                return $viewModel;
            break;
        }
            
        
    }

    public function changeAvatarAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            if (isset($post["avatar"]) && isset($post["member"])) {
                
                $authService = $this->getServiceLocator()->get('AuthService');
                $memberTable = $this->getServiceLocator()->get('MemberTable');
                
                $member_row = $memberTable->getMember($post["member"]);

                if ($member_row->user_id != $authService->getIdentity()->id) {
                    return new JsonModel(array(
                        'message' => 'ERROR', 'code' => 1337
                    ));
                }

                // save avatar
                move_uploaded_file($post["avatar"]["tmp_name"], 'private/source/members/'.$post["member"].'/avatar.png');


                return new JsonModel(array(
                    'success'
                ));
            }
        }
    }
}

