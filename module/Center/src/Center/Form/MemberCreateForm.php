<?php

namespace Center\Form;

use Zend\Form\Form;

class MemberCreateForm extends Form {
    public function __construct($name = null, $options = array()) {
        parent::__construct('MemberCreate');
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
                'label' => 'Imię',
            )
        ));
        
        $this->add(array(
            'name' => 'surname',
            'type' => 'text',
            'options' => array(
                'label' => 'Nazwisko'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'sex',
            'options' => array(
                'disable_inarray_validator' => true,
                'label' => 'Płeć',
                'value_options' => array(
                    'k' => 'Kobieta',
                    'm' => 'Mężczyzna',
                ),
                'label_attributes' => [
                    'class'  => 'radio-label',
                ],
            ),
        ));
        
        $this->add(array(
            'name' => 'birth',
            'type' => 'date',
            'options' => array(
                'max'  => date("Y-m-d H:i:s"),
                'label' => 'Data urodzenia'
            )
        ));
        
        $this->add(array(
            'name' => 'growth',
            'type' => 'number',
            'options' => array(
                'label' => 'Wzrost',
            ),
            'attributes' => array(
                'style' => 'width:70px;',
                'step' => 0.5,
                'min' => 30,
                'max' => 300,
                'value' => 160
            ),
        ));
        
        $this->add(array(
            'name' => 'weight',
            'type' => 'number',
            
            'options' => array(
                'label' => 'Waga',
                
            ),
            'attributes' => array(
                'style' => 'width:70px;',
                'step' => 0.1,
                'min' => 0.5,
                'max' => 300,
                'value' => 50
            ),
            
        ));
        
        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Dodaj osobę',
                'class' => 'btn btn-default',
            ),
            'options' => array(
                'label' => 'Ok'
            )
        ));
    }
}

?>