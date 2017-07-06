<?php

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsIntro;
use frontend\models\Address;
use frontend\models\Member;
use frontend\models\Order;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;

class JxWechatController extends Controller
{
    //关闭跨站攻击验证
    public $enableCsrfValidation = false;

    //首页
    public function actionIndex()
    {
        $app = new Application(\Yii::$app->params['wechat']);
        // 从项目实例中得到服务端应用实例。
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            switch ($message->MsgType) {
                case 'event':
                    if ($message->Event == 'CLICK' && $message->EventKey == 'PROMOTION_PRODUCT') {
                        //随意找五条商品
                        $goodses = Goods::find()->limit(5)->all();
                        foreach ($goodses as $goods) {
                            $goods_intro = GoodsIntro::findOne(['goods_id' => $goods->id]);
                            $news[] = new News([
                                'title' => $goods->name,
                                'description' => $goods_intro->content,
                                'url' => Url::to(['goods/detail', 'goods_id' => $goods->id], true),
                                'image' => 'http://dlm.penneyx.cn/' . $goods->logo,
                            ]);
                        }
                        return $news;
                    }
                    break;
                case 'text':
                    switch ($message->Content) {
                        case '清除缓存':
                            $url = Url::to(['jx-wechat/test'], true);  //这里第二个参数设置为true
                            return '点此清除缓存：' . $url;
                            break;
                        case '帮助':
                            return '您可以发送 优惠、解除绑定 等来获取详细信息';
                            break;
                        case '优惠':
                            $goodses = Goods::find()->limit(5)->all();
                            foreach ($goodses as $goods) {
                                $goods_intro = GoodsIntro::findOne(['goods_id' => $goods->id]);
                                $news[] = new News([
                                    'title' => $goods->name,
                                    'description' => $goods_intro->content,
                                    'url' => Url::to(['goods/detail', 'goods_id' => $goods->id], true),
                                    'image' => 'http://dlm.penneyx.cn/' . $goods->logo,
                                ]);
                            }
                            return $news;
                            break;
                        case '解除绑定':
                            $openid = $message->FromUserName;
                            $member = Member::findOne(['openid'=>$openid]);
                            if($member != null){
                                $member->openid = null;
                                if($member->save(false)){
                                    return '解除绑定成功';
                                }else{
                                    return '解除绑定失败';
                                }
                            }else{
                                return '请您先绑定账户 http://dlm.penneyx.cn/yii2shop/frontend/web/jx-wechat/bind.html';
                            }
                            break;
                    }
            }
        });
        $response = $server->serve();
        $response->send(); // Laravel 里请使用：return $response;
    }

    //菜单
    public function actionMenu()
    {
        $app = new Application(\Yii::$app->params['wechat']);
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "click",
                "name" => "促销商品",
                "key" => "PROMOTION_PRODUCT"
            ],
            [
                "type" => "view",
                "name" => "在线商城",
                "url" => Url::to(['index/index'], true)
            ],
            [
                "name" => "个人中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "绑定账户",
                        "url" => Url::to(['jx-wechat/bind'], true)
                    ],
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url" => Url::to(['jx-wechat/order'], true)
                    ],
                    [
                        "type" => "view",
                        "name" => "收货地址",
                        "url" => Url::to(['jx-wechat/address'], true)
                    ],
                    [
                        "type" => "view",
                        "name" => "修改密码",
                        "url" => Url::to(['jx-wechat/change-pwd'], true)
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
        $menus = $menu->all();
        var_dump($menus);
    }

    //绑定
    public function actionBind()
    {
        //获取回调函数中保存在session的openid
        $openid = \Yii::$app->session->get('openid');
        $member = Member::findOne(['openid'=>$openid]);
        //判断是否已经绑定其他用户
        if($member){
            echo '请不要重复绑定，您已绑定用户:'.$member->username.'<br/>';
            echo Html::a('点此解除该用户的绑定',['jx-wechat/unbind','member_id'=>$member->id]);
            exit;
        }
        if ($openid == null) {
            //获取用户的基本信息（openid），需要通过微信网页授权
            $app = new Application(\Yii::$app->params['wechat']);
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();
            //返回响应值
            $response->send();
        }
        if (\Yii::$app->request->isPost) {
            $username = \Yii::$app->request->post('username');
            $password = \Yii::$app->request->post('password');
            if ($username != null && $password != null) {
                $member = Member::findOne(['username' => $username]);
                if ($member != null) {  //存在用户  比对密码
                    if (\Yii::$app->security->validatePassword($password, $member->password_hash)) {
                        if (\Yii::$app->user->login($member)) {
                            Member::updateAll(['openid' => $openid], 'id=' . $member->id);
                            $member->last_login_time = time();
                            $member->last_login_ip = ip2long(\Yii::$app->request->userIP);
                            $member->save(false);
                            if (\Yii::$app->session->get('redirect')){
                                return $this->redirect([\Yii::$app->session->get('redirect')]);
                            }
                        } else {
                            echo '绑定失败';
                            exit;
                        }
                    } else {
                        echo '用户名或密码错误';
                        exit;
                    }
                } else {
                    echo '该用户不存在';
                    exit;
                }
            }
        }
        return $this->renderPartial('bind');
    }

    //解除绑定
    public function actionUnbind($member_id)
    {
        \Yii::$app->user->logout();
        $member = Member::findOne(['id'=>$member_id]);
        if($member != null){
            $member->openid = null;
            if($member->save(false)){
                echo '解除绑定成功';
            }else{
                echo '解除绑定失败';
            }
        }
    }

    //我的订单
    public function actionOrder()
    {
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){  //没有openid
            //设置跳转地址
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //获取用户的基本信息（openid），需要通过微信网页授权
            $app = new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();
            //返回响应值
            $response->send();
        }else{
            //根据openid查到user
            $cur_member = Member::findOne(['openid'=>$openid]);
            //如果用户存在，说明已经绑定
            if($cur_member != null){
                $orders = Order::findAll(['member_id'=>$cur_member->id]);
                return $this->renderPartial('order',['orders'=>$orders]);
            }else{
                return $this->redirect(['jx-wechat/bind']);
            }
        }
    }

    //修改密码
    public function actionChangePwd()
    {
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){  //没有openid
            //设置跳转地址
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //获取用户的基本信息（openid），需要通过微信网页授权
            $app = new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();
            //返回响应值
            $response->send();
        }else{
            //根据openid获取到当前用户
            $member = Member::findOne(['openid'=>$openid]);
            if($member){
                if(\Yii::$app->request->isPost){
                    $old_password = \Yii::$app->request->post('old_password');
                    $new_password = \Yii::$app->request->post('new_password');
                    $re_password = \Yii::$app->request->post('re_password');
                    if(!empty($old_password) && !empty($new_password) && !empty($re_password)){
                        if(\Yii::$app->security->validatePassword($old_password,$member->password_hash)){
                            if($new_password == $re_password){
                                $member->password_hash = \Yii::$app->security->generatePasswordHash($new_password);
                                if($member->save(false)){
                                    echo '修改密码成功';
                                }else{
                                    echo '修改密码失败';
                                }
                            }else{
                                echo '两次输入的密码不一致';
                            }
                        }else{
                            echo '旧密码错误';
                        }
                    }
                }
                return $this->renderPartial('change-pwd');
            }else{
                return $this->redirect(['jx-wechat/bind']);
            }
        }
    }

    //收货地址
    public function actionAddress(){
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){  //没有openid
            //设置跳转地址
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //获取用户的基本信息（openid），需要通过微信网页授权
            $app = new Application(\Yii::$app->params['wechat']);
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();
            //返回响应值
            $response->send();
        }else{
            $member = Member::findOne(['openid'=>$openid]);
            if($member){
                $addresses = Address::findAll(['member_id'=>$member->id]);
                return $this->renderPartial('address',['addresses'=>$addresses]);
            }else{
                return $this->redirect(['jx-wechat/bind']);
            }
        }
    }

    //回调
    public function actionCallback()
    {
        $app = new Application(\Yii::$app->params['wechat']);
        $user = $app->oauth->user();
        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用
        //将openid放入session  以便在member操作中取出
        \Yii::$app->session->set('openid', $user->getId());
        //var_dump($user->getId());exit;
        //返回
        return $this->redirect([\Yii::$app->session->get('redirect')]);
    }

    //测试
    public function actionTest()
    {
        /*$goodses = Goods::find()->limit(5)->all();
        foreach ($goodses as $goods){
            var_dump($goods->name);
        }*/
        if (\Yii::$app->session->remove('openid')) {
            echo 6666;
        }
    }
}
