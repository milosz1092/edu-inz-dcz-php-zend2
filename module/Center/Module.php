<?php
namespace Center;

use Center\Model\MemberTable;
use Center\Model\IllnessTable;
use Center\Model\MemberIllnessTable;
use Center\Model\DocumentTable;
use Center\Model\Illness;
use Center\Form\MemberCreateFilter;
use Center\Form\MemberEditFilter;
use Center\Form\DocumentAddFilter;
use Center\Form\FilesAddFilter;

use Admin\Model\DefaultTax;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getFormElementConfig()
    {
        // Creating own form elements
        return array(
            'factories' => array(
                'MemberIllnessCheckboxes' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
                    
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Illness());
                    
                    $tabGate = new TableGateway('illness', $dbAdapter, null, $resultSetPrototype);
                    $illnessTable = new \Center\Model\IllnessTable($tabGate);
                    $MemberIllnessCheckboxes = new \Center\Form\Element\MemberIllnessCheckboxes($illnessTable);
                    
                    return $MemberIllnessCheckboxes;
                },
                'DocumentSpecializationSelect' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
                    
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new DefaultTax());

                    $taxTable = new \Admin\Model\TaxonomiesTable('specialization', $dbAdapter);
                    $DocumentSpecializationSelect = new \Center\Form\Element\DocumentSpecializationSelect($taxTable);
                    
                    return $DocumentSpecializationSelect;
                },
                'DocumentTypeSelect' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $dbAdapter = $serviceLocator->get('Zend\Db\Adapter\Adapter');
                    
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new DefaultTax());

                    $taxTable = new \Admin\Model\TaxonomiesTable('document_type', $dbAdapter);
                    $DocumentTypeSelect = new \Center\Form\Element\DocumentTypeSelect($taxTable);
                    
                    return $DocumentTypeSelect;
                },
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
                'Center\Form\MemberCreateFilter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    
                    return new MemberCreateFilter($dbAdapter);
                },
                'Center\Form\MemberEditFilter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    
                    return new MemberEditFilter($dbAdapter);
                },
                'Center\Form\DocumentAddFilter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    
                    return new DocumentAddFilter($dbAdapter);
                },
                'Center\Form\FilesAddFilter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    
                    return new FilesAddFilter($dbAdapter);
                },
                'MemberTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('member', $dbAdapter, null, $resultSetPrototype);
                },
                'MemberTable' =>  function($sm) {
                    $tableGateway = $sm->get('MemberTableGateway');
                    $table = new MemberTable($tableGateway);
                    return $table;
                },
                'IllnessTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('illness', $dbAdapter, null, $resultSetPrototype);
                },
                'IllnessTable' =>  function($sm) {
                    $tableGateway = $sm->get('IllnessTableGateway');
                    $table = new IllnessTable($tableGateway);
                    return $table;
                },
                'MemberIllnessTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('member_illness', $dbAdapter, null, $resultSetPrototype);
                },
                'MemberIllnessTable' =>  function($sm) {
                    $tableGateway = $sm->get('MemberIllnessTableGateway');
                    $table = new MemberIllnessTable($tableGateway);
                    return $table;
                },
                'DocumentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('document', $dbAdapter, null, $resultSetPrototype);
                },
                'DocumentTable' =>  function($sm) {
                    $tableGateway = $sm->get('DocumentTableGateway');
                    $table = new DocumentTable($tableGateway);
                    return $table;
                },
            ),
            
        );
    }
}
