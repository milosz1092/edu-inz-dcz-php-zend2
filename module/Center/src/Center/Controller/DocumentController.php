<?php

namespace Center\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use DirectoryIterator;
use Application\Functions\DateTimeExchange;

use Center\Model\Document;

use DateTime;

class DocumentController extends AbstractActionController
{
    
    private function getDocumentFiles($document_id, $member_id) {
        $directory = 'private/source/members/'.$member_id.'/docs/'.$document_id;
        
        $files = array();
        foreach (new DirectoryIterator($directory) as $fileInfo) {
            if($fileInfo->isDot()) {
                continue;
            }
            
            $files[] = $fileInfo->getFileInfo();
        }
        
        return $files;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function addDocumentAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $member = $memberTable->getMember($this->params()->fromRoute('id'));

        if ($member->user_id != $authService->getIdentity()->id) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $form = $formManager->get('Center\Form\DocumentAddForm');

        return array(
            'form'      => $form,
            'member'    => $member,
        );
    }

    public function processAddDocumentAction()
    {
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
        }
        
        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $documentTable = $this->getServiceLocator()->get('DocumentTable');
       
        $member = $memberTable->getMember($post["id_member"]);
        


        if ($member->user_id != $authService->getIdentity()->id) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $form = $formManager->get('Center\Form\DocumentAddForm');
        $filter = $this->getServiceLocator()->get('Center\Form\DocumentAddFilter');
        $form->setInputFilter($filter->getInputFilter());

