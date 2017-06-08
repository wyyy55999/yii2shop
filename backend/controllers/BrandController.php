<?php

namespace backend\controllers;


use backend\models\Brand;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller
{
    //品牌列表
    public function actionIndex(){
        //实例化全部品牌
        $brands = Brand::find()->all();
        //显示到页面
        return $this->render('index',['brands'=>$brands]);
    }
    //新增品牌
    public function actionAdd(){
        //实例化
        $brand = new Brand();
        $brand->scenario = Brand::SCENARIO_ADD;
        $request = new Request();
        //判断是否为post提交
        if($request->isPost){
            //加载数据
            $brand->load($request->post());
            //在验证前实例化上传对象
            $brand->logoFile = UploadedFile::getInstance($brand,'logoFile');
            //验证数据
            if($brand->validate()){
                //判断是否有文件上传
                if($brand->logoFile){
                    //有就给文件设置路径
                    $fileName = '/images/brands/'.uniqid().'.'.$brand->logoFile->extension;
                    $brand->logoFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $brand->logo = $fileName;
                    //保存添加
                }
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
        $brand->scenario = Brand::SCENARIO_UPDATE;
        $request = new Request();
        //判断是否为post提交
        if($request->isPost){
            //加载数据
            $brand->load($request->post());
            //在验证前实例化上传对象
            $brand->logoFile = UploadedFile::getInstance($brand,'logoFile');
            //验证数据
            if($brand->validate()){
                //判断是否有文件上传
                if($brand->logoFile){
                    //有就给文件设置路径
                    $fileName = '/images/brands/'.uniqid().'.'.$brand->logoFile->extension;
                    $brand->logoFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $brand->logo = $fileName;
                }
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
    //删除品牌
    public function actionDelete($id){
        //实例化
        $brand = Brand::findOne(['id'=>$id]);
        //删除  delete
        $brand->delete();
        //设置提示信息
        \Yii::$app->session->setFlash('success','删除成功');
        //跳转
        return $this->redirect(['brand/index']);
    }
}