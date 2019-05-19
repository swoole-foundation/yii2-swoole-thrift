<?php
/**
 * @author xialeistudio
 * @date 2019-05-19
 */
return [
    'id' => 'yii2-swoole-thrift-app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@app' => dirname(__DIR__)
    ],
    'components' => require __DIR__ . '/components.php',
    'params' => require __DIR__ . '/params.php'
];