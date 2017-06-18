<?php

namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use Codeception\Lib\Generator\PageObject;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    //显示文章列表
    public function actionIndex(){
        //实例化
        //$articles = Article::find()->where('status > -1')->all();
        $page = new Pagination([
            'defaultPageSize'=>10,  //每页显示的条数
            'totalCount'=>Article::find()->where('status > -1')->count(),  //总条数
        ]);
        //每页显示的数据
        $articles = Article::find()->where('status > 0')->offset($page->offset)->limit($page->limit)->all();
        //返回到页面
        return $this->render('index',['articles'=>$articles,'page'=>$page]);
    }
    //新增文章及详情
    public function actionAdd(){
        //实例化
        $article = new Article();
        $request = new Request();
        //保存文章详情
        $detail = new ArticleDetail();
        //判断提交方式
        if($request->isPost){
            //加载
            $article->load($request->post());
            //判断验证结果
            if($article->validate()){
                $detail->load($request->post());
                if($detail->validate()){
                    //成功就保存
                    $article->create_time = time();
                    $article->save();
                    $detail->save();
                    \Yii::$app->session->setFlash('success','添加成功');
                    return $this->redirect(['article/index']);
                }
            }else{
                //失败就打印
                var_dump($article->getErrors());
                var_dump($detail->getErrors());
                exit;
            }
        }
        //get提交显示文章添加页面
        $article->status = 1;
        //获取所有分类
        $cates  = ArticleCategory::find()->all();
        //将结果拆分成数组
        $article_cates = ArrayHelper::map($cates,'id','name');
        return $this->render('option',['article'=>$article,'article_cates'=>$article_cates,'detail'=>$detail]);
    }
    //修改文章及详情
    public function actionUpdate($id){
        //实例化
        $article = Article::findOne(['id'=>$id]);
        $request = new Request();
        //保存文章详情
        $detail = ArticleDetail::findOne(['article_id'=>$id]);
        //判断提交方式
        if($request->isPost){
            //加载
            $article->load($request->post());
            //判断验证结果
            if($article->validate()){
                $detail->load($request->post());
                if($detail->validate()){
                    //成功就保存
                    $article->save();
                    $detail->save();
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['article/index']);
                }
            }else{
                //失败就打印
                var_dump($article->getErrors());
                var_dump($detail->getErrors());
                exit;
            }
        }
        //get提交显示文章添加页面
        //获取所有分类
        $cates  = ArticleCategory::find()->all();
        //将结果拆分成数组
        $article_cates = ArrayHelper::map($cates,'id','name');
        return $this->render('option',['article'=>$article,'article_cates'=>$article_cates,'detail'=>$detail]);
    }
    //删除文章及详情
    public function actionDelete($id){
        //实例化
        $article = Article::findOne(['id'=>$id]);
        $detail = ArticleDetail::findOne(['article_id'=>$id]);
        //删除
        $article->delete();
        $detail->delete();
        //提示
        \Yii::$app->session->setFlash('danger','删除成功');
        //返回跳转
        return $this->redirect(['article/index']);
    }
    //查看文章详情
    public function actionDetail($id){
        $detail = ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('detail',['detail'=>$detail]);
    }
}