<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Locations;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

class OrderController extends \yii\web\Controller
{
    public $layout = 'order';
    //订单填写后提交
    public function actionFillin()
    {
        $order_model = new Order();
        //获取到当前登录的用户id
        if(\Yii::$app->user->isGuest){
            throw new NotFoundHttpException('该用户不存在');
        }else{
            $cur_member_id = \Yii::$app->user->id;
        }
        if(\Yii::$app->request->isPost){
            if($order_model->load(\Yii::$app->request->post())){
                $request = \Yii::$app->request;
                $order_model->member_id = $cur_member_id;
                $address_info = Address::findOne(['id'=>$request->post('address_id'),'member_id'=>$cur_member_id]);
                $order_model->name = $address_info->consignee;  //收货人
                $order_model->province = $address_info->pro->name;  //省
                $order_model->city = $address_info->cit->name;  //市
                $order_model->area = $address_info->are->name;  //区县
                $order_model->address = $address_info->detail_address;  //详细地址
                $order_model->tel = $address_info->tel;  //联系电话
                $order_model->delivery_id = $request->post('Order')['delivery_id'];  //配送方式id
                //获取配送方式
                $deliveries_post = Order::$deliveries;
                foreach ($deliveries_post as $deli){
                    if($deli['delivery_id'] == $request->post('Order')['delivery_id']){
                        $order_model->delivery_name = $deli['delivery_name'];
                        $order_model->delivery_price = $deli['delivery_price'];
                    }
                }
                $order_model->payment_id = $request->post('Order')['payment_id'];//支付方式id
                //获取支付方式
                $payments_post = Order::$payments;
                foreach ($payments_post as $pay){
                    if($pay['payment_id'] ==  $request->post('Order')['payment_id']){
                        $order_model->payment_name = $pay['payment_name'];
                    }
                }
                //总金额
                $order_model->total = $request->post('total');
                //状态
                $order_model->status = 2;
                //事务回滚
                $db = \Yii::$app->db;
                $transaction = $db->beginTransaction();  //开启事务
                try{
                    $order_model->save(false);
                    //获取订单id
                    $order_id = $order_model->id;
                    //循环传过来的商品
                    foreach ($request->post('goods') as $goods_id){
                        //添加商品到订单-商品表
                        $order_goods_model = new OrderGoods();
                        $goods = Goods::findOne(['id'=>$goods_id]);
                        //获取商品库存
                        $goods_stock = Goods::findOne(['id'=>$goods->id]);
                        if($goods_stock->stock < Cart::findOne(['goods_id'=>$goods->id])->amount){
                            throw new NotFoundHttpException($goods_stock->name.'库存不足');
                        }else{
                            //库存够的时候就用商品库存 - 购物车中该商品的数量
                            $goods_stock->stock = $goods_stock->stock - Cart::findOne(['goods_id'=>$goods->id])->amount;
                            $goods_stock->save(false);
                        }
                        $order_goods_model->order_id = $order_id;  //订单id
                        $order_goods_model->goods_id = $goods->id;  //商品id
                        $order_goods_model->goods_name = $goods->name; //商品名
                        $order_goods_model->logo = $goods->logo;  //商品logo
                        $order_goods_model->price = $goods->shop_price;  //商品价格
                        $order_goods_model->amount = Cart::findOne(['goods_id'=>$goods->id])->amount;  //商品数量
                        $order_goods_model->total = ($goods->shop_price)*(Cart::findOne(['goods_id'=>$goods->id])->amount);
                        $order_goods_model->save(false);
                    }
                    //删除购物车商品
                    $mem_goods = Cart::findAll(['member_id'=>$cur_member_id]);
                    //因为是数组  所以要循环删除
                    foreach ($mem_goods as $goods){
                        $goods->delete();
                    }
                    //提交事务
                    $transaction->commit();
                    return $this->redirect(['order/success']);
                }catch (Exception $e){  //回滚
                    //回滚事务
                    $transaction->rollBack();
                }
            }
        }
        //根据用户id获取到地址信息
        $member_address = Address::find()->where('member_id='.$cur_member_id)->all();
        //运费标准
        $deliveries = Order::$deliveries;
        //支付方式
        $payments = Order::$payments;
        //获取当前用户的购物车商品
        $member_goodses = Cart::findAll(['member_id'=>$cur_member_id]);
        return $this->render('fillin',['member_address'=>$member_address,'order_model'=>$order_model,'deliveries'=>$deliveries,'payments'=>$payments,'member_goodses'=>$member_goodses]);
    }
    //订单提交成功
    public function actionSuccess(){
        return $this->render('success');
    }
}
