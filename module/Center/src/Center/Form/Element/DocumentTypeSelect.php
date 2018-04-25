<?php

namespace Center\Form\Element;

use Admin\Model\TaxonomiesTable;
use Zend\Form\Element\Select;

class DocumentTypeSelect extends Select
{
    public function __construct(TaxonomiesTable $taxTable)
    {
        $resultSet = $taxTable->getAllTax('document_type');

        $dbOptions = array('' => '');
        foreach ($resultSet as $row) {
            $dbOptions[$row->id] = ucfirst($row->name);
        }
        
        $this->setValueOptions($dbOptions);
    }
}

?>