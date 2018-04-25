<?php

namespace Chat\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Chat\Model\Request;

use Zend\Db\Sql\Update;
use Zend\Db\Sql\Select;

class RequestTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function addUserRequest(Request $request) {

        $data = array(
            'ss_id' => $request->ss_id,
            'user' => $request->user,
            'ip' => $request->ip,
            'last_time_user' => $request->last_time_user,
            'browser' => $request->browser
        );

        // check record exists
        $select = new Select('chat_requests');
        $select->where(array('ss_id' => $request->ss_id));
        $select->columns(array('user'));
        $select->limit(1);
        try {
            $rowset = $this->tableGateway->selectWith($select);
        } catch (Exception $ex) {
            echo 'Wystąpił błąd!';
        }
        
        
        // insert or update
        if (count($rowset) == 0) {
            try {
                $this->tableGateway->insert($data);
            }
            catch (Exception $e) {
                print_r('Wystąpił błąd!');
            }
        } else if (count($rowset) > 0) {
            try {
                $this->tableGateway->update($data, array('ss_id' => $request->ss_id));
            }
            catch (Exception $e) {
                print_r('Wystąpił błąd!');
            }
        }
    }
    
    public function setUserLastTime(Request $request) {
        $data = array(
            'last_time_user' => $request->last_time_user,
        );

        $this->tableGateway->update($data, array('ss_id' => $request->ss_id));
    }
    
    public function disconnectUser($ss_id) {
        $data['last_time_user'] = 0;
        $this->tableGateway->update($data, array('ss_id' => $ss_id));
    }
    
    public function disconnectAdviser($ss_id) {
        $data = array(
            'adviser' => 0,
            'last_time_adviser' => 0
        );

        try {
            $this->tableGateway->update($data, array('ss_id' => $ss_id));
        } catch (Exception $ex) {
            echo 'Wystąpił błąd!';
        }
    }
    
    public function refreshAdviser($date) {
        $update = new Update('chat_requests');
        $update->set(array('adviser' => 0));
        $update->where->lessThan('last_time_adviser', $date);

        try {
            $this->tableGateway->updateWith($update);
        } catch (Exception $ex) {
            echo 'Wystąpił błąd!';
        }
    }
    
    public function receiveCall($ss_id, $adviser_id, $last_time) {
        $update = new Update('chat_requests');
        $update->set(array('adviser' => $adviser_id, 'last_time_adviser' => $last_time));
        $update->where(array('ss_id' => $ss_id));
        $update->where('adviser = 0 OR adviser = '.$adviser_id);
        

        try {
            $this->tableGateway->updateWith($update);
        } catch (Exception $ex) {
            echo 'Wystąpił błąd!';
        }
    }
    
    public function checkEmptyCall($ss_id) {
        $select = new Select('chat_requests');
        $select->where(array('ss_id' => $ss_id, 'adviser' => 0));
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
        } catch (Exception $ex) {
            echo 'Wystąpił błąd!';
        }
        
        $select1 = new Select('chat_requests');
        $select1->where(array('ss_id' => $ss_id));
        
        try {
            $rowset1 = $this->tableGateway->selectWith($select1);
        } catch (Exception $ex) {
            echo 'Wystąpił błąd!';
        }
        
        if (count($rowset1) != 0) {
            $row = $rowset1->current();
            $adviser = $row->adviser;
        }
        else {
            $adviser = 0;
        }
        
        return array(count($rowset), $adviser);
    }
    
    public function checkAdviserConnected($ss_id) {
        $select = new Select('chat_requests');
        $select->where(array('ss_id' => $ss_id));
        $select->where->greaterThan('adviser', 0);
        
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }

        if (count($rowset) == 0)
            return 'sig_notconnected';
        else if (count($rowset) > 0)
            return 'sig_connected';
    }
    
    public function checkUserConnected($ss_id, $timestamp) {
        $select = new Select('chat_requests');
        $select->where->greaterThan('last_time_user', $timestamp);
        $select->where(array('ss_id' => $ss_id));
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }

        if (count($rowset) == 0)
            return 'sig_notconnected';
        else if (count($rowset) > 0)
            return 'sig_connected';
    }
    
    
    public function getWaitingCalls($date) {
        $select = new Select('chat_requests');
        $select->where->greaterThan('last_time_user', $date);
        $select->where(array('adviser' => 0));
        $select->join('user', "user.id = chat_requests.user", 'email', $select::JOIN_LEFT);

        try {
            $rowset = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }
        
        return $rowset;
    }
    
    public function getSessionInfo($ss_id) {
        $select = new Select('chat_requests');
        $select->where(array('ss_id' => $ss_id));

        $select->join('user', "user.id = chat_requests.user", array('email', 'role'), $select::JOIN_LEFT)
            ->join('role', "role.id = user.role", array('role_name' => 'name'), $select::JOIN_LEFT);
        
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }
        
        return $rowset->current();
    }
    
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        
        return $resultSet;
    }
}

?>