<?php

namespace Admin\Model;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Select;

use Admin\Model\EntrySection;

class EntrySectionTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function saveEntrySection(EntrySection $entry_section) {
        $data = array(
            'name' => $entry_section->name,
            //'role' => $user->role,
        );
        
        $id = (int)$entry_section->id;
        
        if ($id == 0) {
            $this->tableGateway->insert($data);
        }
        else {
            if($this->getEntrySection($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else {
                throw new \Exception('Dział o podanym ID nie istnieje');
            }
        }
    }
    
    public function getEntrySection($id) {
        $id = (int)$id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception('Dział o ID '.$id.' nie istnieje');
        }
        
        return $row;
    }
    
    public function getEntrySectionByName($entry_section_name) {
    	$rowset = $this->tableGateway->select(array('name' => $entry_section_name));
    	$row = $rowset->current();
        
    	if (!$row) 
        {
            $row = NULL;
    	}
        
    	return $row;
    }
    
    public function deleteEntrySection($id) {
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