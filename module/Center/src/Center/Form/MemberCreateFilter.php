<?php

namespace Center\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;

class MemberCreateFilter extends InputFilter implements InputFilterAwareInterface
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
               'name' => 'name',
               'required' => true,
               'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'To pole jest wymagane',
                            
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[a-zA-ZęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+$/',
                            'message' => 'Imię składa się tylko z liter',
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 2,
                            'max' => 50,
                            'message' => 'Imię musi zawierać od %min% do %max% znaków',
                        ),  
                    ),
               )
            )));
            
            $inputFilter->add($factory->createInput(array(
               'name' => 'surname',
               'required' => true,
               'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'To pole jest wymagane',
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[a-zA-ZęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+$/',
                            'message' => 'Nazwisko składa się tylko z liter',
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 2,
                            'max' => 50,
                            'message' => 'Nazwisko musi zawierać od %min% do %max% znaków.'
                        ),  
                    ),
               )
            )));
            
            $inputFilter->add($factory->createInput(array(
               'name' => 'sex',
               'required' => true,
               'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'To pole jest wymagane',
                            
                        ),
                        'break_chain_on_failure' => true
                    ),
                   
               )
            )));

            $inputFilter->add($factory->createInput(array(
               'name' => 'growth',
               'required' => true,
               'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'To pole jest wymagane',
                            
                        ),
                        
                    ),
                    array(
                        'name' => 'Step',
                        'options' => array(
                            'step' => 0.5,
                            'message' => 'Niepoprawny format wartości',
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 30,
                            'max' => 300,
                            'message' => 'Wzrost musi zawierać się w przedziale od %min% do %max% kg',
                        ),  
                    ),
               )
            )));
            
            $inputFilter->add($factory->createInput(array(
               'name' => 'weight',
               'required' => true,
               'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'To pole jest wymagane',
                        ),
                        
                    ),
                    array(
                        'name' => 'Step',
                        'options' => array(
                            'step' => 0.1,
                            'message' => 'Niepoprawny format wartości',
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 0.5,
                            'max' => 300,
                            'message' => 'Waga musi zawierać się w przedziale od %min% do %max% kg',
                        ),  
                    ),
               )
            )));
            
            $inputFilter->add($factory->createInput(array(
               'name' => 'birth',
               'required' => true,
               'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'To pole jest wymagane',
                        ),
                        
                    ),
                    array(
                        'name' => 'Date',
                        'options' => array(
                            'message' => 'Nieprowidłowy format daty',
                        ),
                        'break_chain_on_failure' => true
                    ),
                    array(
                        'name' => 'Center\Validator\DateLessThanValidator',
                        'options' => array(
                            'max' => date("Y-m-d"),
                            'message' => 'Musisz wprowadzić datę z przeszłości'
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