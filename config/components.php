<?php

use app\components\console\ErrorHandler;

return [
    'log' => [
        'flushInterval' => 1,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
                'exportInterval' => 1
            ],
        ],
    ],
    'errorHandler' => [
        'class' => ErrorHandler::class
    ],
    'db' => [
        'class' => '\yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=bbs',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8mb4',
    ]
];