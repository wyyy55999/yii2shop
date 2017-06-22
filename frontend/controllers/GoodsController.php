<?php
namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsAlbum;
use yii\web\Controller;

class GoodsController extends Controller
{
    public $layout = 'goods';

    //商品列表
    public function actionList($goods_cate_id){
        $goodses = Goods::find()->where('goods_category_id='.$goods_cate_id)->all();
        return $this->render('list',['goodses'=>$goodses]);
    }
    //商品详情
    public function actionDetail($goods_id){
        $goods_info = Goods::findOne(['id'=>$goods_id]);
        $goods_albums = GoodsAlbum::findAll(['goods_id'=>$goods_id]);
        return $this->render('detail',['goods_info'=>$goods_info,'goods_albums'=>$goods_albums]);
    }
}