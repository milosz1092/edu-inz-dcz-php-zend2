<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Center\Controller\Index' => 'Center\Controller\IndexController',
            'Center\Controller\Member' => 'Center\Controller\MemberController',
            'Center\Controller\Document' => 'Center\Controller\DocumentController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'center' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/center',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Center\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.

                    'index' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/index[/:id[/:context]]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Center\Controller',
                                'context' => 'show'
                            ),
                        ),
                    ),
                    'member' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/member[/:action[/:id[/:context]]]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Center\Controller',
                                'controller' => 'Member',
                                'action' => 'index',
                                'context' => 'profile'
                            ),
                        ),
                    ),
                    'document' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/document[/:action[/:id[/:context]]]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Center\Controller',
                                'controller' => 'Document',
                                'action' => 'index',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Center' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
