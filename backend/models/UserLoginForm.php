<?php

namespace backend\models;

use yii\base\Model;

class UserLoginForm extends Model
{
    //用户名和密码
    public $username;
    public $password;
    public $is_remember;
    //规则
    public function rules()
    {
        return [
            [['username','password'],'required'],
            [['username','password'],'LoginCheck'],
            ['is_remember','number'],
        ];
    }
    //中文
    public function attributeLabels()
    {
        return [
            'username'=>'登录名',
            'password'=>'登录密码',
            'is_remember'=>'记住密码'
        ];
    }
    //自定义规则
    public function LoginCheck(){
        //通过帐号查找用户
        $admin = User::findOne(['username'=>$this->username]);
        //用户存在就验证密码
        if($admin){
            //密码比对错误就添加错误
            if(!\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){
                $this->addError('username','用户名或密码错误');
            }else{
                //cookie的过期时间  如果点击了自动登录  保存一周  否则不保存
                $duration = $this->is_remember ? 7*24*3600 : 0;
                //密码比对正确就登录
                \Yii::$app->user->login($admin,$duration);
            }
        }else{
            //用户不存在就添加错误
            $this->addError('username','用户名或密码错误');
        }
    }
}