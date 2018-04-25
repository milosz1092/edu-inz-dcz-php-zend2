<?php

namespace Admin\Form\Element;

use Admin\Model\EntrySectionTable;
use Zend\Form\Element\Select;

class EntrySectionSelect extends Select
{
    public function __construct(EntrySectionTable $sectionTable)
    {
        $resultSet = $sectionTable->fetchAll();

        $dbOptions = array(0 => '');
        foreach ($resultSet as $row) {
            $dbOptions[$row->id] = ucfirst($row->name);
        }
        
        $this->setValueOptions($dbOptions);
    }
}

?>