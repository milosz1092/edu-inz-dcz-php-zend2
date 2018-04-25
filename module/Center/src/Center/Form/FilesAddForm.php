<?php

namespace Center\Form;

use Zend\Form\Form;

class FilesAddForm extends Form {
    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/formdata');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('id', 'upload-document');
        
        $this->add(array(
           'name' => 'id_document',
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
            'name' => 'files',
            'type' => 'file',
            
            'options' => array(
                'label' => 'Pliki',
            ),
            'attributes' => array(
                'style' => 'display:inline-block;width:200px',
                'multiple' => true,
                'class' => 'custom-file-input chosen btn btn-warning',
                'id' => 'document_files',
                'required' => 'required',
            ),  
        ));
        
        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
                'type' => 'button',
                'value' => 'Wyślij pliki',
                'class' => 'btn btn-success',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Ok'
            ),

        ));

    }
}

?>