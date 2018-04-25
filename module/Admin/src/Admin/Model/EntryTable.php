<?php

namespace Admin\Model;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;

use Admin\Model\Entry;

class EntryTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function saveEntry(Entry $entry) {
        $data = array(
            'title' => $entry->title,
            'section_id' => $entry->section_id,
            'summary' => $entry->summary,
            'content' => $entry->content,
            'published' => $entry->published,
            'photo' => $entry->photo
        );
        
        $id = (int)$entry->id;
        
        if ($id == 0) {
            $data['created'] = date("Y-m-d H:i:s");
            $data['author'] = $entry->author;
            $this->tableGateway->insert($data);
            
            return $this->tableGateway->getLastInsertValue();
        }
        else {
            if($this->getEntry($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                
                return true;
            }
            else {
                throw new \Exception('Wpis o podanym ID nie istnieje');
            }
        }
    }
    
    public function getEntry($id) {
        $id = (int)$id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception('Wpis o ID '.$id.' nie istnieje');
        }
        
        return $row;
    }
    
    public function getNext($created) {    
        $select = new Select('entry');
        $select->columns(array('id', 'title'));
        $select->order('created ASC');
        $select->where('published');
        $select->where->greaterThan('created', $created);
        $select->limit(1);

        $row = $this->tableGateway->selectWith($select);
        
        $row = $row->current();
        
        return $row;
    }
    
    public function getPrev($created) {
        $select = new Select('entry');
        $select->columns(array('id', 'title'));
        $select->order('created DESC');
        $select->where('published');
        $select->where->lessThan('created', $created);
        $select->limit(1);

        $row = $this->tableGateway->selectWith($select);
        
        $row = $row->current();
        
        return $row;
    }
    
    public function getEntryByTitle($entry_title) {
    	$rowset = $this->tableGateway->select(array('title' => $entry_title));
    	$row = $rowset->current();
        
    	if (!$row) 
        {
            $row = NULL;
    	}
        
    	return $row;
    }
    
    public function deleteEntry($id) {
        $this->tableGateway->delete(array('id' => $id));
        
        return true;
    }
    
    public function fetchAll($type = 0) {
        
        switch($type) {
            case 'recent':
                $resultSet = $this->tableGateway->select(function(Select $select){
                    $select->order('created DESC')->limit(3);
                    $select->where('published');
                });   
            break;
            case 'index':
                $resultSet = $this->tableGateway->select(function(Select $select){
                    $select->order('created DESC');
                    $select->where('published');
                });   
            break;
            case 'all':
                $resultSet = $this->tableGateway->select(function(Select $select){
                    $select->order('created DESC');
                });   
            break;
        }

        return $resultSet;
    }
}

?>