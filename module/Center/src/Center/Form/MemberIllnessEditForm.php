<?php

namespace Center\Form;

use Zend\Form\Form;

class MemberIllnessEditForm extends Form {
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
            'name' => 'illnesses',
            'type' => 'MemberIllnessCheckboxes',
            
            'options' => array(
                'label' => 'Choroby',
            ),
            'attributes' => array(
                'class' => 'option-input',
                'style' => 'margin:0 10px 0 0;'
            ),
        ));
        
        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Aktualizuj',
                'class' => 'btn btn-success',
            ),
            'options' => array(
                'label' => 'Ok'
            )
        ));
    }
}

?>