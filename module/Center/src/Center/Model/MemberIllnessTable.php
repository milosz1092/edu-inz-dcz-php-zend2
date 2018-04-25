<?php

namespace Center\Model;
use Zend\Db\TableGateway\TableGateway;

use Zend\Db\Sql\Select;
use Center\Model\Member;

class MemberIllnessTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function updateIllnesses($illnessesArray, $member_id) {
        $this->tableGateway->delete(array('member_id' => $member_id));
        
        $addedIllnessesArray = array();
        
        $i = 0;
        foreach ($illnessesArray as $key => $illness_id) {
            $data = array (
                'member_id' => $member_id,
                'illness_id' => $illness_id
            );
            
            $this->tableGateway->insert($data);
            $addedIllnessesArray["illnesses"][$i] = $illness_id;
            $i++;
        }
        return $addedIllnessesArray;
    }
    
    public function getIllnesses($member_id) {
        $select = new Select('member_illness');
        $select->columns(array('illness_id'));
        $where['member_id'] = $member_id;
        
        $select->where($where);
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }

        
        $memberIllnessesArray = array();
        
        $i = 0;
        foreach ($rowset as $row) {
            $memberIllnessesArray["illnesses"][$i] = $row->illness_id;
            $i++;
        }
        return $memberIllnessesArray;

    }
    
    public function getIllnessesString($member_id) {
        $select = new Select('member_illness');
        $select->columns(array('illness_id'));
        $where['member_id'] = $member_id;
        $select->join('illness', "illness.id = member_illness.illness_id", array('illness_name' => 'name'), $select::JOIN_LEFT);
        
        $select->where($where);
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }

        
        $memberIllnessesArray = array();
        
        $i = 0;
        foreach ($rowset as $row) {
            $memberIllnessesArray["illnesses"][$i][0] = $row->illness_id;
            $memberIllnessesArray["illnesses"][$i][1] = $row->illness_name;
            $i++;
        }
        return $memberIllnessesArray;

    }
    
    public function deleteMemberIllnesses($id) {
        $this->tableGateway->delete(array('member_id' => $id));
        
        return true;
    }
    
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        
        return $resultSet;
    }
}
