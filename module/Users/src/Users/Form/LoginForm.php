<?php

namespace Users\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null, $options = array()) {
        parent::__construct('users\form');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/formdata');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
           'name' => 'email',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'type' => 'email',
            ),
            'options' => array(
                'label' => 'E-mail'
            )
        ));
        
        $this->add(array(
           'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'type' => 'password'
            ),
            'options' => array(
                'label' => 'Hasło'
            )
        ));
        
        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Zaloguj',
                'class' => 'btn btn-default',
            ),
            'options' => array(
                'label' => 'Ok'
            )
        ));
    }
}

?>