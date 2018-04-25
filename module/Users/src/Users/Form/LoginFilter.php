<?php

namespace Users\Form;
use Zend\InputFilter\InputFilter;

class LoginFilter extends InputFilter
{
    public function __construct() {
        $this->add(array(
           'name' => 'email',
           'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
           'validators' => array(
               array(
                   'name' => 'EmailAddress',
                   'options' => array(
                       'domain' => true,
                        'message' => 'Nieprawidłowy adres e-mail'
                   )
               ),
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'To pole jest wymagane',
                        )
                    ),
                ),
           )
        ));
        
        $this->add(array(
           'name' => 'password',
           'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
           'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'To pole jest wymagane',
                        )
                    ),
                )
           )
        ));

    }
}

?>