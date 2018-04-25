<?php

namespace Admin\Model;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Select;

use Admin\Model\EntryTag;

class EntryTagTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function saveEntryTag(EntryTag $entry_tag) {
        $data = array(
            'name' => $entry_tag->name,
        );
        
        $id = (int)$entry_tag->id;
        
        if ($id == 0) {
            $this->tableGateway->insert($data);
        }
        else {
            if($this->getEntryTag($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else {
                throw new \Exception('Tag o podanym ID nie istnieje');
            }
        }
    }
    
    public function getEntryTag($id) {
        $id = (int)$id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception('Tag o ID '.$id.' nie istnieje');
        }
        
        return $row;
    }
    
    public function getEntryTagByName($entry_tag_name) {
    	$rowset = $this->tableGateway->select(array('name' => $entry_tag_name));
    	$row = $rowset->current();
        
    	if (!$row) 
        {
            $row = NULL;
    	}
        
    	return $row;
    }
    
    public function deleteEntryTag($id) {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function fetchAll() {
        $resultSet = $this->tableGateway->select(function(Select $select){
            $select->order('name ASC');
        });
        
        return $resultSet;
    }
}

?>