<?php

namespace Dashboard;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'controllers' => [
        'factories' => [
            Controller\DashboardController::class => Controller\Factory\DashboardControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'dashboard' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/dashboard',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action' => 'overview',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'contacts' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/contacts',
                            'defaults' => [
                                'action' => 'contacts',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'detail' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/detail[/:contactId]',
                                    'defaults' => [
                                        'action' => 'contacts-detail',
                                    ],
                                    'constrains' => [
                                        'contactId' => '\d+',
                                    ],
                                ],
                            ],
                            'edit' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/edit[/:contactId]',
                                    'defaults' => [
                                        'action' => 'contacts-edit',
                                    ],
                                    'constrains' => [
                                        'contactId' => '\d+',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'dashboard' => __DIR__ . '/../view',
        ],
    ],
];