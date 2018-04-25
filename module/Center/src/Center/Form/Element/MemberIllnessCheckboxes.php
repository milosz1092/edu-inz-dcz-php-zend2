<?php

namespace Center\Form\Element;

use Center\Model\IllnessTable;
use Zend\Form\Element\MultiCheckbox;

class MemberIllnessCheckboxes extends MultiCheckbox
{
    public function __construct(IllnessTable $illnessTable)
    {
        $resultSet = $illnessTable->fetchAll();

        $dbOptions = array();
        foreach ($resultSet as $row) {
            $dbOptions[$row->id] = ucfirst($row->name);
        }
        $this->setValueOptions($dbOptions);
    }
}

?>