<?php

namespace backend\controllers;


use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends PublicController
{
    //显示权限列表
    public function actionPermissionIndex(){
        //实例化权限组件
        $authManager = \Yii::$app->authManager;
        //获取权限
        $permissions = $authManager->getPermissions();
        return $this->render('permission-index',['permissions'=>$permissions]);
    }
    //添加权限
    public function actionPermissionAdd(){
        //实例化表单模型
        $permission_model = new PermissionForm();
        if($permission_model->load(\Yii::$app->request->post()) && $permission_model->validate()){
            //调用模型中的自定义添加方法  验证是否成功，成功则提示并跳转
            if($permission_model->addPermission()){
                \Yii::$app->session->setFlash('success','添加权限成功');
                return $this->redirect(['rbac/permission-index']);
            }
        }
        return $this->render('permission-add',['permission_model'=>$permission_model]);
    }
    //修改权限  $name ==> 是指权限的名称（修改前的）
    public function actionPermissionUpdate($name){
        //实例化权限组件
        $authManager = \Yii::$app->authManager;
        //获取当前权限
        $permission = $authManager->getPermission($name);
        //判断权限是否存在
        if($permission == null){
            throw new NotFoundHttpException('该权限不存在');
        }
        $permission_model = new PermissionForm();  //实例化表单模型
        //获取数据  回显
        $permission_model->loadData($permission);
        //加载并验证数据
        if($permission_model->load(\Yii::$app->request->post()) && $permission_model->validate()){
            //调用模型中的自定义修改方法  验证是否成功，成功则提示并跳转
            if($permission_model->updatePermission($name)){
                \Yii::$app->session->setFlash('success','修改权限成功');
                return $this->redirect(['rbac/permission-index']);
            }
        }
        return $this->render('permission-add',['permission_model'=>$permission_model]);
    }
    //删除权限
    public function actionPermissionDelete($name){
        //获取需要删除掉的权限
        $permission = \Yii::$app->authManager->getPermission($name);
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('danger','删除权限成功');
        return $this->redirect(['rbac/permission-index']);
    }

    //显示角色列表
    public function actionRoleIndex(){
        //获取所有角色
        $roles = \Yii::$app->authManager->getRoles();
        return $this->render('role-index',['roles'=>$roles]);
    }
    //添加角色
    public function actionRoleAdd(){
        $role_model = new RoleForm();
        if($role_model->load(\Yii::$app->request->post()) && $role_model->validate()){
            //调用表单模型中添加角色的方法
            if($role_model->addRole()){
                \Yii::$app->session->setFlash('success','添加角色成功');
                return $this->redirect(['rbac/role-index']);
            }
        }
        //获取到所有权限
//        $permissions = \Yii::$app->authManager->getPermissions();
//        $permissions = ArrayHelper::map($permissions,'name','description');
//        ,'permissions'=>$permissions
        return $this->render('role-add',['role_model'=>$role_model]);
    }
    //修改角色
    public function actionRoleUpdate($name){
        $role_model = new RoleForm();
        //获取角色
        $role  = \Yii::$app->authManager->getRole($name);
        if($role == null){
            throw new NotFoundHttpException('该角色不存在');
        }
        //加载回显的数据
        $role_model->loadRole($role);
        //验证post提交的数据
        if($role_model->load(\Yii::$app->request->post()) && $role_model->validate()){
            //调用表单模型的方法 保存数据
            if($role_model->updateRole($name)){
                \Yii::$app->session->setFlash('success','修改角色成功');
                return $this->redirect(['rbac/role-index']);
            }
        }
        //获取权限
        //$permissions = ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','description');
        return $this->render('role-add',['role_model'=>$role_model]);
    }
    //删除角色
    public function actionRoleDelete($name){
        $role = \Yii::$app->authManager->getRole($name);
        \Yii::$app->authManager->remove($role);
        \Yii::$app->session->setFlash('danger','删除角色成功');
        return $this->redirect(['rbac/role-index']);
    }
}