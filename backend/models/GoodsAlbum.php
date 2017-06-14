<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_album".
 *
 * @property integer $id
 * @property string $album_path
 * @property integer $goods_id
 */
class GoodsAlbum extends \yii\db\ActiveRecord
{
    //定义图片路径  ==>和数据库中的不一致
    public $album;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_album';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['album_path', 'goods_id'], 'required'],
//            [['goods_id'], 'integer'],
            [['album_path'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'album_path' => '图片路径',
            'goods_id' => '所属商品',
        ];
    }
}
