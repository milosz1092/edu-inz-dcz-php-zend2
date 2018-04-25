<?php

namespace Center\Form;

use Zend\Form\Form;

class MemberEditForm extends Form {
    public function init()
    {
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
           'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Zapisz',
                'class' => 'btn btn-success',
            ),
            'options' => array(
                'label' => 'Ok'
            )
        ));
    }
}

?>