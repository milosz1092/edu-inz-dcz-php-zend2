<?php

namespace Admin\Form\Element;

use Admin\Model\UserRoleTable;
use Zend\Form\Element\Select;

class UserRoleSelect extends Select
{
    public function __construct(UserRoleTable $roleTable)
    {
        $resultSet = $roleTable->fetchAll();

        $dbOptions = array('' => '');
        foreach ($resultSet as $row) {
            if ($row->id != 1)
                $dbOptions[$row->id] = ucfirst($row->name);
        }

        $this->setValueOptions($dbOptions);
    }
}

?>