<?php

namespace Chat\Model;

class Request
{
    public $ss_id;
    public $user;
    public $ip;
    public $last_time_user;
    public $browser;
    public $adviser;
    public $last_time_adviser;
    
    public function exchangeArray($data) {
        $this->ss_id = (isset($data['ss_id'])) ? $data['ss_id'] : null;
        $this->user = (isset($data['user'])) ? $data['user'] : null;
        $this->ip = (isset($data['ip'])) ? $data['ip'] : null;
        $this->last_time_user = (isset($data['last_time_user'])) ? $data['last_time_user'] : null;
        $this->browser = (isset($data['browser'])) ? $data['browser'] : null;
        $this->adviser = (isset($data['adviser'])) ? $data['adviser'] : null;
        $this->last_time_adviser = (isset($data['last_time_adviser'])) ? $data['last_time_adviser'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
}

?>