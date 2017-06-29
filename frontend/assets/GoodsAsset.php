<?php
namespace frontend\assets;


use yii\web\AssetBundle;

class GoodsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/list.css',  //列表
        'style/goods.css',  //商品详情
        'style/cart.css',  //购物车
	    'style/fillin.css', //订单核对
        'style/success.css', //交易成功
        'style/home.css',//详情
        'style/order.css',//订单详情
        'style/common.css',
        'style/bottomnav.css',
        'style/footer.css',
        'style/jqzoom.css'
    ];
    public $js = [
//        'js/jquery-1.8.3.min.js',
        'js/header.js',
        'js/goods.js',
        'js/jqzoom-core.js',
        'js/cart1.js',  //购物车
        'js/cart2.js',//订单核对
        'js/home.js',//订单详情
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}