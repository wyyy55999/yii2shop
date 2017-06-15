<?php

namespace backend\controllers;

use backend\models\User;
use backend\models\UserLoginForm;

class UserController extends \yii\web\Controller
{
    //管理员列表
    public function actionIndex(){
        $admins = User::find()->where('status > -1')->all();
        return $this->render('index',['admins'=>$admins]);
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
                $admin->save(false);
                \Yii::$app->session->setFlash('success', '添加管理员成功');
                return $this->redirect(['user/index']);
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
                \Yii::$app->session->setFlash('warning','登录成功');
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

}
