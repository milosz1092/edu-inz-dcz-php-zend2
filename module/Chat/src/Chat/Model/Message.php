<?php

namespace Chat\Model;

class Message
{
    public $msg_id;
    public $req_id;
    public $from_type;
    public $time;
    public $content;
    
    public function exchangeArray($data) {
        $this->msg_id = (isset($data['msg_id'])) ? $data['msg_id'] : null;
        $this->req_id = (isset($data['req_id'])) ? $data['req_id'] : null;
        $this->from_type = (isset($data['from_type'])) ? $data['from_type'] : null;
        $this->time = (isset($data['time'])) ? $data['time'] : null;
        $this->content = (isset($data['content'])) ? $data['content'] : null;
    }
    
    public function getArrayCopy(){
        return get_object_vars($this);
    }
}

?>