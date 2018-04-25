<?php
namespace Chat;

use Chat\Model\RequestTable;
use Chat\Model\MessageTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
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
                'RequestTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('chat_requests', $dbAdapter, null, $resultSetPrototype);
                },
                'RequestTable' =>  function($sm) {
                    $tableGateway = $sm->get('RequestTableGateway');
                    $table = new RequestTable($tableGateway);
                    return $table;
                },
                'MessageTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('chat_messages', $dbAdapter, null, $resultSetPrototype);
                },
                'MessageTable' =>  function($sm) {
                    $tableGateway = $sm->get('MessageTableGateway');
                    $table = new MessageTable($tableGateway);
                    return $table;
                },
            ),
            
        );
    }
}
