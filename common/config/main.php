<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'authManager' => [
            'class' => \bulldozer\users\rbac\DbManager::class,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
