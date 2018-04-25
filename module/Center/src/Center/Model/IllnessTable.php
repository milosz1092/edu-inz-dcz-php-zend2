<?php

namespace Center\Model;
use Zend\Db\TableGateway\TableGateway;

use Zend\Db\ResultSet\ResultSet;

use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Center\Model\Illness;


class IllnessTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function saveIllness(Illness $illness) {
        $data = array(
            'name' => $illness->name,
            'latin_name' => $illness->latin_name,
            'description' => $illness->description,
        );
        
        $id = (int)$illness->id;
        
        if ($id == 0) {
            $this->tableGateway->insert($data);
            
            return $this->tableGateway->getLastInsertValue();
        }
        else {
            if($this->getIllness($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                
                return true;
            }
            else {
                throw new \Exception('Choroba o podanym ID nie istnieje');
            }
        }
    }
    
    public function getAllIllnesses($items_per_page = 99999) {
        $select = new Select('illness');
        $select->order('name ASC');
        $resultSetPrototype = new ResultSet();

        $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);
        
        $paginator = new Paginator($paginatorAdapter);
        
        $page = 1;
        
        $paginator->setItemCountPerPage($items_per_page);
        
        return $paginator;
    }
    
    
    public function deleteIllness($id) {
        
        try {
            $this->tableGateway->delete(array('id' => $id));
        } catch (Exception $e) {
            return false;
        }
        
        return true;
    }
    
    public function getIllness($id) {
        $id = (int)$id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception('Wpis o ID '.$id.' nie istnieje');
        }
        
        return $row;
    }
    
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        
        return $resultSet;
    }
}
