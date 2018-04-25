<?php

namespace Admin\Form;

use Zend\Form\Form;

class UserEditForm extends Form {
    public function init() {
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
           'name' => 'email',
            'attributes' => array(
                'type' => 'email'
            ),
            'options' => array(
                'label' => 'E-mail'
            )
        ));
        
        $this->add(array(
           'name' => 'new_password',
            'attributes' => array(
                'type' => 'password',
                'value' => '',
                'autocomplete' => 'off',
            ),
            'options' => array(
                'label' => 'Nowe hasło'
            )
        ));

        $this->add(array(
            'name' => 'role',
            'type' => 'UserRoleSelect',
            'options' => array(
                'label' => 'Uprawnienia',
            ),
            'attributes' => array(
                'style' => 'margin:0 10px 0 0;',
                'required' => 'required',
                'id' => 'user_role'
            ),
        ));

        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Edytuj',
                'class' => 'btn btn-default',
            ),
            'options' => array(
                'label' => 'Ok'
            )
        ));
    }
}

?>