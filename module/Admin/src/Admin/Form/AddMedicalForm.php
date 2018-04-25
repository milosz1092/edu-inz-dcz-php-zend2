<?php

namespace Admin\Form;

use Zend\Form\Form;

class AddMedicalForm extends Form {
    public function __construct($name = null, $options = array()) {
        parent::__construct('AddMedical');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/formdata');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
           'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Nazwa',
            )
        ));
        
        $this->add(array(
            'name' => 'latin_name',
            'type' => 'text',
            'options' => array(
                'label' => 'Nazwa łacińska'
            )
        ));
        
        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Opis'
            ),
            'attributes' => array(
                'style' => 'width:100%;height:80px;',
            )
        ));

        
        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Dodaj',
                'class' => 'btn btn-default',
            ),
            'options' => array(
                'label' => 'Ok'
            )
        ));
    }
}

?>