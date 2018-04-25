<?php

namespace Chat\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Chat\Model\Message;

use Zend\Db\Sql\Select;

class MessageTable
{
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    
    public function sendMsg(Message $msg) {
        $data = array(
            'req_id' => $msg->req_id,
            'from_type' => $msg->from_type,
            'time' => $msg->time,
            'content' => $msg->content,
        );
        
        try {
            $this->tableGateway->insert($data);
        }
        catch (Exception $e) {
            return 'fail';
        }
        
        return $this->tableGateway->lastInsertValue;
    }
    
    public function getMsg($ss_id, $from, $last) {
        $select = new Select('chat_messages');
        
        $where['req_id'] = $ss_id;
        if ($from != 'x')
            $where['from_type'] = $from;
        
        $select->where($where);
        $select->where->greaterThan('msg_id', $last);
        $select->order(array('msg_id' => 'ASC'));
        
        try {
            $rowset = $this->tableGateway->selectWith($select);
        }
        catch (Exception $e) {
            echo 'Wystąpił błąd!';
        }
        
        return $rowset;
    }
    
    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        
        return $resultSet;
    }
}

?>