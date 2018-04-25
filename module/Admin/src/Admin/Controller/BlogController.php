<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Functions\DirectoryFunctions;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

use Admin\Model\EntrySection;
use Admin\Model\Entry;

use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class BlogController extends AbstractActionController
{
    
    protected $entryTableGateway;

    public function indexAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $userTable = $this->getServiceLocator()->get('UserTable');
        $entrySectionTable = $this->getServiceLocator()->get('EntrySectionTable');
        
        if (!$this->entryTableGateway)
            $this->entryTableGateway = $this->getServiceLocator()->get('EntryTableGateway');

        $select = new Select('entry');
        $select->order('created DESC');

        if ($authService->getIdentity()->role != 3)
            $select->where(array('author' => $authService->getIdentity()->id));
        
        $resultSetPrototype = new ResultSet();

        $paginatorAdapter = new DbSelect($select, $this->entryTableGateway->getAdapter(), $resultSetPrototype);
        
        $paginator = new Paginator($paginatorAdapter);
        
        $page = 1;
        
        if ($this->params()->fromRoute('page'))
            $page = $this->params()->fromRoute('page');
        
        $paginator->setCurrentPageNumber((int)$page);
        $paginator->setItemCountPerPage(10);
        
        $viewModel = new ViewModel(array('entries_paginator' => $paginator, 'userTable' => $userTable, 'entrySectionTable' => $entrySectionTable));
        
        // Checking if entry is deleting
        $post = $this->request->getPost();
        if (isset($post['delete-entry']) && $post['entry-id'] != 0) {
            $viewModel->deleteSuccess = $this->processDeleteEntry($post['entry-id']);
        }
        
        $viewModel->sent_post = $post;
        return $viewModel;
    }

    public function addEntryAction()
    {
        // creating empty entry
        $form = $this->processAddEntryAction();

        $form->isValid();
        $form_id = $form->getData();
        $form_id = $form_id['id'];
        $viewModel = new ViewModel(array('form' => $form, 'success' => true, 'form_id' => $form_id, 'type' => 'add-entry'));
        $viewModel->setTemplate('admin/blog/edit-entry');
        
        return $viewModel;
    }

    public function editEntryAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        $entryTable = $this->getServiceLocator()->get('EntryTable');
        
        $entry = $entryTable->getEntry($this->params()->fromRoute('id'));
               
        if ($authService->hasIdentity() && ($authService->getIdentity()->role == 3 || $authService->getIdentity()->id == $entry->author)) {
        
            $formManager = $this->getServiceLocator()->get('FormElementManager');
            $form = $formManager->get('Admin\Form\AddEntryForm');

            $form->bind($entry);
            $form->isValid();

            $form_id = $form->getData();
            $form_id = $form_id['id'];
            $viewModel = new ViewModel(array('form' => $form, 'form_id' => $form_id, 'type' => 'edit-entry'));

            return $viewModel;
        }
        else
            return $this->redirect()->toUrl('/admin/blog');
    }

    public function processAddEntryAction()
    {
        if (!$this->request->isPost()) {
            $authService = $this->getServiceLocator()->get('AuthService');
            $entryTable = $this->getServiceLocator()->get('EntryTable');

            $formManager = $this->getServiceLocator()->get('FormElementManager');
            $form = $formManager->get('Admin\Form\AddEntryForm');
            
            // create an empty entry
            
            $entry = new Entry();
            $entry->exchangeArray(array('title' => 'Nowy wpis', 'section_id' => 0, 'summary' => '', 'content' => '', 'published' => 0, 'photo' => "", 'author' => $authService->getIdentity()->id));

            $returned_id = $entryTable->saveEntry($entry);
            $saved_entry = $entryTable->getEntry($returned_id);
            
            // create directory for entry
            //mkdir('public/source/entries/'.$returned_id, 0775, true);
            
            return $form->bind($saved_entry);
        }
    }

    public function processEditEntryAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute(null, array('controller' => 'blog', 'action' => 'index'));
        }
        $entryTable = $this->getServiceLocator()->get('EntryTable');
        
        $formManager = $this->getServiceLocator()->get('FormElementManager');
        $form = $formManager->get('Admin\Form\AddEntryForm');

        $post = $this->request->getPost();


        $entry = new Entry();
        $entry->exchangeArray($post);

        $entryTable->saveEntry($entry);
        
        $form->bind($entry);
        
        // VALIDATION !!!!

        $viewModel = new ViewModel(array('form' => $form, 'form_id' => $post['id'], 'type' => 'edit-entry', 'success' => true));
        $viewModel->setTemplate('admin/blog/edit-entry');
        return $viewModel;
    }
    
    protected function processDeleteEntry($id = 0)
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        
        $entryTable = $this->getServiceLocator()->get('EntryTable');
        
        try {
            $entry = $entryTable->getEntry($id);
        }
        catch (\Exception $e) {
            return false;
        }
        
        if ($authService->hasIdentity() && ($authService->getIdentity()->role == 3 || $authService->getIdentity()->id == $entry->author)) {
        
            // delete entry from database
            if ($id == 0)
                $id = $this->params()->fromRoute('id');
            
            $entryTable->deleteEntry($id);

            return true;
        }
        else
            return $this->redirect()->toUrl('/admin/blog');

    }
    

}

