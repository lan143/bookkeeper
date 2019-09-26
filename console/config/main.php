<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'bulldozer-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
        ],
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'bulldozer\users\console\migrations',
                'bulldozer\files\migrations',
            ],
        ],
    ],
    'components' => [
        'taxApi' => [
            'class' => \common\components\TaxApi::class,
            'baseUrl' => 'https://proverkacheka.nalog.ru:9999/',
        ],
        'qrKassaApi' => [
            'class' => \common\components\QrKassaApi::class,
            'baseUrl' => 'https://qr-kassa.ru/',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'redis' => [
            'class' => \yii\redis\Connection::class,
            'retries' => 1,
        ],
        'queue' => [
            'class' => \yii\queue\redis\Queue::class,
            'as log' => \yii\queue\LogBehavior::class,
            'redis' => 'redis',
            'channel' => 'bookkeeper_queue',
        ],
    ],
    'params' => $params,
];
