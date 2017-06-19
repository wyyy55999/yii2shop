<?php

namespace backend\components;


use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        $user = \Yii::$app->user;
        if($user->isGuest){
            return $action->controller->redirect(\Yii::$app->user->loginUrl);
        }
        //$action->uniqueId  当前的路由
        if(!$user->can($action->uniqueId)){
            throw new HttpException(403,'对不起，您没有该权限');
        }
        return parent::beforeAction($action);
    }
}