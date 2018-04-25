<?php
namespace Admin;

use Admin\Form\UserEditFilter;
use Admin\Form\UserEditForm;

use Admin\Form\AddMedicalFilter;
use Admin\Form\AddDefaultFilter;

use Admin\Model\EntrySection;
use Admin\Model\EntrySectionTable;
use Admin\Model\EntryTag;
use Admin\Model\Entry;
use Admin\Model\EntryTable;
use Admin\Model\TaxonomiesTable;
use Admin\Model\UserRole;
use Admin\Model\UserRoleTable;

use Application\Form\AlbumFieldset;
use Zend\ModuleManager\Feature\FormElementProviderInterface;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements FormElementProviderInterface
{
    
    public function getFormElementConfig()
    {
        // Creating own form elements
        return array(
            'factories' => array(
                
                'EntrySectionSelect' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
                    
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new EntrySection());
                    
                    $tabGate = new TableGateway('entry_section', $dbAdapter, null, $resultSetPrototype);
                    
                    $sectionTable = new \Admin\Model\EntrySectionTable($tabGate);

                    $EntrySectionSelect = new \Admin\Form\Element\EntrySectionSelect($sectionTable);
                    
                    return $EntrySectionSelect;
                },      
                'UserRoleSelect' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
                    
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserRole());

                    $tabGate = new TableGateway('role', $dbAdapter, null, $resultSetPrototype);
                    
                    $roleTable = new \Admin\Model\UserRoleTable($tabGate);

                    $UserRoleSelect = new \Admin\Form\Element\UserRoleSelect($roleTable);
                    
                    return $UserRoleSelect;
                },
                       
                /*'EntryTagMultiSelect' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
                    
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new EntryTag());
                    
                    $tabGate = new TableGateway('entry_tag', $dbAdapter, null, $resultSetPrototype);
                    
                    $tagTable = new \Admin\Model\EntryTagTable($tabGate);

                    $EntryTagMultiSelect = new \Admin\Form\Element\EntryTagMultiSelect($tagTable);
                    
                    return $EntryTagMultiSelect;
                }*/
            )
        );
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig() {
        return array(
            'factories' => array(

                'EntryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('entry', $dbAdapter, null, $resultSetPrototype);
                },
                'EntryTable' =>  function($sm) {
                    $tableGateway = $sm->get('EntryTableGateway');
                    $table = new EntryTable($tableGateway);
                    return $table;
                },
                'EntrySectionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('entry_section', $dbAdapter, null, $resultSetPrototype);
                },
                'EntrySectionTable' =>  function($sm) {
                    $tableGateway = $sm->get('EntrySectionTableGateway');
                    $table = new EntrySectionTable($tableGateway);
                    return $table;
                },
                'UserRoleTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('role', $dbAdapter, null, $resultSetPrototype);
                },
                'UserRoleTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserRoleTableGateway');
                    $table = new UserRoleTable($tableGateway);
                    return $table;
                },
                'Admin\Form\UserEditFilter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    
                    return new UserEditFilter($dbAdapter);
                },
                'UserEditForm' => function ($sm) {
                    $form = new UserEditForm();
                    return $form;
                },
                'Admin\Form\AddMedicalFilter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    
                    return new AddMedicalFilter($dbAdapter);
                },
                'Admin\Form\AddDefaultFilter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    
                    return new AddDefaultFilter($dbAdapter);
                },
            ),
            
        );
    }
    

}
