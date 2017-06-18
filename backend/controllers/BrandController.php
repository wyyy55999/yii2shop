<?php

namespace backend\controllers;


use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;   //七牛云


class BrandController extends Controller
{
    //品牌列表
    public function actionIndex(){
        //实例化分页工具条
        $page = new Pagination([
            'totalCount'=>Brand::find()->where('status > -1')->count(),  //总条数
            'defaultPageSize'=>2,//每页显示条数
        ]);
        //实例化全部品牌
        $brands = Brand::find()->where('status > -1')->offset($page->offset)->limit($page->limit)->all();
        //显示到页面
        return $this->render('index',['brands'=>$brands,'page'=>$page]);
    }
    //新增品牌
    public function actionAdd(){
        //实例化
        $brand = new Brand();
        //$brand->scenario = Brand::SCENARIO_ADD;
        $request = new Request();
        //判断是否为post提交
        if($request->isPost){
            //加载数据
            $brand->load($request->post());
            //在验证前实例化上传对象
            //$brand->logoFile = UploadedFile::getInstance($brand,'logoFile');
            //验证数据
            if($brand->validate()){
                /*//判断是否有文件上传
                if($brand->logoFile){
                    //有就给文件设置路径
                    $fileName = '/images/brands/'.uniqid().'.'.$brand->logoFile->extension;
                    $brand->logoFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $brand->logo = $fileName;
                    //保存添加
                }*/
                //不管有没有图片 都应该保存
                $brand->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加成功');
                //跳转
                return $this->redirect(['brand/index']);
            }else{
                var_dump($brand->getErrors());
                exit;
            }
        //不是post提交就显示新增页面
        }else{
            $brand->status = 1;
            return $this->render('add',['brand'=>$brand]);
        }
    }
    //修改品牌
    public function actionUpdate($id){
        //实例化
        $brand = Brand::findOne(['id'=>$id]);
        //$brand->scenario = Brand::SCENARIO_UPDATE;
        $request = new Request();
        //判断是否为post提交
        if($request->isPost){
            //加载数据
            $brand->load($request->post());
            //在验证前实例化上传对象
            //$brand->logoFile = UploadedFile::getInstance($brand,'logoFile');
            //验证数据
            if($brand->validate()){
                //判断是否有文件上传
                /*if($brand->logoFile){
                    //有就给文件设置路径
                    $fileName = '/images/brands/'.uniqid().'.'.$brand->logoFile->extension;
                    $brand->logoFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $brand->logo = $fileName;
                }*/
                //不管有没有图片 都应该保存
                $brand->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success','修改成功');
                //跳转
                return $this->redirect(['brand/index']);
            }else{
                var_dump($brand->getErrors());
                exit;
            }
            //不是post提交就显示新增页面
        }else{
            return $this->render('add',['brand'=>$brand]);
        }
    }
    //物理删除品牌
    /*public function actionDelete($id){
        //实例化
        $brand = Brand::findOne(['id'=>$id]);
        //删除  delete
        $brand->delete();
        //设置提示信息
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转
        return $this->redirect(['brand/index']);
    }*/
    //逻辑删除
    public function actionDel($id){
        //实例化
        $brand = Brand::findOne(['id'=>$id]);
        //修改状态为-1
        $brand->status = -1;
        //保存
        $brand->save();
        //设置提示信息
        \Yii::$app->session->setFlash('danger','删除成功');
        //跳转
        return $this->redirect(['brand/index']);
    }
    //操作
    public function actions() {
        return [
            //上传文件插件
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default   ==>跨站攻击验证
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
                    return "brands/{$p1}/{$p2}/{$filehash}.{$fileext}";
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
                    //图片路径
                    $imgUrl = $action->getWebUrl();
                    //$action->getSavePath();
                    //$action->output['fileUrl'] = $action->getWebUrl();
                    //调用七牛云组件，将图片上传到七牛云
                    $qiniu = \Yii::$app->qiniu;
                    //$qiniu->uploadFile($action->getSavePath(),$imgUrl);
                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    //获取图片在七牛云的地址
                    $url = $qiniu->getLink($imgUrl);
                    //将前端的地址改成在七牛云的地址
                    $action->output['fileUrl'] = $url;
                    //$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    //$action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    //$action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
    //七牛云测试
    public function actionTest(){
        $ak = '1d0e88-5TukIYJX9Fvv25WEUgpfnBuSbBdFSuSxD';
        $sk = 'aJCuSrHWxNM7mANw1FEQpH3XwbX1a9D0d6ShPXia';
        $domain = 'http://or9rs3wp9.bkt.clouddn.com/';
        $bucket = 'dlm1996';
        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        //指定要上传的文件
        $filename = \Yii::getAlias('@webroot'.'/upload/test.jpg');
        $key = 'test.jpg';
        //上传文件
        $qiniu->uploadFile($filename,$key);
        //获取文件路径
        $url = $qiniu->getLink($key);
    }
    public function actionTestTest(){
        //图片路径
        $imgUrl = '/upload/test.jpg';
        //$action->output['fileUrl'] = $action->getWebUrl();
        //调用七牛云组件，将图片上传到七牛云
        $qiniu = \Yii::$app->qiniu;
        // var_dump($imgUrl);exit;
        $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
        //获取图片在七牛云的地址
        $url = $qiniu->getLink($imgUrl);
        var_dump($url);exit;
        //将前端的地址改成在七牛云的地址
        //$action->output['fileUrl'] = $url;
    }

}