<?php

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model
{
    //定义角色字段
    public $name;
    public $description;
    //权限
    public $permission = [];

    //规则
    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permission','safe'],
        ];
    }
    //标签
    public function attributeLabels()
    {
        return [
            'name'=>'角色名',
            'description'=>'角色描述',
            'permission'=>'角色的权限'
        ];
    }
    //添加角色
    public function addRole(){
        //实例化组件
        $authManager = \Yii::$app->authManager;
        //判断角色名是否存在
        if($authManager->getRole($this->name)){
            $this->addError('name','该角色名已存在');
        }else{
            //创建角色
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;
            //将角色保存到数据库
            if($authManager->add($role)){
                foreach ($this->permission as $permissionName){
                    //根据权限名获取到权限
                    $permission = $authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;
    }
    //获取权限  然后在add视图中直接调用这个方法即可
    public static function getPermissions(){
        return ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','description');
    }
    //回显数据
    public function loadRole(Role $role){
        $this->name = $role->name;
        $this->description = $role->description;
        //获取当前角色的权限
        $permissions = \Yii::$app->authManager->getPermissionsByRole($role->name);
        //遍历数组并赋值
        foreach ($permissions as $permissionOfRole){
            //$permissionOfRole  是一个对象   调用里面的name属性
            $this->permission[] = $permissionOfRole->name;
        }
    }
    //保存修改操作
    public function updateRole($name){
        $authManager = \Yii::$app->authManager;
        //判断修改后的角色是否已经存在
        if($name != $this->name && $authManager->getRole($this->name)){
            //存在则报错
            $this->addError('name','该角色已存在');
            return false;
        }else{
            //获取当前角色
            $role = $authManager->getRole($name);
            //修改名字和描述等
            $role->name = $this->name;
            $role->description = $this->description;
            if($authManager->update($name,$role)){
                //清除掉name对应的 所有的 权限 removeChildren()
                $authManager->removeChildren($role);
                //修改name对应的角色及其权限
                foreach ($this->permission as $permissionName){
                    //获取权限
                    $permissionOfRole = $authManager->getPermission($permissionName);
                    if($permissionOfRole){
                        $authManager->addChild($role,$permissionOfRole);
                    }
                }
                return true;
            }
        }
    }
}