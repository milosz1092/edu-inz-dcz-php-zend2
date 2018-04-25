<?php
namespace Blog\Controller;

use Application\Functions\DateTimeExchange;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class IndexController extends AbstractActionController
{
    protected $entryTableGateway;

    public function indexAction()
    {   
        if (!$this->entryTableGateway)
            $this->entryTableGateway = $this->getServiceLocator()->get('EntryTableGateway');

        $select = new Select('entry');
        $select->order('created DESC');
        $select->where('published');
        
        $resultSetPrototype = new ResultSet();

        $paginatorAdapter = new DbSelect($select, $this->entryTableGateway->getAdapter(), $resultSetPrototype);
        
        $paginator = new Paginator($paginatorAdapter);
        
        $page = 1;
        
        if ($this->params()->fromRoute('page'))
            $page = $this->params()->fromRoute('page');
        
        $paginator->setCurrentPageNumber((int)$page);
        $paginator->setItemCountPerPage(9);
        
        $date_time = new DateTimeExchange();
        
        $viewModel = new ViewModel(array('recent_entries_paginator' => $paginator, 'date_time' => $date_time, 'prev_year' => NULL, 'prev_month' => NULL));
        
        return $viewModel;
    }

    public function showAction()
    {
        $authService = $this->getServiceLocator()->get('AuthService');
        
        $entryTable = $this->getServiceLocator()->get('EntryTable');
        $userTable = $this->getServiceLocator()->get('UserTable');
        
        $entry = $entryTable->getEntry($this->params()->fromRoute('id'));
        
        $next = $entryTable->getNext($entry->created);
        $prev = $entryTable->getPrev($entry->created);
        
        if ($entry->published || ($authService->hasIdentity() && ($authService->getIdentity()->role == 3 || $authService->getIdentity()->id == $entry->author))) {
        
            $author_row = $userTable->getUser($entry->author);
            $author = array('id' => $author_row->id, 'email' => $author_row->email);

            $date_time = new DateTimeExchange($entry->created);
            $viewModel = new ViewModel(array('entry' => $entry, 'date_time' => $date_time, 'author' => $author, 'next' => $next, 'prev' => $prev));
            
            if ($authService->hasIdentity() && ($authService->getIdentity()->role == 3 || $authService->getIdentity()->id == $entry->author)) {
                $viewModel->enableEdit = true;
            }
        
            return $viewModel;
        }
        else
            return $this->redirect()->toUrl('/blog');
    }

}

