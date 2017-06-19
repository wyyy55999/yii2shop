<?php

namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleCategory;
use yii\web\Controller;
use yii\web\Request;

class ArticleCategoryController extends PublicController
{
    //文章分类列表
    public function actionIndex(){
        //实例化
        $cates = ArticleCategory::find()->all();
        //渲染到页面
        return $this->render('index',['cates'=>$cates]);
    }
    //新增文章分类
    public function actionAdd(){
        //实例化
        $cates = new ArticleCategory();
        $request = new Request();
        //判断是否为post提交
        if($request->isPost){
            //加载post数据
            $cates->load($request->post());
            //验证post数据
            if($cates->validate()){
                //验证成功就保存
                $cates->save();
                //提示
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article-category/index']);
            }else{
                //失败就打印错误信息
                var_dump($cates->getErrors());
                exit;
            }
        }else{
            //不是则显示新增页面
            $cates->is_help = 1;
            $cates->status = 1;
            return $this->render('option',['cates'=>$cates]);
        }
    }
    //修改文章分类
    public function actionUpdate($id){
        //实例化
        $cate = ArticleCategory::findOne(['id'=>$id]);
        $request = new Request();
        //判断是否为post提交
        if($request->isPost){
            //加载post数据
            $cate->load($request->post());
            //验证post数据
            if($cate->validate()){
                //验证成功就保存
                $cate->save();
                //提示
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article-category/index']);
            }else{
                //失败就打印错误信息
                var_dump($cate->getErrors());
                exit;
            }
        }else{
            //不是则显示操作页面
            return $this->render('option',['cates'=>$cate]);
        }
    }
    //删除文章分类
    public function actionDelete($id){
        //实例化
        $cate = ArticleCategory::findOne(['id'=>$id]);
        $article = Article::findAll(['article_category_id'=>$id]);
        if($article==null){
            //删除
            $cate->delete();
            //提示
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('danger','该分类下还有文章，无法删除！');
        }
        //跳转
        return $this->redirect(['article-category/index']);
    }
}