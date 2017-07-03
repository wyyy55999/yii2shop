<?php

namespace frontend\controllers;


use frontend\models\Member;
use frontend\models\MemberLoginForm;
use frontend\models\Order;
use yii\helpers\Url;
use yii\web\Controller;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;

class WechatController extends Controller
{
    public $enableCsrfValidation = false;  //关闭跨站攻击验证

    //用于接收微信服务器发送的请求
    public function actionIndex(){
        //实例化
        $app = new Application(\Yii::$app->params['wechat']);
        // 从项目实例中得到服务端应用实例。
        $server = $app->server;
        $server->setMessageHandler(function ($message) {
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            switch ($message->MsgType) {
                case 'text':  //如果传输类型是文本
                    //判断是否为成都
                    switch ($message->Content){
                        case '成都':
                            $xml = simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
                            foreach ($xml as $city){
                                if($city['cityname'] == "成都"){
                                    $weather = $message->Content."的天气是：".$city['stateDetailed'].$city['tem1'].'℃ ~ '.$city['tem2'].'℃';
                                    break;
                                }
                            }
                            return $weather;
                            break;
                        case '登录':
                            $url = Url::to(['wechat/login'],true);  //这里第二个参数设置为true
                            return '点此登录：'.$url;
                            break;
                        case '最新活动':
                            //单图文
                            $news = new News([
                                'title'       => '买一送一大酬宾',
                                'description' => '走过路过不要错过',
                                'url'         => 'https://www.baidu.com/',
                                'image'       => 'http://pic74.nipic.com/file/20150810/10115763_233338370780_2.jpg',
                            ]);
                            return $news;
                            break;
                        case '全部活动':
                            //多图文
                            $news1 = new News([
                                'title'       => '买一送一大酬宾',
                                'description' => '走过路过不要错过',
                                'url'         => 'https://www.baidu.com/',
                                'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                            ]);
                            $news2 = new News([
                                'title'       => '低价酬宾',
                                'description' => '回馈新老客户',
                                'url'         => 'https://www.baidu.com/',
                                'image'       => 'http://pic74.nipic.com/file/20150810/10115763_233338370780_2.jpg',
                            ]);
                            $news3 = new News([
                                'title'       => '买买买',
                                'description' => '你就是爱买的女孩',
                                'url'         => 'https://www.baidu.com/',
                                'image'       => 'http://pic3.16pic.com/00/37/06/16pic_3706700_b.jpg',
                            ]);
                            return [$news1,$news2,$news3];
                            break;
                        case '注销':
                            $url = Url::to(['wechat/logout'],true);
                            return '点此注销登录：'.$url;
                            break;
                    }
                    return $message->Content;
                    break;
                case 'event': //传输类型是事件
                    //判断事件类型是否为点击事件  事件的key是否为V1001_RECENT_ACTIVITY
                    if($message->Event == 'CLICK' && $message->EventKey == 'V1001_RECENT_ACTIVITY'){
                        $news1 = new News([
                            'title'       => '买一送一大酬宾',
                            'description' => '走过路过不要错过',
                            'url'         => 'https://www.baidu.com/',
                            'image'       => 'http://pic27.nipic.com/20130131/1773545_150401640000_2.jpg',
                        ]);
                        $news2 = new News([
                            'title'       => '低价酬宾',
                            'description' => '回馈新老客户',
                            'url'         => 'https://www.baidu.com/',
                            'image'       => 'http://pic74.nipic.com/file/20150810/10115763_233338370780_2.jpg',
                        ]);
                        $news3 = new News([
                            'title'       => '买买买',
                            'description' => '你就是爱买的女孩',
                            'url'         => 'https://www.baidu.com/',
                            'image'       => 'http://pic3.16pic.com/00/37/06/16pic_3706700_b.jpg',
                        ]);
                        return [$news1,$news2,$news3];
                    }
                    break;
            }
        });
        $response = $server->serve();
        $response->send(); // Laravel 里请使用：return $response;
    }
    //设置菜单
    public function actionSetMenu(){
        $app = new Application(\Yii::$app->params['wechat']);
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "click",
                "name" => "全部活动",
                "key"  => "V1001_RECENT_ACTIVITY"
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "个人中心",
                        "url"  => Url::to(['wechat/member'],true)
                    ],
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url"  => Url::to(['wechat/order'],true)
                    ],
                    [
                        "type" => "view",
                        "name" => "绑定账户",
                        "url" => Url::to(['wechat/login'],true)
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
        //获取已设置的菜单
        $menus = $menu->all();
        var_dump($menus);
    }
    //个人中心
    public function actionMember(){
        //获取回调函数中保存在session的openid
        $openId = \Yii::$app->session->get('openId');
        if($openId == null){
            $app = new Application(\Yii::$app->params['wechat']);
            //获取用户的基本信息（openid），需要通过微信网页授权
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();
            //返回响应值
            $response->send();
        }
        var_dump($openId);
    }
    //订单
    public function actionOrder(){
        //需要open_id 和 member_id
        //获取回调函数中保存在session的openid
        $openId = \Yii::$app->session->get('openId');
        if($openId == null){
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //获取用户的基本信息（openid），需要通过微信网页授权
            $app = new Application(\Yii::$app->params['wechat']);
            //发起微信网页授权
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();
            //返回响应值
            $response->send();
        }else{
            //通过openid找到当前用户
            $cur_member = Member::findOne(['openid'=>$openId]);
            //如果没有找到该用户，说明没有登录，引导用户登录
            if($cur_member == null){
                return $this->redirect(['wechat/login']);
            }else{
                //如果找到了
                $orders = Order::findAll(['member_id'=>$cur_member->id]);
                //打印结果
                var_dump($orders);
            }
        }
    }
    //登录
    public function actionLogin(){
        //获取回调函数中保存在session的openid
        $openId = \Yii::$app->session->get('openId');
        if($openId == null){
            $app = new Application(\Yii::$app->params['wechat']);
            //获取用户的基本信息（openid），需要通过微信网页授权
            $response = $app->oauth->scopes(['snsapi_base'])->redirect();
            //返回响应值
            $response->send();
        }
        if(\Yii::$app->request->isPost){
            //让用户登录，如果登录成功，将openid写入当前登录账户
            $request = \Yii::$app->request;
            if(\Yii::$app->request->isPost){
                $user = Member::findOne(['username'=>$request->post('username')]);
                if($user && \Yii::$app->security->validatePassword($request->post('password'),$user->password_hash)){
                    \Yii::$app->user->login($user);
                    //如果登录成功，将openid写入当前登录账户
                    Member::updateAll(['openid'=>$openId],'id='.$user->id);
                    if(\Yii::$app->session->get('redirect')) return $this->redirect([\Yii::$app->session->get('redirect')]);
                    echo '绑定成功';exit;
                }else{
                    echo '登录失败';exit;
                }
            }
        }
        return $this->renderPartial('login');
    }
    //注销登录
    public function actionLogout(){
        if(\Yii::$app->user->logout()){
            if(\Yii::$app->session->removeAll()){
                echo '注销成功';
            }
        }
    }
    //回调
    public function actionCallback(){
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
        \Yii::$app->session->set('openId',$user->getId());
        //var_dump($user->getId());
        //返回
        return $this->redirect([\Yii::$app->session->get('redirect')]);
    }
}