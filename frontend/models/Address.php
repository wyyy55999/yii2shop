<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $consignee
 * @property string $address
 * @property string $tel
 * @property integer $is_default
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['consignee','province','city','area','detail_address', 'tel'], 'required'],
            [ 'is_default', 'integer'],
            [['detail_address'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
            ['tel','match','pattern'=>'/^1[34578]\d{9}$/','message'=>'请输入有效的手机号码'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'consignee' => '收货人',
            'province'=>'省份',
            'city'=>'城市',
            'area'=>'区县',
            'detail_address' => '详细地址',
            'tel' => '手机号码',
            'is_default' => '是否为默认地址',
        ];
    }

    //保存添加
    public function AddSave(){
        $addresses = Address::find()->all();
        if($addresses){
            if($this->is_default == 1) {
                foreach ($addresses as $address){
                    $address->is_default = 0;
                    $address->save(false);
                }
            }
        }
        return true;
    }

    //三级联动
    public static function getRegion($parentId=0)
    {
        //从locations表中查询
        $result = static::find()->from('locations')->where(['parent_id'=>$parentId])->asArray()->all();
        return ArrayHelper::map($result, 'id', 'name');
    }

}
