<?php

namespace Center\Model;
use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Select;
use Center\Model\Member;

class MemberTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function saveMember(Member $member, $user_id = 0) {
        $data = array(
            'name' => $member->name,
            'surname' => $member->surname,
            'sex' => $member->sex,
            'birth' => $member->birth,
            'last_edit' => date("Y-m-d H:i:s"),
        );
        
        if ($member->growth != NULL)
            $data['growth'] = $member->growth;
        
        if ($member->weight != NULL)
            $data['weight'] = $member->weight;
        
        $id = (int)$member->id;
        
        if ($id == 0) {
            $data['user_id'] = $user_id;
            $data['created'] = date("Y-m-d H:i:s");
            
            $this->tableGateway->insert($data);
            
            return $this->tableGateway->getLastInsertValue();
        }
        else {
            
            if($this->getMember($id)) {
                $this->tableGateway->update($data, array('id' => $id));
                
                return true;
            }
            else {
                throw new \Exception('Członek rodziny o podanym ID nie istnieje');
            }
            return true;
        }
    }
    
    public function getMember($id) {
        $id = (int)$id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row) {
            throw new \Exception('Osoba o ID '.$id.' nie istnieje');
        }
        
        return $row;
    }
    
    public function checkPermission($member_id, $user_id) {
        $select = new Select('member');
        $select->columns(array('id'));
        $where['user_id'] = $user_id;
        $where['id'] = $member_id;
        
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
    
    public function getUserMembers($user_id) {  
        $select = new Select('member');
        $where['user_id'] = $user_id;
        
        $select->where($where);
        $select->order(array('birth' => 'ASC'));
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }
        
        return $rowset;
    }
    
    public function deleteMember($id) {
        $this->tableGateway->delete(array('id' => $id));
        
        return true;
    }
    
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        
        return $resultSet;
    }
}
