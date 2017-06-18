<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 */
class Goods extends \yii\db\ActiveRecord
{
    //是否在售
    static public $saleOptions = [1=>'在售',0=>'下架'];
    //状态
    static public $statusOptions = [1=>'正常',0=>'回收站'];
    //最低价和最高价
    public $min_price;
    public $max_price;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'goods_category_id', 'brand_id', 'shop_price', 'stock'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['sn'], 'unique'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => '商品LOGO',
            'goods_category_id' => '所属商品分类',
            'brand_id' => '品牌',
            'market_price' => '市场价格',
            'shop_price' => '本店价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
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
    //获取分类名
    public function getGoodsCate(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    //获取品牌名
    public function getGoodsBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
    //定义场景常量
    const SCENARIO_ADD = 'add';
    const SCENARIO_SEARCH = 'search';
    //定义场景字段
    public function scenarios()
    {
        $scenarios =  parent::scenarios();
        $scenarios[self::SCENARIO_ADD] = ['name', 'goods_category_id', 'brand_id', 'shop_price', 'stock','market_price', 'is_on_sale','sn','logo'];
        $scenarios[self::SCENARIO_SEARCH] = ['sn'];
        return $scenarios;
    }
}
