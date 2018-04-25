<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Chat\Controller\Index' => 'Chat\Controller\IndexController',
            'Chat\Controller\Adviser' => 'Chat\Controller\AdviserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'chat' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/chat',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Chat\Controller',
                        'controller'    => 'Adviser',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'index/async' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/index/async',
                            'defaults' => array(
                                'controller' => 'Chat\Controller\Index',
                                'action' => 'async'
                            ),
                        ),
                    ),
                    'adviser/async' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/adviser/async',
                            'defaults' => array(
                                'controller' => 'Chat\Controller\Adviser',
                                'action' => 'async'
                            ),
                        ),
                    ),
                    'adviser' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/adviser[/:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Chat\Controller\Adviser',
                                'action' => 'index'
                            ),
                        ),
                    ),
                    
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Chat' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
