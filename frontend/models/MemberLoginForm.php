<?php
namespace frontend\models;


use yii\base\Model;

class MemberLoginForm extends Model
{
    public $username;
    public $password;
    public $code;
    public $rememberPwd;
    //定义场景常量
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_API = 'api';

    //规则
    public function rules()
    {
        return [
            [['username','password','code'],'required'],
            ['rememberPwd','safe'],
            ['code','captcha','captchaAction'=>'site/captcha','on'=>self::SCENARIO_LOGIN],
            ['code','captcha','captchaAction'=>'api/captcha','on'=>self::SCENARIO_API],
            [['username','password'],'MemberLogin'],
        ];
    }
    //标签
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'code'=>'验证码',
            'rememberPwd'=>'记住密码'
        ];
    }
    //登录
    public function MemberLogin(){
        //找到当前用户
        $current_member = Member::findOne(['username'=>$this->username]);
        if($current_member){
            //验证密码
            if(!\Yii::$app->security->validatePassword($this->password,$current_member->password_hash)){
                $this->addError('username','用户名或密码错误');
            }else{
                //cookie的过期时间  如果点击了自动登录  保存一周  否则不保存
                $duration = $this->rememberPwd ? 7*24*3600 : 0;
                \Yii::$app->user->login($current_member,$duration);
            }
        }else{
            $this->addError('username','用户名或密码错误');
        }
    }
}