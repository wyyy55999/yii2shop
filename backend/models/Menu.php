<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label','parent_id'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label'], 'string', 'max' => 40],
            [['url'], 'string', 'max' => 255],
            ['url','match','pattern'=>'/[\w\-]+\/[\w\-]+/','message'=>'菜单地址格式错误，请重新输入'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '名称',
            'url' => '地址',
            'parent_id' => '上级菜单',
            'sort' => '排序',
        ];
    }

    //获取父级标签
    public function getParentLabel(){
        return $this->hasOne(self::className(),['id'=>'parent_id']);
    }

    //不能修改到自己和自己的子孙分类下
    public function checkParent($id){
        if($id == $this->parent_id){
            $this->addError('parent_id','不能修改到自己分类下');
            return false;
        }
        return true;
    }

    //不能删除有子菜单的菜单
    public function checkSon($id){
        $sons = Menu::findAll(['parent_id'=>$id]);
        if(!empty($sons)){
            return false;
        }
        return true;
    }

    //获取当前菜单的子菜单
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
