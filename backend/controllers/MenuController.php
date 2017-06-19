<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class MenuController extends \yii\web\Controller
{
    //菜单列表
    public function actionIndex()
    {
        $menus = Menu::find()->all();
        return $this->render('index',['menus'=>$menus]);
    }
    //新增菜单
    public function actionAdd(){
        $menu = new Menu();
        if(\Yii::$app->request->isPost){
            if($menu->load(\Yii::$app->request->post()) && $menu->validate()){
                $menu->save(false);
                \Yii::$app->session->setFlash('success','添加菜单成功');
                return $this->redirect(['menu/index']);
            }
        }
        $parent_menus = ArrayHelper::merge([0=>'一级菜单'],ArrayHelper::map(Menu::findAll(['parent_id'=>0]),'id','label'));
        return $this->render('option',['menu'=>$menu,'parent_menus'=>$parent_menus]);
    }
    //修改菜单
    public function actionUpdate($id){
        $menu = Menu::findOne(['id'=>$id]);
        if($menu == null){
            throw new NotFoundHttpException('该菜单不存在');
        }
        if(\Yii::$app->request->isPost){
            if($menu->load(\Yii::$app->request->post()) && $menu->validate()){
//                var_dump(\Yii::$app->request->post());exit;
                if($menu->checkParent($id)){   //不能移动到自己的分类下
                    $menu->save(false);
                    \Yii::$app->session->setFlash('warning','修改菜单成功');
                    return $this->redirect(['menu/index']);
                }
            }
        }
        $parent_menus = ArrayHelper::merge([0=>'一级菜单'],ArrayHelper::map(Menu::findAll(['parent_id'=>0]),'id','label'));
        return $this->render('option',['menu'=>$menu,'parent_menus'=>$parent_menus]);
    }
    //删除菜单
    public function actionDelete($id){
        $menu = Menu::findOne(['id'=>$id]);
        if($menu == null){
            throw new NotFoundHttpException('该菜单不存在');
        }
        //如果该菜单下有子菜单  不能删除
        if($menu->checkSon($id)){
            $menu->delete();
            \Yii::$app->session->setFlash('danger','删除菜单成功');
            return $this->redirect(['menu/index']);
        }else{
            \Yii::$app->session->setFlash('warning','该菜单下有子菜单,不能删除');
            return $this->redirect(['menu/index']);
        }
    }
}
