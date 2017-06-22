<?php

namespace frontend\controllers;


use backend\models\GoodsCategory;
use yii\web\Controller;

class IndexController extends Controller
{
    public $layout = 'index';
    //显示
    public function actionIndex(){
        $goods_cates = GoodsCategory::find()->where('parent_id = 0')->all();
        return $this->render('index',['goods_cates'=>$goods_cates]);
    }
}