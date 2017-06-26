<?php

namespace frontend\controllers;

use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\MemberLoginForm;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

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
                    return $this->redirect(['member/login']);
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
                //同步到数据库
                //获取到cookie中的数据
                $cookies = \Yii::$app->request->cookies;
                $cookie = $cookies->get('cart');
                if($cookie == null){  //如果购物车没有数据  那就是空数组
                    $cart = [];
                }else{
                    $cart = unserialize($cookie->value);   //这是购物车的数据
                }
                //遍历cart数组  存入数据库
                foreach ($cart as $goods_id=>$amount){
                    $cart_model = new Cart();
                    $member_goods = Cart::findOne(['member_id'=>\Yii::$app->user->id,'goods_id'=>$goods_id]);
                    if($member_goods){
                        $member_goods->amount += $amount;
                        $member_goods->save(false);
                    }else{
                        $cart_model->member_id = \Yii::$app->user->id;
                        $cart_model->goods_id = $goods_id;
                        $cart_model->amount = $amount;
                        $cart_model->save(false);
                    }
                }
                $cookies = \Yii::$app->response->cookies;
                $cookies->remove('cart');  //清除cookie中的cart
                return $this->redirect(['index/index']);
            }
        }
        return $this->render('login',['member_login'=>$member_login]);
    }
    //注销登录
    public function actionLogout(){
        @\Yii::$app->user->logout();
        return $this->redirect(['member/login']);
    }
    //首页
    public function actionIndex()
    {
        //var_dump(\Yii::$app->user->identity);
        return $this->renderPartial('index');
    }
    //发送验证短信
    public function actionSendSms(){
        //获取电话
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel) && $tel != null){
            echo '手机号有误';
            exit;
        }
        //发送短信
        $code = rand(100000,999999);
        //$result = \Yii::$app->sms->setTel($tel)->setParam(['code'=>$code])->send();
        $result = 1;
        if($result){
            //如果发送成功，保存验证码到cache缓存  /  redis   /  session  /  mysql
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);//第三个参数是过期时间  也就是验证码超过五分钟就无效
            echo Json::encode(['msg'=>'success','code'=>$code]);
        }else{
            echo Json::encode(['msg'=>'fail','code'=>$code]);
        }
    }
    //测试登录
    public function actionTest(){
        if(\Yii::$app->user->isGuest){
            echo '游客';
        }else{
            var_dump(\Yii::$app->user->identity);
            echo '登录成功';
        }
    }
    //测试短信插件
    public function actionTestMsg(){
       /*// 配置信息
        $config = [
            'app_key'    => '24479675',
            'app_secret' => 'e5168ed9e6006dcd1f129843b7959f82',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];


        // 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;
        $code = rand(100000, 999999);
        $req->setRecNum('18208132747')   //设置发给某用户
            ->setSmsParam([
                'code' => $code  //${code}
            ])
            ->setSmsFreeSignName('姚倩的网站')//设置短信签名，必须是已审核的
            ->setSmsTemplateCode('SMS_71585182');//设置短信模版id  必须审核通过

        $resp = $client->execute($req);
        var_dump($resp);
        var_dump($code);*/
        $code = rand(100000, 999999);
        $res = \Yii::$app->sms->setTel(18208132747)->setParam(['code'=>$code])->send();
        var_dump($res);
        if($res){
            echo '发送成功，验证码是：'.$code;
        }else{
            echo '发送失败';
        }
    }
}
