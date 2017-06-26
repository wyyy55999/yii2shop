<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property double $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    //地址id
    public $address_id;
    //配送方式
    public static $deliveries = [
        ['delivery_id'=>'1','delivery_name'=>'普通快递送货上门','delivery_price'=>'10.00','delivery_intro'=>'每张订单不满499.00元,运费15.00元, 订单4...',],
        ['delivery_id'=>'2','delivery_name'=>'特快专递','delivery_price'=>'40.00','delivery_intro'=>'每张订单不满499.00元,运费40.00元, 订单4...',],
        ['delivery_id'=>'3','delivery_name'=>'加急快递送货上门','delivery_price'=>'40.00','delivery_intro'=>'每张订单不满499.00元,运费40.00元, 订单4...',],
        ['delivery_id'=>'4','delivery_name'=>'平邮','delivery_price'=>'10.00','delivery_intro'=>'每张订单不满499.00元,运费15.00元, 订单4...',],
    ];
    //支付方式
    public static $payments = [
        ['payment_id'=>'1','payment_name'=>'货到付款','payment_intro'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        ['payment_id'=>'2','payment_name'=>'在线支付','payment_intro'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        ['payment_id'=>'3','payment_name'=>'上门自提','payment_intro'=>'自提时付款，支持现金、POS刷卡、支票支付'],
        ['payment_id'=>'4','payment_name'=>'邮局汇款','payment_intro'=>'通过快钱平台收款 汇款后1-3个工作日到账'],
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '区县',
            'address' => '详细地址',
            'tel' => '电话号码',
            'delivery_id' => '配送方式id',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
            'payment_id' => '支付方式id',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '订单状态（0已取消1待付款2待发货3待收货4完成）',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }
    //创建时间
    public function beforeSave($insert)
    {
        if($insert){
            $this->create_time = time();
        }
        return parent::beforeSave($insert);
    }
}
