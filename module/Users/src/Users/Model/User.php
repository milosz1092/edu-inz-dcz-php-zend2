<?php

namespace Users\Model;

class User
{
    public $id;
    public $email;
    public $password;
    public $role;
    
    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        
        if (isset($data['password']) && $data['password'] != '')
            $this->password = $data['password'];
        
        if (isset($data['role']) && $data['role'] != '')
            $this->role = $data['role'];
    }
    
    public function getArrayCopy(){
        return get_object_vars($this);
    }
}

?>