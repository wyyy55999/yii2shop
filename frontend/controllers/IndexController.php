<?php

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\components\SphinxClient;
use frontend\models\SearchForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class IndexController extends Controller
{
    public $layout = 'index';
    //显示
    public function actionIndex(){
        $goods_cates = GoodsCategory::find()->where('parent_id = 0')->all();
        return $this->render('index',['goods_cates'=>$goods_cates]);
    }
    //测试sphinx
    public function actionTestSphinx(){
        $cl = new SphinxClient();
        $cl->SetServer ( '127.0.0.1', 9312);
        //$cl->SetServer ( '10.6.0.6', 9312);
        //$cl->SetServer ( '10.6.0.22', 9312);
        //$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout ( 10 );
        $cl->SetArrayResult ( true );
        $cl->SetMatchMode ( SPH_MATCH_ALL);  //==>匹配模式
        //$cl->SetMatchMode ( SPH_MATCH_EXTENDED2);
        $cl->SetLimits(0, 1000);
        $info = '华为手机';
        $res = $cl->Query($info, 'mysql');//shopstore_search
        //print_r($cl);
        var_dump($res);
    }
    //中文分词搜索
    public function actionSearch(){
        $query = Goods::find();
        if($keywords = \Yii::$app->request->get('keywords')){
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
            $cl->SetMatchMode ( SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
            $res = $cl->Query($keywords, 'mysql');//shopstore_search
            if(!isset($res['matches'])){  //如果没有匹配的商品
                echo Json::encode($query->where(['id'=>0]));
            }else{  //如果有匹配的商品
                //获取商品id
                $ids = ArrayHelper::map($res['matches'],'id','id');
                $result = $query->where(['in','id',$ids]);
                echo Json::encode($result->where[2]);
                //var_dump($query->where(['in','id',$ids]));
                //echo Json::encode($query->where(['in','id',$ids]));  //查找id在ids数组中的商品
            }
        }
    }
}