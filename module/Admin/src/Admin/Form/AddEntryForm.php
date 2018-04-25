<?php

namespace Admin\Form;

use Zend\Form\Form;

class AddEntryForm extends Form {
    
    public function init()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/formdata');
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('id', 'add-entry-form');
        
        $this->add(array(
           'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
        
        $this->add(array(
           'name' => 'author',
            'attributes' => array(
                'type' => 'hidden'
            ),
        ));
        
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'options' => array(
                'label' => 'Tytuł'
            )
        ));
        
        $this->add(array(
            'name' => 'section_id',
            'type' => 'EntrySectionSelect',
            'options' => array(
                'label' => 'Dział',
            ),
            'attributes' => array(
                'id' => 'entry_section'
            ),
        ));
        
        $this->add(array(
            'name' => 'summary',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Wstęp'
            )
        )); 
        
        $this->add(array(
            'name' => 'content',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Treść'
            ),
            'attributes' => array(
                'id' => 'entry_edit'
            ),
        ));
        
        $this->add(array(
            'name' => 'published',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Widoczność',
            )
        )); 
        
        $this->add(array(
            'name' => 'photo',
            'type' => 'text',
            'options' => array(
                'label' => 'Zdjęcie',
            ),
            'attributes' => array(
                'id' => 'entry_photo'
            ),
        )); 
        
        /*$this->add(array(
            'name' => 'tag',
            'type' => 'EntryTagMultiSelect',
            'options' => array(
                'label' => 'Tagi',
            ),
            'attributes' => array(
                'multiple' => 'multiple',
            ),
        ));*/
        
        $this->add(array(
           'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Zapisz wpis',
                'class' => 'btn btn-success',
            ),
            'options' => array(
                'label' => 'Ok'
            )
        ));

    }
}

?>