<?php

namespace backend\controllers;

use backend\models\Order;

class OrderController extends \yii\web\Controller
{
    //订单列表
    public function actionIndex()
    {
        $orders = Order::find()->all();
        return $this->render('index',['orders'=>$orders]);
    }
    //修改订单状态
    public function actionStatusUpdate($order_id){
        $order = Order::findOne(['id'=>$order_id]);
        $order->status = 3;
        $order->save(false);
        \Yii::$app->session->setFlash('success','发货成功');
        return $this->redirect(['order/index']);
    }
}
