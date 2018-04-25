<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Functions\DirectoryFunctions;
use Zend\Session\SessionManager;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        
        // if logged redirect to center
        if ($authService->hasIdentity())
            $this->redirect()->toUrl ('/center');
        
        $entryTable = $this->getServiceLocator()->get('EntryTable');
        $recent_entries = $entryTable->fetchAll('recent');
        
        $post = $this->request->getPost();
        // if delete account request
        if (isset($post['delete-account'])) {

            $memberTable = $this->getServiceLocator()->get('MemberTable');
            $memberIllnessTable = $this->getServiceLocator()->get('MemberIllnessTable');
            $documentTable = $this->getServiceLocator()->get('DocumentTable');
            $userTable = $this->getServiceLocator()->get('UserTable');
                
            $rowset = $memberTable->getUserMembers($authService->getIdentity()->id);

            $deleted_member_errors = 0;
            foreach ($rowset as $row) {
                if ($row->user_id == $authService->getIdentity()->id) {
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
            }
            if (!$deleted_member_errors && $userTable->deleteUser($authService->getIdentity()->id)) {
                DirectoryFunctions::deleteWithFiles('public/source/users/'.$authService->getIdentity()->id);
                $authService->clearIdentity();
                $sessionManager = new SessionManager;
                $sessionManager->forgetMe();
                
                $this->redirect()->toUrl('/');
            }
        }
        
        $data['recent_entries'] = $recent_entries;

        $viewModel = new ViewModel($data);

        return $viewModel;
    }

    public function noscriptAction()
    {
        $this->layout('layout/noscript');
        return new ViewModel();
    }
}

