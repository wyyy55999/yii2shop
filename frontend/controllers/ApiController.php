<?php
namespace frontend\controllers;


use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\MemberLoginForm;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends Controller
{
    //关闭跨站攻击
    public $enableCsrfValidation = false;
    //设置响应头
    public function init()
    {
        //相当于设置响应头为json格式
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }
    //会员注册
    public function actionUserRegister(){
        if(\Yii::$app->request->isPost){
            $member_model = new Member();
            //使用场景
            $member_model->scenario = Member::SCENARIO_API;
            $request = \Yii::$app->request;
            //接收传值
            $member_model->username = $request->post('username');
            $member_model->password = $request->post('password');
            $member_model->repassword = $request->post('repassword');
            $member_model->email = $request->post('email');
            $member_model->tel = $request->post('tel');
            $member_model->sms_code = $request->post('sms_code');
            $member_model->code = $request->post('code');
            $member_model->read_agree = $request->post('read_agree');
            if($member_model->validate()){
              /*  $member_model->password_hash = \Yii::$app->security->generatePasswordHash($request->post('password'));*/
                if($member_model->registerSave()){
                    if($member_model->save(false)){
                        return ['status'=>1,'msg'=>'注册成功'];
                    }else{
                        return ['status'=>0,'msg'=>'注册失败'];
                    }
                }
            }else{
                return ['status'=>'-1','msg'=>$member_model->getErrors()];
            }
        }else{
            return ['status'=>'-1','msg'=>'请求方式有误'];
        }
    }
    //会员登录
    public function actionUserLogin(){
        if(\Yii::$app->request->isPost) {
            $member_model = new MemberLoginForm();
            //使用场景
            $member_model->scenario = MemberLoginForm::SCENARIO_API;
            //获取传过来的值
            $member_model->username = \Yii::$app->request->post('username');
            $member_model->password = \Yii::$app->request->post('password');
            $member_model->code = \Yii::$app->request->post('code');
            $member_model->rememberPwd = \Yii::$app->request->post('rememberPwd');
            if ($member_model->validate()) {
                $member = Member::findOne(['username' => \Yii::$app->request->post('username')]);
                //最后登陆时间和ip
                $member->last_login_time = time();
                $member->last_login_ip = ip2long(\Yii::$app->request->userIP);
                $member->save(false);
                return ['status' => 1, 'msg' => '登录成功', 'data' => $member];
            }else{
                return ['status'=>'-1','msg'=>$member_model->getErrors()];
            }
        }else{
            return ['status'=>'-1','msg'=>'请求方式错误'];
        }
    }
    //修改密码
    public function actionUpdatePwd(){
        if(\Yii::$app->request->isPost){
            //判断旧密码是否正确
            $old_pwd_model = Member::findOne(['id'=>\Yii::$app->user->id]);
            //判断新密码和旧密码是否一致
            if(\Yii::$app->security->validatePassword(\Yii::$app->request->post('old_password'),$old_pwd_model->password_hash)){
                if(\Yii::$app->request->post('password') === \Yii::$app->request->post('repassword')){
                    //一致就修改
                    $old_pwd_model->password_hash = \Yii::$app->security->generatePasswordHash(\Yii::$app->request->post('password'));
                    if ($old_pwd_model->save(false)){
                        return ['status'=>1,'msg'=>'修改密码成功'];
                    }else{
                        return ['status'=>0,'msg'=>'修改密码失败'];
                    }
                }else{
                    return ['status'=>'-1','msg'=>'两次输入的密码不一致'];
                }
            }else{
                return ['status'=>'-1','msg'=>'密码错误'];
            }
        }
    }
    //获取当前登录的用户信息
    public function actionGetCurrentUserInfo(){
        if($cur_member = \Yii::$app->user->identity){
            return ['status'=>1,'msg'=>'获取成功','data'=>$cur_member];
        }else{
            return ['status'=>0,'msg'=>'暂无登录信息'];
        }
    }
    //注销登录
    public function actionUserLogout(){
        if(\Yii::$app->user->logout()){
            return ['status'=>1,'msg'=>'注销成功'];
        }else{
            return ['status'=>0,'msg'=>'注销失败'];
        }
    }


    //添加地址
    public function actionAddressAdd(){
        if(\Yii::$app->request->isPost){
            $address_model = new Address();
            $request = \Yii::$app->request;
            //if($address_model->validate()){
                $address_model->consignee = $request->post('consignee');
                $address_model->province = $request->post('province');
                $address_model->city = $request->post('city');
                $address_model->area = $request->post('area');
                $address_model->detail_address = $request->post('detail_address');
                $address_model->tel = $request->post('tel');
                $address_model->is_default = $request->post('is_default');
                $address_model->member_id = \Yii::$app->user->id;
                if($address_model->AddSave()){
                    if($address_model->save(false)){
                        return ['status'=>1,'msg'=>'添加地址成功'];
                    }else{
                        return ['status'=>0,'msg'=>'添加地址失败'];
                    }
                }
            //}
        }
    }
    //修改地址
    public function actionAddressUpdate(){

    }
    //删除地址
    public function actionAddressDelete(){
        if($address_id = \Yii::$app->request->get('address_id')){
            $address = Address::findOne(['id'=>$address_id,'member_id'=>\Yii::$app->user->id]);
            if($address->delete()){
                return ['status'=>1,'msg'=>'删除地址成功'];
            }else{
                return ['status'=>0,'msg'=>'删除地址失败'];
            }
        }
    }
    //地址列表
    public function actionAddressList(){
        $addresses = Address::findAll(['member_id'=>\Yii::$app->user->id]);
        if($addresses != null){
            return ['status' => 1, 'msg' => '', 'data' => $addresses];
        }else{
            return ['status' => 0, 'msg' => '暂无地址信息'];
        }
    }


    //获取所有商品分类
    public function actionGoodsCategoryList(){
        $goods_cates = GoodsCategory::find()->all();
        if($goods_cates != null){
            return ['status' => 1, 'msg' => '', 'data' => $goods_cates];
        }else{
            return ['status' => 0, 'msg' => '暂无分类信息'];
        }
    }
    //获取某分类的所有子分类
    public function actionGetSonCates(){
        if($goods_cate_id = \Yii::$app->request->get('goods_cate_id')){
            //找到该条记录
            $goods_cate = GoodsCategory::findOne(['id'=>$goods_cate_id]);
            //查询左值大于他的且右值小于他的数据
            $son_cates = GoodsCategory::find()->where("tree={$goods_cate->tree}")->andWhere("rgt<{$goods_cate->rgt}")->andWhere("lft>{$goods_cate->lft}")->all();
            if($son_cates != null){
                return ['status' => 1, 'msg' => '', 'data' => $son_cates];
            }else{
                return ['status' => 0, 'msg' => '暂无子分类信息'];
            }
        }
    }
    //获取某分类的父分类
    public function actionGetParentCate(){
        if($goods_cate_id = \Yii::$app->request->get('goods_cate_id')){
            $goods_cate_info = GoodsCategory::findOne(['id'=>$goods_cate_id]);
            $parent_cate = GoodsCategory::findOne(['id'=>$goods_cate_info->parent_id]);
            if($parent_cate != null){
                return ['status' => 1, 'msg' => '', 'data' => $parent_cate];
            }else{
                return ['status' => 0, 'msg' => '未获取到父分类信息'];
            }
        }
    }


    //获取某分类下面的所有商品
    public function actionGetGoodsByCategory(){
        if($goods_cate_id = \Yii::$app->request->get('goods_cate_id')){
            //每页显示的条数  默认两条
            $per_page = \Yii::$app->request->get('per_page',2);
            //当前页 默认在第一页
            $page = \Yii::$app->request->get('page',1);
            $query = Goods::find();
            //每页显示的数据
            $cate = GoodsCategory::findOne(['id'=>$goods_cate_id]);
            switch ($cate->depth){
                case 2:  //三级分类
                    $goodses = $query->where('goods_category_id='.$goods_cate_id)->offset($per_page*($page-1))->limit($per_page)->all();
                    //总条数
                    $total_count = $query->count();
                    break;
                case 1:  //二级分类
                    $ids = ArrayHelper::map($cate->children,'id','id');
                    $goodses = $query->andWhere(['in','goods_category_id',$ids])->offset($per_page*($page-1))->limit($per_page)->all();
                    //总条数
                    $total_count = $query->andWhere(['in','goods_category_id',$ids])->count();
                    break;
                case 0:  //一级分类
                    $ids = ArrayHelper::map($cate->leaves()->asArray()->all(),'id','id');
                    $goodses = $query->andWhere(['in','goods_category_id',$ids])->offset($per_page*($page-1))->limit($per_page)->all();
                    //总条数
                    $total_count = $query->andWhere(['in','goods_category_id',$ids])->count();
                    break;
            }
            if($goodses != null){
                //总页数
                $total_page = ceil($total_count/$per_page);
                return ['status' => 1, 'msg' => '', 'data' => [
                    'per_page'=>$per_page,
                    'page'=>$page,
                    'total_count'=>$total_count,
                    'total_page'=>$total_page,
                    'goodses'=>$goodses
                ]];
            }else{
                return ['status' => 0, 'msg' => '该分类下暂无商品'];
            }
        }
    }
    //获取某品牌下面的所有商品
    public function actionGetGoodsByBrand(){
        if($brand_id = \Yii::$app->request->get('brand_id')){
            //每页显示的数据
            $per_page = \Yii::$app->request->get('per_page',2);
            //当前页
            $page = \Yii::$app->request->get('page',1);
            //总条数
            $total_count = Goods::find()->where('brand_id='.$brand_id)->count();
            //总页数
            $total_page = ceil($total_count/$per_page);
            //每页显示的数据
            $goodses = Goods::find()->where('brand_id='.$brand_id)->offset($per_page*($page-1))->limit($per_page)->all();
            if($goodses != null){
                return ['status' => 1, 'msg' => '', 'data' => [
                    'per_page'=>$per_page,
                    'page'=>$page,
                    'total_count'=>$total_count,
                    'total_page'=>$total_page,
                    'goodses'=>$goodses
                ]];
            }else{
                return ['status' => 0, 'msg' => '该品牌下暂无商品'];
            }
        }
    }


    //获取文章分类
    public function actionGetArticleCategory(){
        $article_cates = ArticleCategory::find()->all();
        if($article_cates != null){
            return ['status' => 1, 'msg' => '', 'data' => $article_cates];
        }else{
            return ['status' => 0, 'msg' => '暂无文章分类'];
        }
    }
    //获取某分类下面的所有文章
    public function actionGetArticleByArticleCate(){
        if($article_category_id = \Yii::$app->request->get('article_category_id')){
            $articles = Article::findAll(['article_category_id'=>$article_category_id]);
            if($articles != null){
                return ['status' => 1, 'msg' => '', 'data' => $articles];
            }else{
                return ['status' => 0, 'msg' => '该品牌下暂无商品'];
            }
        }
    }
    //获取某文章所属分类
    public function actionGetArticleCateByArticle(){
        if($article_id = \Yii::$app->request->get('article_id')){
            //获取该文章的信息
            $article = Article::findOne(['id'=>$article_id]);
            //查找分类
            $article_cate = ArticleCategory::findOne(['id'=>$article->article_category_id]);
            if($article_cate != null){
                return ['status' => 1, 'msg' => '', 'data' => $article_cate];
            }else{
                return ['status' => 0, 'msg' => '未查找到文章所属分类'];
            }
        }
    }


    //添加商品到购物车
    public function actionAddGoodsToCart(){
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
            return ['status' => 0, 'msg' => '暂无该商品'];
        }
        if(\Yii::$app->user->isGuest){
            //添加商品到cookie
            //实例化cookie
            $cookies_request = \Yii::$app->request->cookies;
            $cookie_request = $cookies_request->get('cart');
            //先判断购物车中是否存在商品，存在则反序列化读出，不存在则为空
            if($cookie_request == null){
                $cart = [];
            }else{
                $cart = unserialize($cookie_request->value);
            }
            $cookies_response = \Yii::$app->response->cookies;
            if(key_exists($goods_id,$cart)){  //如果购物车中存在该商品，数量累加
                $cart[$goods_id] += $amount;
            }else{
                //购物车中不存在则直接赋值
                $cart[$goods_id] = $amount;
            }
            $cookie_response = new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart)
            ]);
            $cookies_response->add($cookie_response);
            return ['status' => 1, 'msg' => '添加到购物车成功','data'=>$cart];
        }else{
            //添加商品到数据库
            $member_id = \Yii::$app->user->id;
            $member = Member::findOne(['id'=>$member_id]);
            if($member == null){
                return ['status' => 0, 'msg' => '该会员不存在'];
            }
            $cart_model = new Cart();
            $goods_cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            if($goods_cart){
                //修改时使用的是查询出来的该条数据的模型
                $goods_cart->amount += $amount;
                if($goods_cart->save(false)){
                    return ['status' => 1, 'msg' => '添加到购物车成功'];
                }else{
                    return ['status' => 0, 'msg' => '添加到购物车失败'];
                }
            }else{
                $cart_model->member_id = $member_id;
                $cart_model->goods_id = $goods_id;
                $cart_model->amount = $amount;
                if($cart_model->save(false)){
                    return ['status' => 1, 'msg' => '添加到购物车成功'];
                }else{
                    return ['status' => 0, 'msg' => '添加到购物车失败'];
                }
            }
        }
    }
    //修改购物车某商品数量
    public function actionUpdateGoodsAmount(){
        //接收数据
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
            return ['status' => 0, 'msg' => '暂无该商品'];
        }
        if(\Yii::$app->user->isGuest){
            //修改cookie中的内容
            $cookies_request = \Yii::$app->request->cookies;
            //获取到cookie，判断购物车中是否存在
            $cookie = $cookies_request->get('cart');
            if($cookie == null){
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
            //修改对应的数量
            $cookies_response = \Yii::$app->response->cookies;
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                //如果修改的数量为0，就删除购物车中的对应商品
                if(key_exists($goods->id,$cart)){
                    unset($cart[$goods_id]);
                    return ['status' => 1, 'msg' => '删除购物车商品成功'];
                }
            }
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart)
            ]);
            $cookies_response->add($cookie);
            return ['status' => 1, 'msg' => '修改数量成功','data'=>$cart];
        }else{
            $member_id = \Yii::$app->user->id;
            //修改购物车数据表中的数量
            $goods_cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            if($goods_cart == null){
                return ['status' => 0, 'msg' => '暂无该商品'];
            }
            if($amount){
                $goods_cart->amount = $amount;
                if($goods_cart->save(false)){
                    return ['status' => 1, 'msg' => '修改数量成功','data'=>$goods_cart];
                }else{
                    return ['status' => 0, 'msg' => '修改数量失败'];
                }
            }else{
                if($goods_cart->delete()){
                    return ['status' => 1, 'msg' => '删除购物车商品成功'];
                }else{
                    return ['status' => 0, 'msg' => '删除购物车商品失败'];
                }
            }
        }
    }
    //删除购物车某商品
    public function actionDeleteCartGoods(){
        $goods_id = \Yii::$app->request->get('goods_id');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
            return ['status' => 0, 'msg' => '暂无该商品'];
        }
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
            if(key_exists($goods_id,$cart)){
                unset($cart[$goods_id]);
                return ['status' => 1, 'msg' => '删除购物车商品成功'];
            }
        }else{
            $member_id = \Yii::$app->user->id;
            $goods_cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            if($goods_cart->delete()){
                return ['status' => 1, 'msg' => '删除购物车商品成功'];
            }else{
                return ['status' => 0, 'msg' => '删除购物车商品失败'];
            }
        }
    }
    //清空购物车
    public function actionCleanCart(){
        if(\Yii::$app->user->isGuest){
            $cookies_response = \Yii::$app->response->cookies;
            $cookies_response->remove('cart');
            /*//实例化cookie
            $cookies_request = \Yii::$app->request->cookies;
            $cookie_request = $cookies_request->get('cart');
            //先判断购物车中是否存在商品，存在则反序列化读出，不存在则为空
            if($cookie_request == null){
                $cart = [];
            }else{
                $cart = unserialize($cookie_request->value);
            }*/
            return ['status' => 1, 'msg' => '清空购物车商品成功'/*,'data'=>$cart*/];
        }else{
            $member_id = \Yii::$app->user->id;
            $goods_carts = Cart::findAll(['member_id'=>$member_id]);
            foreach ($goods_carts as $goods_cart){
                $goods_cart->delete();
            }
            return ['status' => 1, 'msg' => '清空购物车商品成功'];
        }
    }
    //获取购物车所有商品
    public function actionGetAllGoodsInCart(){
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }
        }else{
            $member_id = \Yii::$app->user->id;
            $cart = Cart::findAll(['member_id'=>$member_id]);
        }
        return ['status' => 1, 'msg' => '','data'=>$cart];
    }


    //获取支付方式
    public function actionGetPayments(){
        $payments = Order::$payments;
        return ['status' => 1, 'msg' => '','data'=>$payments];
    }
    //获取送货方式
    public function actionGetDeliveries(){
        $deliveries = Order::$deliveries;
        return ['status' => 1, 'msg' => '','data'=>$deliveries];
    }
    //提交订单
    public function actionSubmitOrder(){
        if(!\Yii::$app->user->isGuest){
            $order_model = new Order();
            $request = \Yii::$app->request;
            $order_model->member_id = \Yii::$app->user->id;
            $order_model->name = $request->post('name');
            $order_model->province = $request->post('province');
            $order_model->city = $request->post('city');
            $order_model->area = $request->post('area');
            $order_model->address = $request->post('address');
            $order_model->tel = $request->post('tel');
            $order_model->delivery_id = $request->post('delivery_id');
            $order_model->delivery_name = $request->post('delivery_name');
            $order_model->delivery_price = $request->post('delivery_price');
            $order_model->payment_id = $request->post('payment_id');
            $order_model->payment_name = $request->post('payment_name');
            $order_model->total = $request->post('total');
            $order_model->payment_id = $request->post('payment_id');
            $order_model->status = 1;
            //事务回滚
            $db = \Yii::$app->db;
            //开启事务
            $transaction = $db->beginTransaction();
            try{
                $order_model->save(false);
                //如果保存成功，添加商品信息到订单-商品表
                $order_id = $order_model->id;
                //这里的商品信息其实也就是所有购物车中对应的该用户的信息
                foreach ($request->post('goods_id')  as $goods_id){
                    $goods_cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
                    $order_goods_model = new OrderGoods();
                    $order_goods_model->order_id = $order_id;
                    $order_goods_model->goods_id = $goods_cart->goods_id;
                    $goods_info = Goods::findOne(['id'=>$goods_cart->goods_id]);
                    if($goods_cart->amount > $goods_info->stock){
                        return ['status' => 0, 'msg' => $goods_info->name.'库存不足'];
                    }else{
                        $goods_info->stock = $goods_info->stock - $goods_cart->amount;
                        $goods_info->save(false);
                    }
                    $order_goods_model->goods_name = $goods_info->name;
                    $order_goods_model->logo = $goods_info->logo;
                    $order_goods_model->price = $goods_info->shop_price;
                    $order_goods_model->amount = $goods_cart->amount;
                    $order_goods_model->total = ($goods_info->shop_price) * ($goods_cart->amount);
                    $order_goods_model->save(false);
                }
                //删除购物车中对应用户的信息
                $member_carts = Cart::findAll(['member_id'=>\Yii::$app->user->id]);
                foreach ($member_carts as $member_cart){
                    $member_cart->delete();
                }
                //提交事务
                $transaction->commit();
                return ['status' => 1, 'msg' => '提交订单成功'];
            }catch (Exception $exception){
                //回滚事务
                $transaction->rollBack();
                return ['status' => 0, 'msg' => '提交订单失败'];
            }
        }
    }
    //获取当前用户订单列表
    public function actionGetCurrentMemberOrders(){
        $member_id = \Yii::$app->user->id;
        $member_orders = Order::findAll(['member_id'=>$member_id]);
        return ['status' => 0, 'msg' => '','data'=>$member_orders];
    }
    //取消订单
    public function actionCancelOrder(){
        $order = Order::findOne(['id'=>\Yii::$app->request->get('order_id'),'member_id'=>\Yii::$app->user->id]);
        if($order->status == 1 && $order->status != 0){
            $order->status = 0;
        }else{
            return ['status' => 0, 'msg' => '订单已付款，无法取消'];
        }
        if($order->save(false)){
            return ['status' => 1, 'msg' => '取消订单成功'];
        }else{
            return ['status' => 0, 'msg' => '取消订单失败'];
        }
    }


    //上传文件
    public function actionUpload(){
        $img = UploadedFile::getInstanceByName('img');
        if($img){
            $filename = '/upload/'.uniqid().'.'.$img->extension;
            $res = $img->saveAs(\Yii::getAlias('@webroot').$filename,0);
            if($res){
                return ['status' => 1, 'msg' => '上传成功','data'=>$filename];
            }
            return ['status' => 0, 'msg' => $img->error];
        }
        return ['status' => 0, 'msg' => '没有文件上传'];
    }
    //验证码
    public function actions(){
        return [
            //验证码
            'captcha'=>[
                'class'=> 'yii\captcha\CaptchaAction',
//                'class'=>Captcha::className(),
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }
    //发送验证短信
    public function actionSendSms(){
        //获取电话
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel) && $tel != null){
            echo '手机号有误';
            exit;
        }
        //获取发送验证码时的时间
        $time = \Yii::$app->cache->get('time_tel_'.$tel);
        //当前时间-发送时间 = 发送验证码距离现在的时间
        $second = time() - $time;
        if($second < 60){  //如果发送验证码距离现在的时间小于60秒，说明是在一分钟之内发送的验证码
            return ['status' => 0,'msg'=>'请'.(60-$second).'秒后再试！'];
        }
        //发送短信
        $code = rand(100000,999999);
        //$result = \Yii::$app->sms->setTel($tel)->setParam(['code'=>$code])->send();
        $result = 1;
        if($result){
            //如果发送成功，保存验证码到cache缓存  /  redis   /  session  /  mysql
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);//第三个参数是过期时间  也就是验证码超过五分钟就无效
            \Yii::$app->cache->set('time_tel_'.$tel,time(),5*60);//发送验证码的时间
            return ['status' => 1,'msg'=>'发送成功','data'=>$code];
        }else{
            return ['status' => 0,'msg'=>'发送失败','data'=>$code];
        }
    }
}