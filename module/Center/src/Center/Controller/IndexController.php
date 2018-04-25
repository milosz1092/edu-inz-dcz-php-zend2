<?php

namespace Center\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Functions\DateTimeExchange;
use Application\Functions\DirectoryFunctions;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $documentTable = $this->getServiceLocator()->get('DocumentTable');
        $authService = $this->getServiceLocator()->get('AuthService');
        $date_time = new DateTimeExchange();
        
        
        
        
        $data = array(
            'url' => $this->url()->fromRoute(),
            'member_id' => $this->params()->fromRoute('id'),
            'date_time' => $date_time
        );

        // if delete member request
        $post = $this->request->getPost();
        if (isset($post['delete-member']) && isset($post['member-id'])) {
            $memberIllnessTable = $this->getServiceLocator()->get('MemberIllnessTable');
            $member = $memberTable->getMember($post['member-id']);
            
            if ($member->user_id == $authService->getIdentity()->id) {
                // deleting member
                if ($memberTable->deleteMember($post['member-id'])) {
                    $data['member_deleted'] = true;
                    
                    // deleting member documents
                    $documentTable->deleteMemberDocuments($post['member-id']);
                    // deleting member illness
                    $memberIllnessTable->deleteMemberIllnesses($post['member-id']);
                    // deleting member directory
                    DirectoryFunctions::deleteWithFiles('private/source/members/'.$post['member-id']);
                }

            }
        }
        
        // if clear account request
        if (isset($post['clear-account'])) {
            if (!isset($memberIllnessTable))
                $memberIllnessTable = $this->getServiceLocator()->get('MemberIllnessTable');

            
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
                if (!$deleted_member_errors)
                    $data['account_clear'] = true;
        }
        
        $data['members'] = $memberTable->getUserMembers($authService->getIdentity()->id);
        
        if ($this->params()->fromRoute('id')) {
            $memberIllnessTable = $this->getServiceLocator()->get('MemberIllnessTable');
            $member = $memberTable->getMember($this->params()->fromRoute('id'));
            
            if ($member['user_id'] != $authService->getIdentity()->id) {
                $this->getResponse()->setStatusCode(404);
                return;
            }
            
            $data['context'] = $this->params()->fromRoute('context');
            $data['member_row'] = $member;
            $data['member_illness'] = $memberIllnessTable->getIllnessesString($member->id);
            
            // if delete document request
            $post = $this->request->getPost();
            if (isset($post['delete-document']) && isset($post['id-document'])) {
                if ($documentTable->deleteDocument($post['id-document'])) {
                    DirectoryFunctions::deleteWithFiles('private/source/members/'.$member->id.'/docs/'.$post['id-document']);
                    $data['document_deleted'] = true;
                }
            }
            
            
            $data['member_documents'] = $documentTable->getMemberDocuments($member["id"]);
            
        } else {
            $data['all_documents_count'] = $documentTable->countUserDocuments($authService->getIdentity()->id);
            $data['all_files_count'] = $documentTable->countUserFiles($authService->getIdentity()->id);
        }
        
        $viewModel = new ViewModel($data);

        return $viewModel;
    }


}

