<?php

namespace Center\Form\Element;

use Admin\Model\TaxonomiesTable;
use Zend\Form\Element\Select;

class DocumentSpecializationSelect extends Select
{
    public function __construct(TaxonomiesTable $taxTable)
    {
        $resultSet = $taxTable->getAllTax('specialization');

        $dbOptions = array('' => '');
        foreach ($resultSet as $row) {
            $dbOptions[$row->id] = ucfirst($row->name);
        }
        
        $dbOptions['x'] = 'Nie dotyczy';
        $this->setValueOptions($dbOptions);
        
    }
    
    
}

?>