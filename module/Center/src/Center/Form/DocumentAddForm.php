<?php

namespace Center\Form;

use Zend\Form\Form;

class DocumentAddForm extends Form {
    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/formdata');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('id', 'upload-document');
        
        $this->add(array(
           'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
        
        $this->add(array(
           'name' => 'id_member',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
        
        $this->add(array(
            'name' => 'id_type',
            'type' => 'DocumentTypeSelect',
            'options' => array(
                'label' => 'Typ danych',
            ),
            'attributes' => array(
                'style' => 'margin:0 10px 0 0;',
                'id' => 'document_type',
                'required' => 'required'
            ),
        ));
        
        $this->add(array(
            'name' => 'id_specialization',
            'type' => 'DocumentSpecializationSelect',
            
            'options' => array(
                'label' => 'Specjalista zlecający',
            ),
            'attributes' => array(
                'style' => 'margin:0 10px 0 0;',
                'id' => 'document_specialization',
                'required' => 'required',
            ),
        ));
        
        
        $this->add(array(
            'name' => 'testing_date',
            'type' => 'date',
            'options' => array(
                'max'  => date("Y-m-d H:i:s"),
                'label' => 'Data'
            ),
            'attributes' => array(
                'required' => 'required',
            ),
        ));
        
        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Opis'
            ),
            'attributes' => array(
                'style' => 'width:100%;height:80px;',
            )
        ));
        
        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Zapisz dokument',
                'class' => 'btn btn-success',
            ),
            'options' => array(
                'label' => 'Ok'
            )
        ));

    }
}

?>