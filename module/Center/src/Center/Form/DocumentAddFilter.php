<?php

namespace Center\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Db\Adapter\Adapter;

class DocumentAddFilter extends InputFilter implements InputFilterAwareInterface
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
               'name' => 'id_type',
               'required' => true,
               'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'To pole jest wymagane',
                        ),
                        //'break_chain_on_failure' => true
                    ),
               )
            )));
            
            $inputFilter->add($factory->createInput(array(
               'name' => 'id_specialization',
               'required' => true,
               'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'message' => 'To pole jest wymagane',
                        ),
                        //'break_chain_on_failure' => true
                    ),
               )
            )));
            
            
            $inputFilter->add($factory->createInput(array(
               'name' => 'testing_date',
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