<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    //logo的图片文件
    public $logoFile;
    //静态属性  保存状态的选项
    static public $statusOptions = [1=>'显示',0=>'隐藏',-1=>'删除'];

    //定义场景常量
    const SCENARIO_ADD = 'add';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
            ['logoFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false,'on'=>self::SCENARIO_ADD],              ['logoFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>true,'on'=>self::SCENARIO_UPDATE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '品牌名称',
            'intro' => '品牌简介',
            'logoFile' => '品牌LOGO',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
}
