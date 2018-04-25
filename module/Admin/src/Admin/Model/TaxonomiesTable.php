<?php

namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Select;
use Zend\Paginator\Paginator;

use Admin\Model\Medical;
use Admin\Model\DefaultTax;


class TaxonomiesTable
{   
    protected $tableGateway;
    
    public function __construct($context, $adapter) {
        $dbAdapter = $adapter;
        $resultSetPrototype = new ResultSet();
        $this->tableGateway = new TableGateway($context, $dbAdapter, null, $resultSetPrototype);
    }
    
    public function saveMedical(Medical $medical, $table) {
        $data = array(
            'name' => $medical->name,
            'latin_name' => $medical->latin_name,
            'description' => $medical->description,
        );
        
        $id = (int)$medical->id;
        
        if ($id == 0) {
            $insert = new Insert($table);
            $insert->values($data);
            
            $this->tableGateway->insertWith($insert);
        }
        else {
            if($this->issetTax($id, $table)) {
                $update = new Update($table);
                $update->set($data);
                $update->where(array('id' => $id));
                
                $this->tableGateway->updateWith($update);
            }
            else {
                throw new \Exception('Pozycja o podanym ID nie istnieje');
            }
        }
    }
    
    public function saveDefault(DefaultTax $defaultTax, $table) {
        $data = array(
            'name' => $defaultTax->name,
        );
        
        $id = (int)$defaultTax->id;
        
        if ($id == 0) {
            $insert = new Insert($table);
            $insert->values($data);
            
            $this->tableGateway->insertWith($insert);
        }
        else {
            if($this->issetTax($id, $table)) {
                $update = new Update($table);
                $update->set($data);
                $update->where(array('id' => $id));
                
                $this->tableGateway->updateWith($update);
            }
            else {
                throw new \Exception('Pozycja o podanym ID nie istnieje');
            }
        }
    }
    
    public function issetTax($id, $table) {
        $select = new Select($table);
        $select->columns(array('name'));
        $where['id'] = $id;
        
        $select->where($where);
        $select->limit(1);
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }
        
        return count($rowset);
    }
    
    public function getAllTax($table, $items_per_page = 999999) {
        $select = new Select($table);
        $select->order('name ASC');
        $resultSetPrototype = new ResultSet();

        $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);
        
        $paginator = new Paginator($paginatorAdapter);
        
        $page = 1;
        
        $paginator->setItemCountPerPage($items_per_page);
        
        return $paginator;
    }
    
    public function getTax($table, $id) {
        $select = new Select($table);
        $where['id'] = $id;
        
        $select->where($where);
        $select->limit(1);
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
            return $rowset->current();
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }
    }
    
    public function deleteTax($id) {
        try {
            $this->tableGateway->delete(array('id' => $id));
        } catch (Exception $e) {
            return false;
        }
        
        return true;
    }
    
}
