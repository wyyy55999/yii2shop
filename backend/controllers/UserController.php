<?php

namespace backend\controllers;

use backend\models\User;
use backend\models\UserLoginForm;
use backend\models\UserRoleForm;
use yii\data\Pagination;

class UserController extends \yii\web\Controller
{
    //管理员列表
    public function actionIndex(){
        //实例化分页工具条
        $page = new Pagination([
            'totalCount'=>User::find()->where('status > -1')->count(),
            'defaultPageSize'=>15,
        ]);
       $admins = User::find()->where('status > -1')->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['admins'=>$admins,'page'=>$page]);
    }
    //添加管理员
    public function actionAdd(){
        $admin = new User();
        //使用场景
        $admin->scenario = User::SCENARIO_ADD;
        if(\Yii::$app->request->isPost) {
            $admin->load(\Yii::$app->request->post());
            if ($admin->validate()) {
                //加盐加密
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($admin->password);
                if($admin->save(false)){
                    //获取上一次添加的数据的id
                    $id = $admin->id;
                    if($admin->addRoleToUser($id)){
                        \Yii::$app->session->setFlash('success', '添加管理员成功');
                        return $this->redirect(['user/index']);
                    }
                }
            }
        }
        $admin->status = 1;
        return $this->render('option',['admin'=>$admin]);
    }
    //修改管理员
    public function actionUpdate($id){
        $admin = User::findOne(['id'=>$id]);
        //使用场景
        $admin->scenario = User::SCENARIO_UPDATE;
        if(\Yii::$app->request->isPost){
            $admin->load(\Yii::$app->request->post());
            if($admin->validate()){
                $admin->save(false);
                \Yii::$app->session->setFlash('success','修改管理员成功');
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('option',['admin'=>$admin]);
    }
    //删除管理员
    public function actionDelete($id){
        $admin = User::findOne(['id'=>$id]);
        $admin->status = -1;
        $admin->save(false);
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['user/index']);
    }
    //登录
    public function actionLogin(){
        $user_login = new UserLoginForm();
        if(\Yii::$app->request->isPost){
            if($user_login->load(\Yii::$app->request->post()) && $user_login->validate()){
                $cur_admin = User::findOne(['username'=>$user_login->username]);
                //最后登录时间
                $cur_admin->last_login_time = time();
                //最后登录ip
                $cur_admin->last_login_ip = \Yii::$app->request->userIP;
                $cur_admin->save(false);
                \Yii::$app->session->setFlash('warning','登录成功，您的登录时间为 '.date('Y-m-d H:i:s',time()));
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('login',['user_login'=>$user_login]);
    }
    //注销登录
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }
    //修改角色
    public function actionChangeRole($id){
        $admin = User::findOne(['id'=>$id]);
        //使用场景
//        $admin->scenario = User::SCENARIO_ADD;
        if($admin->load(\Yii::$app->request->post())){
            if($admin->updateUserRole($id)){
                \Yii::$app->session->setFlash('warning','修改角色成功');
                return $this->redirect(['user/index']);
            }
        }
        $roles = \Yii::$app->authManager->getRolesByUser($id);
        $admin->loadCurrentUserRole($roles);
        return $this->render('change-role',['admin'=>$admin]);
    }
    //修改密码
    public function actionChangePwd(){
        $admin = User::findOne(['id'=>\Yii::$app->user->identity->id]);
        $admin->scenario = User::SCENARIO_CHANG_PWD;
        if($admin->load(\Yii::$app->request->post()) && $admin->validate()){
            \Yii::$app->session->setFlash('success','修改密码成功,请重新登录');
            return $this->redirect(['user/login']);
        }
        return $this->render('change-pwd',['admin'=>$admin]);
    }
}
