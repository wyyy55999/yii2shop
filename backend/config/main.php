<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language'=>'zh-CN',
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'class'=>\yii\web\User::className(),
            //'identityClass' => 'common\models\User',
            'identityClass' => \backend\models\User::className(),
            'enableAutoLogin' => true,  //自动登录
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl'=>['user/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        //地址管理
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        //七牛云配置
        'qiniu'=>[
            'class'=>\backend\components\Qiniu::className(),
            'up_host'=>'http://up-z2.qiniu.com',
            'accessKey'=>'1d0e88-5TukIYJX9Fvv25WEUgpfnBuSbBdFSuSxD',
            'secretKey'=>'aJCuSrHWxNM7mANw1FEQpH3XwbX1a9D0d6ShPXia',
            'bucket'=>'dlm1996',
            'domain'=>'http://or9rs3wp9.bkt.clouddn.com/'
        ],
    ],
    'params' => $params,
];
