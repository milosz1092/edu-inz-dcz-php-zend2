<?php

namespace Admin\Model;

class Medical
{
    public $id;
    public $name;
    public $latin_name;
    public $description;
    
    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->latin_name = (isset($data['latin_name'])) ? $data['latin_name'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
    }
    
    public function getArrayCopy() {
        return get_object_vars($this);
    }
}

?>