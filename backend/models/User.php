<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property string $last_login_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    //密码和确认密码
    public $repassword;
    public $password;
    //修改密码的旧密码
    public $old_password;
    //状态
    static public $statusOptions = [1=>'正常',0=>'禁用'];
    //定义权限变量
    public $roles = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username',  'password_hash', 'email','repassword'], 'required'],
            [['status', 'created_at', 'updated_at', 'last_login_time'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'last_login_ip'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            ['username', 'unique','message'=>'该管理员名已经存在'],
            ['email','unique','message'=>'该邮箱已被使用'],
            //验证邮箱 使用正则
            ['email','match','pattern'=>'/[\w\-\.]+@[\w\-\.]+\.[\w]+/','message'=>'您输入的电子邮箱格式有误'],
            [['password_reset_token'], 'unique'],
            /*[['password_hash','repassword'],'validatePassword'],*/
            //用确认密码和密码进行比较
            ['repassword','compare','compareAttribute'=>'password','message'=>'两次输入的密码不一致'],
            ['roles','safe'],
            ['old_password','validateOldpwd'],
        ];
    }
    /*//自定义验证规则  ==>  密码和确认密码
    public function validatePassword(){
        if($this->password_hash != $this->repassword){
            $this->addError('password_hash','两次输入的密码不一致');
        }
    }*/
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '管理员名',
            'auth_key' => 'Auth Key',
            'password' => '密码',
            'repassword'=>'确认密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '电子邮箱',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'roles'=>'选择角色',
            'old_password'=>'旧密码',
        ];
    }

    //创建时间和修改时间
    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at = time();
            //生成随机字符串作为自动登录验证口令
            $this->auth_key = Yii::$app->security->generateRandomString();
        }else{
            $this->updated_at = time();
        }
        return parent::beforeSave($insert);
    }

    //定义场景常量
    const SCENARIO_ADD = 'add';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CHANG_PWD = 'change-pwd';
    //定义场景字段
    public function scenarios()
    {
        $scenarios =  parent::scenarios();
        $scenarios[self::SCENARIO_ADD] = ['username',  'password', 'email','repassword','status', 'created_at', 'updated_at', 'last_login_time','auth_key','password_reset_token','roles'];
        $scenarios[self::SCENARIO_UPDATE] = ['username', 'email','status', 'created_at', 'updated_at', 'last_login_time','auth_key','password_reset_token','roles'];
        $scenarios[self::SCENARIO_CHANG_PWD] = ['password','repassword','old_password'];
        return $scenarios;
    }

    //获取所有角色
    public static function getRoles(){
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(),'name','name');
    }

    //给用户添加角色
    public function addRoleToUser($id){
        $authManager = \Yii::$app->authManager;
        //遍历当前选中的角色
        foreach ($this->roles as $roleName){
            //根据遍历出来的角色名字找到角色对象
            $role= $authManager->getRole($roleName);
            //将角色对象分配给用户
            $authManager->assign($role,$id);
        }
        return true;
    }

    //获取当前用户的角色
    public function loadCurrentUserRole($roles){
        //循环回显
        foreach ($roles as $role){
            $this->roles[] = $role->name;
        }
    }

    //修改用户角色
    public function updateUserRole($id){
        //清除角色分配好的权限
        Yii::$app->authManager->revokeAll($id);
        //循环添加
        foreach ($this->roles as $roleName){
            $roleObj = Yii::$app->authManager->getRole($roleName);
            Yii::$app->authManager->assign($roleObj,$id);
        }
        return true;
    }

    //验证旧密码
    public function validateOldpwd(){
        //通过id查找用户
        $admin = User::findOne(['id'=>Yii::$app->user->identity->id]);
        if($admin){
            if(!Yii::$app->security->validatePassword($this->old_password,$admin->password_hash)){
                $this->addError('old_password','密码错误');
                return false;
            }else{
                $admin->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $admin->save(false);
                Yii::$app->user->logout();
                return true;
            }
        }else{
            $this->addError('old_password','您的密码错误');
            return false;
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

   /* public function login($identity, $duration = 0)
    {
        if ($this->beforeLogin($identity, false, $duration)) {
            $this->switchIdentity($identity, $duration);
            $id = $identity->getId();
            $ip = Yii::$app->getRequest()->getUserIP();
            Yii::info("User '$id' logged in from $ip with duration $duration.", __METHOD__);
            $this->afterLogin($identity, false, $duration);
        }
        return !$this->getIsGuest();
    }*/
}
