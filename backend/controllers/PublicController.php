<?php

namespace backend\controllers;


use backend\components\RbacFilter;
use yii\web\Controller;

class PublicController extends Controller
{
    //使用过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}