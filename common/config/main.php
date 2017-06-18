<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        //权限管理
        'authManager'=>[
            'class'=>\yii\rbac\DbManager::className(),
        ],
    ],
];
