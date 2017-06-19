<?php
namespace backend\models;


use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model
{
    //定义权限字段
    public $name;  //权限名
    public $description; //权限描述

    //规则
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['name','match','pattern'=>'/[\w\-]+\/[\w\-]+/','message'=>'权限格式错误，请重新输入'],
        ];
    }
    //标签名
    public function attributeLabels()
    {
        return [
            'name'=>'权限名',
            'description'=>'权限描述',
        ];
    }
    //添加权限
    public function addPermission(){
        //如果该权限不存在才添加   存在就报错
        //实例化组件
        $authManager = \Yii::$app->authManager;
        //意思是如果获取到了与当前输入的权限名一样的权限，返回该权限名
        if($authManager->getPermission($this->name)){  //说明权限存在
            $this->addError('name','该权限已存在');
        }else{  //权限不存在
            //创建权限  createPermission返回一个对象
            $permission = $authManager->createPermission($this->name);
            $permission->description = $this->description;  //赋值
            //加入到数据库  add  需要传一个对象
            return $authManager->add($permission);
        }
        return false;
    }
    //加载权限  回显   限定只能是Permission类的对象
    public function loadData(Permission $permission){
        $this->name = $permission->name;  //将获取到的权限赋值给当前的权限名和描述，以此回显
        $this->description = $permission->description;
    }
    //修改权限操作
    public function updatePermission($name){
        //获取到修改前的权限  根据name
        $permission = \Yii::$app->authManager->getPermission($name);
        //因为name是主键  不能重复  也就是权限名不能重复  所以先判断  重复则返回false
        //$name == $this->name  说明没有修改权限名  $name != $this->name表示修改了权限名  然后判断权限名是否存在
        if($name != $this->name && \Yii::$app->authManager->getPermission($this->name)){
            $this->addError('name','该权限名已存在');
            return false;
        }
        //修改权限  update
        $permission->name = $this->name;
        $permission->description = $this->description;
        return \Yii::$app->authManager->update($name,$permission);
    }
}