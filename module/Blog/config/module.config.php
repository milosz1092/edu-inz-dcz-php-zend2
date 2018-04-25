<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Index' => 'Blog\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'blog' => array(
                'type' => 'Zend\Mvc\Router\Http\Regex',
                'options' => array(
                    'regex' => '(/blog/page/(?<page>[a-zA-Z0-9_-]+))|(/blog/(?<action>[a-zA-Z0-9_-]+)/(?<id>[a-zA-Z0-9_-]+))|(/blog)',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                    'spec' => '/blog',
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:action[/:id]]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Blog\Controller',
                                'controller' => 'Index',
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
            'Blog' => __DIR__ . '/../view',
        ),
    ),
);
