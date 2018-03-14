<?php

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => 'mysql:host=127.0.0.1;dbname=phpbootcamp_crm;port=3306;charset=utf8',
        'username' => 'phpbootcamp_crm',
        'password' => 'ZF4Fun&Profit',
        'driver_options' => [
            'factories' => [
                \Zend\Db\Adapter\Adapter::class => \Zend\Db\Adapter\AdapterServiceFactory::class,
            ],
        ],
    ],
];