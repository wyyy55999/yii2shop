<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsAlbum;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use xj\uploadify\UploadAction;

class GoodsController extends Controller
{
    //使用过滤器
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'only'=>['index','add','update','delete'],
            ]
        ];
    }
    //商品列表
    public function actionIndex()
    {
        //实例化
        $goods = new Goods();
        $request = new Request();
        //分页对象
        $page = new Pagination([
            'totalCount'=>Goods::find()->where("status >0")->count(),
            'defaultPageSize'=>2,
        ]);
        //使用场景
        $goods->scenario = Goods::SCENARIO_SEARCH;
        if(isset($request->get()['Goods'])){
            //定义数组保存条件
            $condition = [];
            if ($request->get()['Goods']['name'] != null) {
                $condition[] = "name like '%{$request->get()['Goods']['name']}%'";

            }
            if ($request->get()['Goods']['sn'] != null) {
                $condition[] = "sn like '%{$request->get()['Goods']['sn']}%'";
            }
            if($request->get()['Goods']['min_price'] && $request->get()['Goods']['max_price']){
                $condition[] = "shop_price <= {$request->get()['Goods']['max_price']} and shop_price >= {$request->get()['Goods']['min_price']}";
            }
//            if ($request->get()['Goods']['goods_category_id'] != null) {
//                $condition[] = "goods_category_id={$request->get()['Goods']['goods_category_id']}";
//            }
            if ($request->get()['Goods']['brand_id'] != null) {
                $condition[] = "brand_id={$request->get()['Goods']['brand_id']}";
            }
            if($condition){   //搜索条件不为空
                //拆分数组
                $conditions = implode(' and ', $condition);
                //实例化分页对象
                $page = new Pagination([
                    'totalCount'=>Goods::find()->where("status >0 and {$conditions}")->count(),
                    'defaultPageSize'=>2,
                ]);
                //如果有条件
                $goodses = Goods::find()->where("status >0 and {$conditions}")->offset($page->offset)->limit($page->limit)->all();
                //设置输入框回显搜索要求
                $goods->name = $_GET['Goods']['name'];
                $goods->sn = $_GET['Goods']['sn'];
                $goods->min_price = $request->get()['Goods']['min_price'];
                $goods->max_price = $request->get()['Goods']['max_price'];
                //设置商品分类选中项
                //$goods->goods_category_id = $_GET['Goods']['goods_category_id'];
                //设置品牌选中项
                $goods->brand_id = $_GET['Goods']['brand_id'];
            }else{   //搜索条件为空  也就是按下了搜索键  但是并没有搜索任何内容
                $goodses = Goods::find()->where('status > 0')->offset($page->offset)->limit($page->limit)->all();
            }
        }else{
            //没有条件  也就是直接输入index
            $goodses = Goods::find()->where('status > 0')->offset($page->offset)->limit($page->limit)->all();
        }
        //品牌
        $brands = Brand::find()->all();
        $brand = ArrayHelper::map($brands,'id','name');
//        //分类
//        $cates = GoodsCategory::find()->all();
//        $goods_cates = ArrayHelper::map($cates,'id','name');
//        /*'goods_cates'=>$goods_cates*/
        //所有商品
        return $this->render('index',['goodses'=>$goodses,'goods'=>$goods,'brands'=>$brand,'page'=>$page]);
    }
    //添加商品
    public function actionAdd(){
        $goods = new Goods();
        $request = new Request();
        $goods_intro = new GoodsIntro();
        $count = 0;  //定义一个总数  保存每天新增的商品数量
        //获取当前的年月日
        $now_date = date('Ymd',time());
        //获取当前天数的count
        $goods_day = GoodsDayCount::findOne(['day'=>date('Y-m-d',time())]);
        if($goods_day == null){
            $goods_day = new GoodsDayCount();
            $count = 1;
        }else{
            //如果有值，说明存在商品，那么商品总数就+1
            $count = $goods_day->count + 1;
        }
        if($request->isPost){
            if($goods->load($request->post()) && $goods->validate() && $goods_intro->load($request->post()) && $goods_intro->validate()){
                $goods_day->count = $count;//商品的总数
                $goods_day->day = date('Y-m-d',time());//现在的天数
                //生成货号  sprintf() 补0函数
                $goods_sn = $now_date.sprintf("%05d",$count);
                //商品货号
                $goods->sn = $goods_sn;
                //给商品当天的总数+1
                $goods_day->count = $count;
                $goods->save(false);
                $goods_day->save(false);
                $goods_intro->goods_id = $goods->id;  //将上一条插入的记录赋值给商品详情表的goods_id
                $goods_intro->save(false);
            }else{
                var_dump($goods->getErrors());
                var_dump($goods_day->getErrors());
                var_dump($goods_intro->getErrors());
                exit;
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods/index']);
        }else{
            //获取全部品牌分类  然后在添加页面展示
            $brands = Brand::find()->all();
            $brands = ArrayHelper::map($brands,'id','name');
            $goods->is_on_sale = 1;
            $goods->status = 1;
            //获取商品分类
            $goods_cates = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
            return $this->render('option',['goods'=>$goods,'brands'=>$brands,'goods_cates'=>$goods_cates,'goods_intro'=>$goods_intro]);
        }
    }
    //修改商品
    public function actionUpdate($id){
        $goods = Goods::findOne(['id'=>$id]);
        $request = new Request();
        $goods_intro = GoodsIntro::findOne(['goods_id'=>$id]);
        if($request->isPost){
            if($goods->load($request->post()) && $goods->validate() && $goods_intro->load($request->post()) && $goods_intro->validate()){
                $goods->save(false);
                $goods_intro->goods_id = $goods->id;  //将上一条插入的记录赋值给商品详情表的goods_id
                $goods_intro->save(false);
            }else{
                var_dump($goods->getErrors());
                var_dump($goods_intro->getErrors());
                exit;
            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['goods/index']);
        }else{
            //获取全部品牌分类  然后在添加页面展示
            $brands = Brand::find()->all();
            $brands = ArrayHelper::map($brands,'id','name');
            //获取商品分类
            $goods_cates = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
            return $this->render('option',['goods'=>$goods,'brands'=>$brands,'goods_cates'=>$goods_cates,'goods_intro'=>$goods_intro]);
        }
    }
    //删除商品  物理删除
    public function actionDelete($id){
        $goods = Goods::findOne(['id'=>$id]);
        $goods->status = 0;
        $goods->save();
        \Yii::$app->session->setFlash('danger','删除成功');
        return $this->redirect(['goods/index']);
    }
    //测试
    public function actionTest(){
        $goods = new Goods();
        $brands = Brand::find()->all();
        $brands = ArrayHelper::map($brands,'id','name');
        $goods->is_on_sale = 1;
        $goods->status = 1;
        //获取商品分类
        $goods_cates = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('option',['goods'=>$goods,'brands'=>$brands,'goods_cates'=>$goods_cates]);
    }
    //上传图片 uploadify插件  文本编辑器  ueditor插件
    public function actions() {
        return [
            //uploadify插件
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "goods/{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif','jpeg'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
            //ueditor插件
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    //搜索
    public function actionSearch(){
        $request = new Request();
        $condition = [];
        if($request->isGet){
            if($_GET['Goods']['name']){
                $condition[] = "name like '%{$_GET['Goods']['name']}%'";
            }
            if($_GET['Goods']['sn']){
                $condition[] = "sn like '%{$_GET['Goods']['sn']}%'";
            }
            if($_GET['Goods']['goods_category_id']){
                $condition[] = "goods_category_id={$_GET['Goods']['goods_category_id']}";
            }
            if($_GET['Goods']['brand_id']){
                $condition[] = "brand_id={$_GET['Goods']['brand_id']}";
            }
            $conditions = implode(' and ',$condition);

            $goodses = Goods::find()->where(" {$conditions}")->all();
            $goods = new Goods();
            //品牌
            $brands = Brand::find()->all();
            $brand = ArrayHelper::map($brands,'id','name');
            //分类
            $cates = GoodsCategory::find()->all();
            $goods_cates = ArrayHelper::map($cates,'id','name');
            //使用场景
            $goods->scenario = Goods::SCENARIO_SEARCH;
            return $this->render('index',['goodses'=>$goodses,'goods'=>$goods,'brands'=>$brand,'goods_cates'=>$goods_cates]);
        }
    }
}
