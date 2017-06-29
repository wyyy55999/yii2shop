<?php

namespace console\controllers;

use backend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\console\Controller;

class TaskController extends Controller
{
    //删除未付款的订单
    public function actionClean(){
        //set_time_limit(30);  //设置程序最大执行时间
        while (1){
            //找到所有状态为1并且超过一小时未付款的订单  未付款==>已取消
            $orders = Order::find()->where('status=1')->andWhere(['<','create_time',time()-3600])->all();
            //将状态改为0
            foreach ($orders as $order){
                $order->status = 0;
                $order->save(false);
                //商品表中的商品库存字段要修改成之前的库存
                //获取到之前的购物车订单对象
                $old_stocks = OrderGoods::findAll(['order_id'=>$order->id]);
                foreach ($old_stocks as $old_stock){
                    //将商品库存加上之前的购物车数量
                    $goods = Goods::findOne(['id'=>$old_stock->goods_id]);
                    $goods->stock += $old_stock->amount;
                    $goods->save(false);
                }
            }
            echo 6666666;
            sleep(10);
        }
    }
}