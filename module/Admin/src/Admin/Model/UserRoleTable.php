<?php

namespace Admin\Model;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Select;

use Admin\Model\UserRole;

class UserRoleTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function getUserRole($id) {
        $id = (int)$id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception('Rola o ID '.$id.' nie istnieje');
        }
        
        return $row;
    }
    
    public function getUserRoleByName($user_role_name) {
    	$rowset = $this->tableGateway->select(array('name' => $user_role_name));
    	$row = $rowset->current();
        
    	if (!$row) 
        {
            $row = NULL;
    	}
        
    	return $row;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select(function(Select $select){
            $select->order('name ASC');
        });
        return $resultSet;
    }
}

?>