        if ($request->isPost()) {

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();
                
                $document = new Document();
                $document->exchangeArray($data);
                
                $doc_id = $documentTable->saveDocument($document);
                mkdir('private/source/members/'.$member->id.'/docs/'.$doc_id, 0775, true);

                $this->redirect()->toRoute(NULL, array('controller' => 'document', 'action' => 'add-files', 'id' => $doc_id, 'context' => $member->id));
            } else {
                $model = new ViewModel(array(
                   'error' => true,
                   'form' => $form,
                   'member' => $member,
                ));

                $model->setTemplate('center/document/add-document');
                return $model;
            }
        }

        return array('form' => $form);
    }


    public function uploadProgressAction()
    {
        //\Center\Model\ConstStorage::MAX_DOCUMENT_UPLOAD_FILESIZE      
        $id = $this->params()->fromRoute('id', null);
        /*$progress = new \Zend\ProgressBar\Upload\UploadProgress();

        return new \Zend\View\Model\JsonModel($progress->getProgress($id));*/

        return new JsonModel(
            uploadprogress_get_info($id)
        );
        
    }

    public function addFilesAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $documentTable = $this->getServiceLocator()->get('DocumentTable');
        $date_time = new DateTimeExchange();
        
        $member = $memberTable->getMember($this->params()->fromRoute('context'));
        $document = $documentTable->getDocument($this->params()->fromRoute('id'));

        if (($member->user_id != $authService->getIdentity()->id) || ($document->id_member != $member->id)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        

        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $form = $formManager->get('Center\Form\FilesAddForm');

        $member_files_objects = $this->getDocumentFiles($document->id, $member->id);
        $member_files = array();
        foreach ($member_files_objects as $file) {
            $member_files[] = $file->getBasename();
        }
        sort($member_files);
        
        return array(
            'form'      => $form,
            'member'    => $member,
            'document'  => $document,
            'date_time' => $date_time,
            'files' => $member_files,
        );
    }

    public function processAddFilesAction()
    {
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
        }
        
        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $documentTable = $this->getServiceLocator()->get('DocumentTable');
        
       
        $member = $memberTable->getMember($post["id_member"]);
        $document = $documentTable->getDocument($post["id_document"]);
        
        if (($member->user_id != $authService->getIdentity()->id) || ($document->id_member != $member->id)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $form = $formManager->get('Center\Form\FilesAddForm');
        $filter = $this->getServiceLocator()->get('Center\Form\FilesAddFilter');
        
        $form->setInputFilter($filter->getInputFilter());
        
        if ($request->isPost()) {

            $form->setData($post);
            if ($form->isValid() && !empty($post['isAjax'])) {
                $data = $form->getData();
                // Form is valid, save the form!

                $errors = array();
                $uploaded = array();
                $status = true;
                foreach ($post["files"] as $file) {
                    $info = pathinfo($file['name']);
                    
                    if (!in_array(mb_strtolower($info['extension']), \Center\Model\ConstStorage::AVAILABLE_UPLOAD_EXTENSIONS)) {
                        $errors[$file['name']][] = 'Nieobsługiwane rozszerzenie pliku';
                    }
                    
                    if ($file["size"] / 1048576 > \Center\Model\ConstStorage::MAX_DOCUMENT_UPLOAD_FILESIZE) {
                        $errors[$file['name']][] = 'Rozmiar pliku przekracza '.\Center\Model\ConstStorage::MAX_DOCUMENT_UPLOAD_FILESIZE.'MB';
                    }
                    
                    if (!isset($errors[$file['name']])) {
                        if (file_exists('private/source/members/'.$member->id.'/docs/'.$document->id.'/'.$file["name"]))
                        {
                            $i = 1;
                            $new_filename = $info['filename'].'_'.$i.'.'.$info['extension'];
                            while(file_exists('private/source/members/'.$member->id.'/docs/'.$document->id.'/'.$new_filename)) {
                                $i++;
                                $new_filename = $info['filename'].'_'.$i.'.'.$info['extension'];
                            }
                        } else {
                            $new_filename = $file['name'];
                        }
                        
                        move_uploaded_file($file["tmp_name"], 'private/source/members/'.$member->id.'/docs/'.$document->id.'/'.$new_filename);
                        $uploaded[] = array($file['name'], $new_filename);
                    } else {
                        $status = false;
                    }
                }
                
                $data = array();
                $data['status'] = $status;
                
                if (count($uploaded)) {
                    $data['uploaded'] = $uploaded;
                }
                
                if (count($errors)) {
                    $data['errors'] = $errors;
                }
                
                return new JsonModel( $data );

            } else {
                if (!empty($post['isAjax'])) {
                     // Send back failure information via JSON
                     return new JsonModel(array(
                         'status'     => false,
                         'formErrors' => $form->getMessages(),
                         'formData'   => $form->getData(),
                     ));
                }
            }
        }
    }

    public function editDocumentAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $documentTable = $this->getServiceLocator()->get('DocumentTable');
        
        $member = $memberTable->getMember($this->params()->fromRoute('context'));
        $document = $documentTable->getDocument($this->params()->fromRoute('id'));
        
        if (($member->user_id != $authService->getIdentity()->id) || ($document->id_member != $member->id)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $form = $formManager->get('Center\Form\DocumentAddForm');
        $form->bind($document);

        return array(
            'form'      => $form,
            'member'    => $member,
            'document_id' => $this->params()->fromRoute('id')
        );

    }

    public function processEditDocumentAction()
    {
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $post = $this->request->getPost();
        }
        
        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $authService = $this->getServiceLocator()->get('AuthService');
        $memberTable = $this->getServiceLocator()->get('MemberTable');
        $documentTable = $this->getServiceLocator()->get('DocumentTable');
       
        $document = $documentTable->getDocument($post["id"]);
        $member = $memberTable->getMember($post['id_member']);

        if (($member->user_id != $authService->getIdentity()->id) || ($document->id_member != $member->id)) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $form = $formManager->get('Center\Form\DocumentAddForm');
        $filter = $this->getServiceLocator()->get('Center\Form\DocumentAddFilter');
        $form->setInputFilter($filter->getInputFilter());

        $form->setData($post);
        if ($form->isValid()) {
            $data = $form->getData();

            $document = new Document();
            $document->exchangeArray($data);

            $documentTable->saveDocument($document);
            
            $model = new ViewModel(array(
               'success' => true,
               'form' => $form,
               'member' => $member,
               'document_id' => $post["id"]
            ));

            $model->setTemplate('center/document/edit-document');
            return $model;
            
        } else {
            $model = new ViewModel(array(
               'error' => true,
               'form' => $form,
               'member' => $member,
               'document_id' => $post["id"]
            ));

            $model->setTemplate('center/document/edit-document');
            return $model;
        }


        return array('form' => $form);
    }

    public function changeFilenameAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $post = $this->request->getPost();

            $authService = $this->getServiceLocator()->get('AuthService');
            $memberTable = $this->getServiceLocator()->get('MemberTable');
            $documentTable = $this->getServiceLocator()->get('DocumentTable');

            $member = $memberTable->getMember($post["member_id"]);
            $document = $documentTable->getDocument($post["document_id"]);


            if (($member->user_id != $authService->getIdentity()->id) || ($document->id_member != $member->id)) {
                return new JsonModel(array(
                    'message' => 'ERROR', 'code' => 1337
                ));
            }

            $info = pathinfo($post["new_filename"]);
            
            $errors = array();
            if (!in_array(mb_strtolower($info['extension']), \Center\Model\ConstStorage::AVAILABLE_UPLOAD_EXTENSIONS)) {
                $errors[] = 'Nieobsługiwane rozszerzenie pliku';
            }
            
            if (file_exists('private/source/members/'.$member->id.'/docs/'.$document->id.'/'.$post["new_filename"]))
            {
                $i = 1;
                $new_filename = $info['filename'].'_'.$i.'.'.$info['extension'];
                while(file_exists('private/source/members/'.$member->id.'/docs/'.$document->id.'/'.$new_filename)) {
                        $i++;
                        $new_filename = $info['filename'].'_'.$i.'.'.$info['extension'];
                }
            } else {
                $new_filename = $post["new_filename"];
            }
            
            // rename file
            if (!count($errors) && rename('private/source/members/'.$member->id.'/docs/'.$document->id.'/'.$post['old_filename'], 'private/source/members/'.$member->id.'/docs/'.$document->id.'/'.$new_filename)) {
                return new JsonModel(array(
                    'status' => 'success',
                    'new_filename' => $new_filename
                ));
            } else {
                return new JsonModel(array(
                    'status' => 'false',
                    'errors' => $errors
                ));
            }
        }
    }

    public function deleteFileAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            $post = $this->request->getPost();
            
            $authService = $this->getServiceLocator()->get('AuthService');
            $memberTable = $this->getServiceLocator()->get('MemberTable');
            $documentTable = $this->getServiceLocator()->get('DocumentTable');

            $member = $memberTable->getMember($post["member_id"]);
            $document = $documentTable->getDocument($post["document_id"]);


            if (($member->user_id != $authService->getIdentity()->id) || ($document->id_member != $member->id)) {
                return new JsonModel(array(
                    'message' => 'ERROR', 'code' => 1337
                ));
            }
            
            if (unlink('private/source/members/'.$member->id.'/docs/'.$document->id.'/'.$post['filename'])) {
                return new JsonModel(array(
                    'status' => 'success',
                ));
            } else {
                return new JsonModel(array(
                    'status' => 'false',
                ));
            }
        }
    }


}

