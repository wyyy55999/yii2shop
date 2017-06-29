<?php
namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsAlbum;
use backend\models\GoodsCategory;
use frontend\components\SphinxClient;
use frontend\models\Cart;
use frontend\models\Member;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class GoodsController extends Controller
{
    public $layout = 'goods';
    //商品列表和分类
    public function actionList($goods_cate_id = null){
        if($goods_cate_id != null){
            $all_cates = [];  //所有的子孙分类
            $goodses = []; //所有商品
            //先查找自己的商品
            $goodses[] = Goods::find()->where('goods_category_id='.$goods_cate_id)->all();
            //再查自己是否有的子孙
            $sons = GoodsCategory::findAll(['parent_id'=>$goods_cate_id]);
            if($sons){  //有儿子就找孙子
                $all_cates[] = $sons;
                foreach ($sons as $son){
                    $grandSon = GoodsCategory::findAll(['parent_id'=>$son->id]);
                    if($grandSon){
                        $all_cates[] = $grandSon;
                    }
                }
            }
            if($all_cates){  //如果存在子孙分类
                foreach ($all_cates as $cates){
                    if(is_array($cates)){
                        foreach ($cates as $cate){
                            $goodses[] = Goods::find()->where('goods_category_id='.$cate->id)->all();
                        }
                    }
                }
            }
        }else {
            //sphinx中文分词搜索
            if ($keywords = \Yii::$app->request->get('keywords')) {
                $query = Goods::find();
                $cl = new SphinxClient();
                $cl->SetServer('127.0.0.1', 9312);
                $cl->SetConnectTimeout(10);
                $cl->SetArrayResult(true);
                $cl->SetMatchMode(SPH_MATCH_ALL);
                $cl->SetLimits(0, 1000);
                $res = $cl->Query($keywords, 'mysql');//shopstore_search
                $goodses = [];
                if (!isset($res['matches'])) {  //如果没有匹配的商品
                    $goodses[] = $query->where(['id' => 0])->all();
                } else {  //如果有匹配的商品
                    //获取商品id
                    $ids = ArrayHelper::map($res['matches'], 'id', 'id');
                    $goodses[] = $query->where(['in', 'id', $ids])->all();
                }

                /*$pager = new Pagination([
                    'totalCount'=>$query->count(),
                    'pageSize'=>5
                ]);
                $goodses[] = $query->limit($pager->limit)->offset($pager->offset)->all();*/
                $keywords = array_keys($res['words']);
                $options = [
                    'before_match' => '<span style="color:red;">',
                    'after_match' => '</span>',
                    'chunk_separator' => '...',
                    'limit' => 80, //如果内容超过80个字符，就使用...隐藏多余的的内容
                ];
                //关键字高亮
                foreach($goodses[0] as $index=>$item) {
                    //使用的索引不能写*，关键字可以使用空格、逗号等符号做分隔，放心，sphinx很智能，会给你拆分的
                    $name = $cl->BuildExcerpts([$item->name], 'mysql', implode(',', $keywords), $options);
                    $goodses[0][$index]->name = $name[0];
                }
            }
        }
        $goods_cates = GoodsCategory::findAll(['parent_id'=>0]);  //一级分类
        return $this->render('list',['goodses'=>$goodses,'goods_cates'=>$goods_cates]);
    }
    //商品详情
    public function actionDetail($goods_id){
        $goods_info = Goods::findOne(['id'=>$goods_id]);
        $goods_albums = GoodsAlbum::findAll(['goods_id'=>$goods_id]);
        return $this->render('detail',['goods_info'=>$goods_info,'goods_albums'=>$goods_albums]);
    }
    //添加到购物车
    public function actionAddCart(){
        $this->layout = 'cart';//设置布局文件
        //接收数据
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        //判断是否存在该数据
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
            throw new NotFoundHttpException('该商品不存在');
        }
        //验证是否已登录  如果未登录 存入cookie
        if(\Yii::$app->user->isGuest){
            //实例化cookie
            $cookies = \Yii::$app->request->cookies;
            //获取cookie中购物车的内容  判断是否为空  不为空说明有值  为空说明没值
            $cookie = $cookies->get('cart');
            if($cookie == null){ //cookie中没有购物车数据  说明购物车原本没有东西  直接赋值
                $cart = [];
            }else{  //如果有  就反序列化读出
                $cart = unserialize($cookie->value);
            }
            $cookies = \Yii::$app->response->cookies;
            //可以使用goods_id=>amount的数组形式  或者使用二维数组
            //$cart = [$goods_id=>$amount];
            //判断之前是否有该商品  有的话就累加  没有就赋值
            if(key_exists($goods_id,$cart)){
                $cart[$goods_id] += $amount;
            }else{
                $cart[$goods_id] = $amount;
            }
            $cookie = new Cookie([
                'name'=>'cart',
                //value需要goods_id和amount  因为必须是字符串 所以序列化存入
                'value'=>serialize($cart),
            ]);
            //存入cookie
            $cookies->add($cookie);
        }else{
            //如果已登录  存入数据库
            //获取当前登录的用户id
            $cur_member_id = \Yii::$app->user->id;
            $cur_member = Member::findOne(['id'=>$cur_member_id]);
            if($cur_member == null){
                throw new NotFoundHttpException('该用户不存在');
            }
            //将对应数据存入数据库
            $cart = new Cart();
            //判断数据库中是否已经存在该member_id和goods_id的对应关系，存在即累加   不存在即赋值
            $member_goods = Cart::findOne(['member_id'=>$cur_member_id,'goods_id'=>$goods_id]);
            if($member_goods){
                //这里要使用$member_goods  不能用$cart  否则会被认为是新增而不是修改
                $member_goods->amount +=$amount;
                //存入数据库
                $member_goods->save(false);
            }else{
                $cart->goods_id = $goods_id;
                $cart->member_id = $cur_member_id;
                $cart->amount = $amount;
                //存入数据库
                $cart->save(false);
            }
        }
        return $this->redirect(['goods/cart']);
    }
    //显示商品到购物车页面
    public function actionCart(){
        $this->layout = 'cart';//设置布局文件
        //如果是游客登录
        if(\Yii::$app->user->isGuest){
            //获取cookie
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            //判断购物车中是否有商品
            if($cookie == null){
                $cart = [];
            }else{ //$cart是goods_id=>amount的数组
                $cart = unserialize($cookie->value);
            }
            $goods_models = [];//存放商品
            foreach ($cart as $goods_id=>$amount){
                $goods = Goods::findOne(['id'=>$goods_id])->attributes;
                $goods['amount'] = $amount;   //将amount加入到goods中  而不是$goods_models中
                $goods_models[] = $goods;
            }
        }else{
            //非游客登录
            $cur_member_id = \Yii::$app->user->id;
            $cart_member = Cart::findAll(['member_id'=>$cur_member_id]);
            if($cart_member == null){
                $goods_models = [];
                return $this->render('cart',['goods_models'=>$goods_models]);
            }
            $goods_models = [];
            foreach ($cart_member as $goods_info){
                $goods = Goods::findOne(['id'=>$goods_info->goods_id])->attributes;
                $goods['amount'] = $goods_info->amount;
                $goods_models[] = $goods;
            }
        }
        return $this->render('cart',['goods_models'=>$goods_models]);
    }
    //修改购物车商品数量
    public function actionUpdateAmount(){
        //获取ajax提交的数据
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        $goods = Goods::findOne(['id'=>$goods_id]);
        if($goods == null){
            throw new NotFoundHttpException('该商品不存在');
        }
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if($cookie == null){
                $cart = [];
            }else{
                $cart = unserialize($cookie->value);
            }

            $cookies = \Yii::$app->response->cookies;
            //如果存在amount  那么就将数量改为amount
            if($amount){
                $cart[$goods_id] = $amount;
            }else{
                //如果不存在  amount = 0
                if(key_exists($goods['id'],$cart)){
                    unset($cart[$goods_id]);
                }
            }
            //$cart = [$goods_id=>$amount];
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($cart),
            ]);
            $cookies->add($cookie);
        }else{
            //登录
            $cur_member_id = \Yii::$app->user->id;
            if(Member::findOne(['id'=>$cur_member_id]) == null){
                throw new NotFoundHttpException('该用户不存在');
            }
            $member_goods = Cart::findOne(['member_id'=>$cur_member_id,'goods_id'=>$goods_id]);
            if($amount){  //如果$amount不等于0  说明有值
                $member_goods->amount = $amount;
                $member_goods->save(false);
            }else{  //说明没有值  也就是删除
                $member_goods->delete();
            }
        }
    }
}