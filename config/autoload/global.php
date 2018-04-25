<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=db_dcz;host=localhost',
        'username' => 'dbUser',
        'password' => 'password46418504',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
     'navigation' => array(
         'default' => array(
             array(
                 'label' => 'Strona główna',
                 'route' => 'home',
                 'pages' => array(
                     array(
                        'label' => 'Brak obsługi JavaScript',
                        'route' => 'noscript',
                        'pages' => array(
                            array(
                                'label' => '',
                                'controller' => 'Index',
                                'action' => 'noscript',
                            ),
                        )
                    ),
                     array(
                        'label' => 'Użytkownik',
                        'route' => 'users',
                        'controller' => 'Index',
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'Logowanie',
                                'route' => 'users/login',
                            ),
                            array(
                                'label' => 'Rejestracja',
                                'route' => 'users/register',
                            ),
                        )
                    ),
                     array(
                        'label' => 'Chat',
                        'route' => 'chat/adviser',
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'Rozmowa',
                                'route' => 'chat/adviser',
                                'action' => 'receive',
                            ),
                        )
                    ),
                     array(
                        'label' => 'Centrum',
                        'route' => 'center',
                        'controller' => 'Index',
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'route' => 'center/index',
                                'label' => 'Informacje osobowe',
                                'controller' => 'Index',
                                'action' => 'index',
                            ),
                            array(
                                'label' => 'Obserwowani',
                                'controller' => 'Member',
                                'action' => 'index',
                            ),
                            array(
                                'label' => 'Dodawanie osoby',
                                'controller' => 'Member',
                                'action' => 'create',
                            ),
                            array(
                                'label' => 'Dodawanie osoby',
                                'controller' => 'Member',
                                'action' => 'processCreateMember',
                            ),
                            array(
                                'label' => 'Edycja profilu',
                                'controller' => 'Member',
                                'action' => 'profile-edit',
                            ),
                            array(
                                'label' => 'Edycja profilu',
                                'controller' => 'Member',
                                'action' => 'processProfileEdit',
                            ),
                            array(
                                'label' => 'Dokumenty',
                                'controller' => 'Document',
                                'action' => 'index',
                            ),
                            array(
                                'label' => 'Dodawanie dokumentu',
                                'controller' => 'Document',
                                'action' => 'add-document',
                            ),
                            array(
                                'label' => 'Dokument',
                                'controller' => 'Document',
                                'action' => 'add-files',
                            ),
                            array(
                                'label' => 'Edycja dokumentu',
                                'controller' => 'Document',
                                'action' => 'edit-document',
                            ),
                            array(
                                'label' => 'Edycja dokumentu',
                                'controller' => 'Document',
                                'action' => 'processEditDocument',
                            ),
                        )
                    ),
                     array(
                        'label' => 'Blog',
                        'route' => 'blog',
                        'pages' => array(
                            array(
                                'label' => '',
                                'controller' => 'Index',
                                'action' => 'show',
                            ),
                        )
                    ),
                     array(
                        'label' => 'Chrolonogia',
                        'route' => 'chronology',
                        'pages' => array(
                            array(
                                'label' => '',
                                'controller' => 'Index',
                                'action' => 'chronology',
                            ),
                        )
                    ),
                    array(
                        'label' => 'Zarządzanie',
                        'route' => 'admin',
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'Użytkownicy',
                                'route' => 'admin/user-manager',
                                'action' => 'index',
                                'pages' => array(
                                   array(
                                       'label' => 'Edycja użytkownika',
                                       'route' => 'admin/user-manager',
                                       'action' => 'edit',
                                   ),
                                   array(
                                       'label' => 'Edycja użytkownika',
                                       'route' => 'admin/user-manager',
                                       'action' => 'process',
                                   ),
                                )
                            ),
                            array(
                                'label' => 'Taksonomie',
                                'route' => 'admin/taxonomies',
                                'action' => 'index',
                                'pages' => array(
                                   array(
                                       'label' => 'Dodawanie pozycji słownika',
                                       'route' => 'admin/taxonomies',
                                       'action' => 'add-medical',
                                   ),
                                   array(
                                       'label' => 'Dodawanie pozycji słownika',
                                       'route' => 'admin/taxonomies',
                                       'action' => 'add-default',
                                   ),
                                   array(
                                       'label' => 'Edycja pozycji słownika',
                                       'route' => 'admin/taxonomies',
                                       'action' => 'edit-medical',
                                   ),
                                   array(
                                       'label' => 'Edycja pozycji słownika',
                                       'route' => 'admin/taxonomies',
                                       'action' => 'processEditMedical',
                                   ),
                                   array(
                                       'label' => 'Edycja pozycji słownika',
                                       'route' => 'admin/taxonomies',
                                       'action' => 'edit-default',
                                   ),
                                   array(
                                       'label' => 'Edycja pozycji słownika',
                                       'route' => 'admin/taxonomies',
                                       'action' => 'processEditDefault',
                                   ),
                                )
                            ),
                            array(
                                'label' => 'Blog',
                                'route' => 'admin/blog',
                                'action' => 'index',
                                'pages' => array(
                                   array(
                                       'label' => 'Dodawanie wpisu',
                                       'route' => 'admin/blog',
                                       'action' => 'add-entry',
                                   ),
                                   array(
                                       'label' => 'Dodawanie wpisu',
                                       'route' => 'admin/blog',
                                       'action' => 'processAddEntry',
                                   ),
                                 array(
                                       'label' => 'Edycja wpisu',
                                       'route' => 'admin/blog',
                                       'action' => 'edit-entry',
                                   ),
                                   array(
                                       'label' => 'Edycja wpisu',
                                       'route' => 'admin/blog',
                                       'action' => 'processEditEntry',
                                   ),
                                   array(
                                       'label' => 'Usuwanie wpisu',
                                       'route' => 'admin/blog',
                                       'action' => 'delete-entry',
                                   ),
                                )
                            ),
                        )
                    ),
                 )
             ),
         ),
        'normal' => array( 
            array(
               'label' => 'Blog',
               'route' => 'blog',
               'icon' => 'glyphicon glyphicon-book'
            ),
        ),
        'member' => array( 
            array(
               'label' => 'Centrum',
               'route' => 'center',
               'icon' => 'glyphicon glyphicon-flag'
            ),
            /*array(
               'label' => 'Blog',
               'route' => 'blog',
               'icon' => 'glyphicon glyphicon-pencil'
            ),
            array(
               'label' => 'Chronologia',
               'route' => 'chronology',
               'icon' => 'glyphicon glyphicon-time'
            ),*/
        ),
        'offline' => array(
            array(
               'label' => 'Logowanie',
               'route' => 'users/login',
               'icon' => 'glyphicon glyphicon-log-in',
            ),
            array(
               'label' => 'Rejestracja',
               'route' => 'users/register',
               'icon' => 'glyphicon glyphicon-pencil',
            ),
        ),
        'online' => array(
            array(
               'label' => 'Menu konta',
               'uri' => '#',
               'pages' => array(
                    array(
                       'label' => 'Panel użytkownika',
                       'uri' => '#',
                       'header' => true,
                    ),
                    array(
                       'label' => 'Wyloguj się',
                       'route' => 'users/logout',
                        'icon' => 'glyphicon glyphicon-log-out',
                    ),
                    array(
                       'label' => '#',
                       'separator' => true,
                       'uri' => '#',
                       
                    ),
                    array(
                       'label' => 'Zarządzanie',
                       'uri' => '#',
                       'header' => true,
                       'resource' => 'admin/blog',
                    ),
                    array(
                       'label' => 'Blog',
                        'route' => 'admin/blog',
                        'resource' => 'admin/blog',
                    ),
                    array(
                       'label' => 'Użytkownicy',
                       'route' => 'admin/user-manager',
                       'resource' => 'admin/user-manager',
                    ),
                    array(
                       'label' => 'Taksonomie',
                       'route' => 'admin/taxonomies',
                       'resource' => 'admin/taxonomies',
                    ),
                    array(
                       'label' => '#',
                       'separator' => true,
                       'uri' => '#',
                       'resource' => 'chat/adviser',
                       
                    ),
                    array(
                       'label' => 'Poradnia',
                       'uri' => '#',
                       'header' => true,
                       'resource' => 'chat/adviser',
                       
                    ),
                    array(
                       'label' => 'Chat',
                       'route' => 'chat/adviser',
                       'resource' => 'chat/adviser',
                    ),
               )
           ),

        ),
     ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Navigation\Service\NavigationAbstractServiceFactory'
        ),
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);
