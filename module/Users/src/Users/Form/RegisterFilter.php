<?php

namespace Users\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;

class RegisterFilter extends InputFilter implements InputFilterAwareInterface
{

    protected $inputFilter;
    protected $dbAdapter;

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function __construct(Adapter $dbAdapter) {
        $this->dbAdapter = $dbAdapter;
    }

    public function getDbAdapter() {
        return $this->dbAdapter;
    }
    
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
               'name' => 'email',
               'required' => true,
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
                    array(
                        'name' => 'Db\NoRecordExists',
                        'options' => array(
                            'message' => 'Podany adres e-mail jest zajęty',
                            'table'   => 'user',
                            'field'   => 'email',
                            'adapter' => $this->dbAdapter
                        ),
                    ),
               )
            )));

            $inputFilter->add($factory->createInput(array(
               'name' => 'password',
               'required' => true,
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
            )));

            $inputFilter->add($factory->createInput(array(
               'name' => 'confirm_password',
               'required' => true,
               'validators' => array(
                   array(
                       'name' => 'Identical',
                       'options' => array(
                           'token' => 'password',
                            'messages' => array(
                                \Zend\Validator\Identical::NOT_SAME => 'Podane hasła nie są identyczne',
                            )
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
            )));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
    
}

?>