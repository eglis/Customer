<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletoncms for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'asset_manager' => array(
        'resolver_configs' => array(
            'collections' => array(
                'js/application.js' => array(
                    'js/customer.js',
                ),
                'css/application.css' => array(
                    'css/customer.css',
                ),
                'js/administration.js' => array(
                    'js/customer.js',
                ),
                'css/administration.css' => array(
                    'css/customer.css',
                ),
            ),
            'paths' => array(
                __DIR__ . '/../public',
                __DIR__ . '/../../../../public'
            ),
        ),
    ),
    'bjyauthorize' => array(
        'guards' => array(
            'BjyAuthorize\Guard\Route' => array(

                array('route' => 'zfcadmin/customer', 'roles' => array('admin')),
                array('route' => 'zfcadmin/customer/default', 'roles' => array('admin')),
                array('route' => 'zfcadmin/customer/settings', 'roles' => array('admin')),
                array('route' => 'zfcadmin/customer/delfile', 'roles' => array('admin')),
                array('route' => 'zfcadmin/customer/contacttype', 'roles' => array('admin')),
                array('route' => 'zfcadmin/customer/group', 'roles' => array('admin')),
                array('route' => 'zfcadmin/customer/massactions', 'roles' => array('admin')),

                // Generic route guards
                array('route' => 'customer', 'roles' => array('guest')),
                array('route' => 'customer/default', 'roles' => array('guest')),
            ),
        ),
    ),
    'navigation' => array(
        'admin' => array(
            'settings' => array(
                'label' => _('Settings'),
                'route' => 'zfcadmin',
                'privileges' => 'list',
                'resource' => 'adminmenu',
                'pages' => array(
                    array(
                        'label' => _('Customers'),
                        'route' => 'zfcadmin/customer/settings',
                        'icon' => 'fa fa-group'
                    ),
                    array(
                        'label' => _('Contact Type'),
                        'route' => 'zfcadmin/customer/contacttype',
                        'icon' => 'fa fa-phone',
                    ),
                ),
            ),
            'customer' => array(
                'label' => 'Customer',
                'route' => 'home',
                'icon' => 'fa fa-user',
                'pages' => array(
                    array(
                        'label' => _('Customers'),
                        'route' => 'zfcadmin/customer/default',
                        'icon' => 'fa fa-group',
                    ),

                    array(
                        'label' => _('Groups'),
                        'route' => 'zfcadmin/customer/group',
                        'icon' => 'fa fa-users',
                    ),
                ),
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'customer' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/customer',
                    'defaults' => array(
                        'controller' => 'Customer\Controller\Index',
                        'action' => 'index',
                    )
                )
            ),
            'zfcadmin' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'ZfcAdmin\Controller\AdminController',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'customer' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/customer',
                            'defaults' => array(
                                'controller' => 'CustomerAdmin\Controller\Index',
                                'action' => 'index',
                            ),
                        ),
                        'child_routes' => array(
                            'default' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/[:action[/:id]]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '[0-9]*'
                                    ),
                                    'defaults' => array()
                                )
                            ),
                            'delfile' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/delfile/[:uid[/:file]]',
                                    'constraints' => array(
                                        'file' => '[\w,\s-%@]+\.[A-Za-z]{3}',
                                        'uid' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'
                                    ),
                                    'defaults' => array(
                                        'controller' => 'CustomerAdmin\Controller\Index',
                                        'action' => 'delfile',
                                    )
                                )
                            ),
                            'massactions' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/massaction/[:task]',
                                    'constraints' => array(
                                        'task' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'CustomerAdmin\Controller\Index',
                                        'action' => 'mass',
                                    )
                                )
                            ),
                            'group' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/group/[:action[/:id]]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '[0-9]*'
                                    ),
                                    'defaults' => array(
                                        'controller' => 'CustomerAdmin\Controller\CustomerGroup',
                                        'action' => 'index',
                                    )
                                )
                            ),
                            'contacttype' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/contacttype/[:action[/:id]]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '[0-9]*'
                                    ),
                                    'defaults' => array(
                                        'controller' => 'CustomerAdmin\Controller\ContactType',
                                        'action' => 'index',
                                    )
                                )
                            ),
                            'settings' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/settings/[:action[/:id]]',
                                    'constraints' => array(
                                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id' => '[0-9]*'
                                    ),
                                    'defaults' => array(
                                        'controller' => 'CustomerSettings\Controller\Index',
                                        'action' => 'index',
                                    )
                                )
                            )
                        ),

                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(),
        'factories' => array(
            'CustomerAdmin\Controller\Index' => 'CustomerAdmin\Factory\IndexControllerFactory',
            'CustomerAdmin\Controller\ContactType' => 'CustomerAdmin\Factory\ContactTypeControllerFactory',
            'CustomerAdmin\Controller\CustomerGroup' => 'CustomerAdmin\Factory\CustomerGroupControllerFactory',
            'CustomerSettings\Controller\Index' => 'CustomerSettings\Factory\IndexControllerFactory',
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../locale',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
