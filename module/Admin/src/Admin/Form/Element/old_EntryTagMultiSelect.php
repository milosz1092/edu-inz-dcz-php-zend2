<?php

namespace Admin\Form\Element;

use Admin\Model\EntryTagTable;
use Zend\Form\Element\Select;

class EntryTagMultiSelect extends Select
{
    public function __construct(EntryTagTable $tagTable)
    {
        $resultSet = $tagTable->fetchAll();

        
        foreach ($resultSet as $row) {
            $dbOptions[$row->id] = ucfirst($row->name);
        }

        $this->setValueOptions($dbOptions);
    }
}

?>