<?php

namespace frontend\controllers;

use frontend\models\Member;
use frontend\models\MemberLoginForm;

class MemberController extends \yii\web\Controller
{
    public $layout = 'login';
    //注册
    public function actionRegister(){
        $member_reg = new Member();
        if(\Yii::$app->request->isPost){
            if($member_reg->load(\Yii::$app->request->post()) && $member_reg->validate()){
                if($member_reg->registerSave()){
                    $member_reg->save(false);
                    \Yii::$app->session->setFlash('success','注册成功');
                    return $this->redirect(['member/test']);
                }
            }
        }
        return $this->render('register',['member_reg'=>$member_reg]);
    }
    //登录
    public function actionLogin(){
        $member_login = new MemberLoginForm();
        if(\Yii::$app->request->isPost){
            if($member_login->load(\Yii::$app->request->post()) && $member_login->validate()){
                $current_member = Member::findOne(['username'=>$member_login->username]);
                //成功则保存当前时间和ip
                $current_member->last_login_time = time();
                $current_member->last_login_ip =  ip2long(\Yii::$app->request->userIP);
                $current_member->save(false);
                return $this->redirect(['member/test']);
            }
        }
        return $this->render('login',['member_login'=>$member_login]);
    }
    //注销登录
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->render('index');
    }
    //首页
    public function actionIndex()
    {
        //var_dump(\Yii::$app->user->identity);
        return $this->renderPartial('index');
    }
    //测试
    public function actionTest(){
        if(\Yii::$app->user->isGuest){
            echo '游客';
        }else{
            var_dump(\Yii::$app->user->identity);
            echo '登录成功';
        }
    }

}
