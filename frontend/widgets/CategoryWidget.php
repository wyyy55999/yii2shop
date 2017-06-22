<?php
namespace frontend\widgets;

use backend\models\GoodsCategory;

class CategoryWidget extends \yii\base\Widget
{
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        //实例化redis
        $redis = new \Redis();
        //连接redis
        $redis->connect('127.0.0.1');
        //获取redis中的分类信息
        $category_html = $redis->get('category_html');
        //判断是否存在分类信息  如果不存在  也就是为空  那么就创建redis
        if($category_html == null){
            $goods_cates = GoodsCategory::findAll(['parent_id'=>0]);
            $category_html = $this->renderFile('@app/widgets/views/category.php',['goods_cates'=>$goods_cates]);
            //设置redis
            $redis->set('category_html',$category_html);
        }
        $redis->expire('category_html',10);
        return $category_html;
    }
}