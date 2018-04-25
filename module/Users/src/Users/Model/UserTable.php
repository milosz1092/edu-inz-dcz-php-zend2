<?php

namespace Users\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class UserTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function saveUser(User $user) {
        $data = array(
            'email' => $user->email,
        );
        
        if ($user->role) {
            $data['role'] = $user->role;
        }
        
        if ($user->password != NULL && $user->password != '')
            $data['password'] = md5($user->password);
        
        $id = (int)$user->id;
        
        if ($id == 0) {
            $this->tableGateway->insert($data);
            return $this->tableGateway->getLastInsertValue();
        }
        else {
            if($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else {
                throw new \Exception('Użytkownik o podanym ID nie istnieje');
            }
        }
    }
    
    public function getUser($id) {
        $id = (int)$id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception('Użytkownik o ID '.$id.' nie istnieje');
        }
        
        return $row;
    }
    
    public function getUserByEmail($user_email) {
    	$rowset = $this->tableGateway->select(array('email' => $user_email));
    	$row = $rowset->current();
        
    	if (!$row) 
        {
            $row = NULL;
    	}
        
    	return $row;
    }
    
    public function deleteUser($id) {
        $this->tableGateway->delete(array('id' => $id));
        
        return true;
    }
    
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        
        return $resultSet;
    }
}

?>