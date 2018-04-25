<?php

namespace Center\Model;

class Document
{
    public $id;
    public $id_member;
    public $id_type;
    public $id_specialization;
    public $testing_date;
    public $description;
    
    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->id_member = (isset($data['id_member'])) ? $data['id_member'] : null;
        $this->id_type = (isset($data['id_type'])) ? $data['id_type'] : null;
        $this->id_specialization = (isset($data['id_specialization'])) ? $data['id_specialization'] : null;
        $this->testing_date = (isset($data['testing_date'])) ? $data['testing_date'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
}

?>