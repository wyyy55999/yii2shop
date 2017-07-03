<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    //密码  明文
    public $password;
    //确认密码
    public $repassword;
    //验证码
    public $code;
    //阅读并同意
    public $read_agree;
    //手机验证码
    public $sms_code;
    //定义场景常量
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_API = 'api';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password','repassword','email', 'tel','sms_code'], 'required'],
            [['last_login_time', 'last_login_ip', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
            ['code','captcha','captchaAction'=>'site/captcha','on'=>self::SCENARIO_REGISTER],
            ['code','captcha','captchaAction'=>'api/captcha','on'=>self::SCENARIO_API],
            ['repassword','compare','compareAttribute'=>'password','message'=>'两次输入的密码不一致'],
            ['email','email'],
            ['tel','match','pattern'=>'/^1[34578]\d{9}$/','message'=>'请输入正确的手机号'],
            ['username','unique','message'=>'该用户名已被使用'],
            ['email','unique','message'=>'该邮箱已被使用'],
            ['read_agree','safe'],
            //验证手机号码
            ['sms_code','validateSms'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'password'=>'密码',
            'repassword'=>'确认密码',
            'email' => '邮箱',
            'tel' => '电话',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登陆ip',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
            'code'=>'验证码',
            'read_agree'=>'',
            'sms_code'=>'手机验证码'
        ];
    }
    //创建时间
    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at = time();
        }
        return parent::beforeSave($insert);
    }
    //加盐加密  给一个唯一的auth_key
    public function registerSave(){
        //if($this->read_agree){
            if($this->repassword == $this->password){
                $this->status = 1;  //设置状态默认为正常
                $this->auth_key = Yii::$app->security->generateRandomString();
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                return true;
            }
        //}
        return false;
    }
    //验证手机号
    public function validateSms(){
        //根据set的key  获取value
        $tel_value = Yii::$app->cache->get('tel_'.$this->tel);
        if(!$tel_value || $this->sms_code != $tel_value){  //如果不存在value或者当前输入的验证码不等于设置的验证码  就报错
            $this->addError('sms_code','验证码有误，请重新输入');
        }
    }
    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() == $authKey;
    }
}
