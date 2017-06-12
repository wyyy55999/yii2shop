<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    //列表页
    public function actionIndex()
    {
        $goods_cates = GoodsCategory::find()->orderBy('tree,lft')->all();
        return $this->render('index',['goods_cates'=>$goods_cates]);
    }
    //添加商品分类
    public function actionAdd(){
        $goods_cates = new GoodsCategory();
        //验证数据
        if($goods_cates->load(\Yii::$app->request->post()) && $goods_cates->validate()){
            //判断传过来的父id是否为0   不为0表示不是顶级分类
            if($goods_cates->parent_id){
                //不是顶级分类就找到id等于传过来的父id的数据，说明是想在它下面添加子分类
                $parent = GoodsCategory::findOne(['id'=>$goods_cates->parent_id]);
                //将传过来的数据加入到父分类下
                $goods_cates->prependTo($parent);
            }else{
                //将传过来的数据作为顶级分类添加
                $goods_cates->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods-category/index']);
        }
        //获取所有分类选项  GoodsCategory::find()->asArray()->all()
        //设置顶级分类选项  [['id'=>0,'name'=>'顶级分类','parent_id'=>0]]  两个中括号是因为分类选项是一个二级分类  如果不用两个，那么顶级分类会将所有的都括起来  'parent_id'=>0可以不用这个  不影响结果
        //ArrayHelper::merge  合并两个数组
        $options = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
//        $options = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类']],GoodsCategory::find()->asArray()->all());
        return $this->render('option',['goods_cates'=>$goods_cates,'options'=>$options]);
    }
    //修改商品分类
    public function actionUpdate($id){
        $goods_cates = GoodsCategory::findOne(['id'=>$id]);
        //如果查询结果为空，说明分类不存在，抛出一个异常
        if($goods_cates == null){
            throw new NotFoundHttpException('分类不存在');
        }
        //验证数据
        if($goods_cates->load(\Yii::$app->request->post()) && $goods_cates->validate()){
            //判断传过来的父id是否为0   不为0表示不是顶级分类
            if($goods_cates->parent_id){
                //不是顶级分类就找到id等于传过来的父id的数据，说明是想在它下面添加子分类
                $parent = GoodsCategory::findOne(['id'=>$goods_cates->parent_id]);
                //将传过来的数据加入到父分类下
                $goods_cates->prependTo($parent);
            }else{
                //判断修改前是否为顶级分类
                if($goods_cates->getOldAttribute('parent_id') == 0){  //修改前就是顶级分类
                    //如果是 那么就不用再创建顶级分类 直接保存即可
                    $goods_cates->save();
                }else{  //修改前不是顶级分类
                    //将传过来的数据作为顶级分类添加
                    $goods_cates->makeRoot();
                }
            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['goods-category/index']);
        }
        //获取所有分类选项  GoodsCategory::find()->asArray()->all()
        //设置顶级分类选项  [['id'=>0,'name'=>'顶级分类','parent_id'=>0]]  两个中括号是因为分类选项是一个二级分类  如果不用两个，那么顶级分类会将所有的都括起来  'parent_id'=>0可以不用这个  不影响结果
        //ArrayHelper::merge  合并两个数组
        $options = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        //$options = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类']],GoodsCategory::find()->asArray()->all());
        return $this->render('option',['goods_cates'=>$goods_cates,'options'=>$options]);
    }
    //测试
    public function actionTest(){
        $jydq = new GoodsCategory();
        $jydq->name = '家用电器';
        $jydq->parent_id = 0;
        $jydq->makeRoot();
        //获取小家电的父级分类信息
        $parent = GoodsCategory::findOne(['id'=>1]);
        $xjd = new GoodsCategory();
        $xjd->name = '小家电';
        $xjd->parent_id = $parent->id;
        $xjd->prependTo($parent);
        echo '操作成功';
        $parent = GoodsCategory::findOne(['id'=>1]);
        //家用电器的所有没有分类的子孙（叶子节点）
        var_dump($parent->leaves()->all());
    }
    //测试ztree
    public function actionZtree()
    {
        $cates = GoodsCategory::find()->asArray()->all();
        return $this->renderPartial('ztree',['cates'=>$cates]);
    }
}
