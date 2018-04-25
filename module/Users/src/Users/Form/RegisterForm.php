<?php

namespace Users\Form;

use Zend\Form\Form;

class RegisterForm extends Form {
    public function __construct($name = null, $options = array()) {
        parent::__construct('Register');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/formdata');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
           'name' => 'email',
            'attributes' => array(
                'type' => 'email'
            ),
            'options' => array(
                'label' => 'E-mail'
            )
        ));
        
        $this->add(array(
           'name' => 'password',
            'attributes' => array(
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'Hasło'
            )
        ));
        
        $this->add(array(
           'name' => 'confirm_password',
            'attributes' => array(
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'Powtórz hasło'
            )
        ));
        
        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Zarejestruj',
                'class' => 'btn btn-default',
            ),
            'options' => array(
                'label' => 'Ok'
            )
        ));
    }
}

?>