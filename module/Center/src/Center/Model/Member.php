<?php

namespace Center\Model;

class Member
{
    public $id;
    public $name;
    public $surname;
    public $sex;
    public $birth;
    public $growth;
    public $weight;
    public $created;
    public $last_edit;
    
    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->surname = (isset($data['surname'])) ? $data['surname'] : null;
        $this->sex = (isset($data['sex'])) ? $data['sex'] : null;
        $this->birth = (isset($data['birth'])) ? $data['birth'] : null;
        $this->growth = (isset($data['growth'])) ? $data['growth'] : null;
        $this->weight = (isset($data['weight'])) ? $data['weight'] : null;
        $this->created = (isset($data['created'])) ? $data['created'] : null;
        $this->last_edit = (isset($data['last_edit'])) ? $data['last_edit'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
}

?>