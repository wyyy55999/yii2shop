<?php

namespace frontend\controllers;

use chenkby\region\Region;
use chenkby\region\RegionAction;
use frontend\models\Address;

class AddressController extends \yii\web\Controller
{
    public $layout = 'address';
    //收货地址展示和添加
    public function actionAdd()
    {
        $address = new Address();
        if(\Yii::$app->request->isPost){
            if($address->load(\Yii::$app->request->post()) && $address->validate()){
                if($address->AddSave()){
                    $address->member_id = \Yii::$app->user->id;
                    $address->save(false);
                    return $this->redirect(['address/add']);
                }
            }
        }
        //找到member对应的所有地址
        $addresses = Address::find()->where('member_id='.\Yii::$app->user->id)->all();
        return $this->render('index',['address'=>$address,'addresses'=>$addresses]);
    }
    //修改收货地址和展示
    public function actionUpdate($id){
        if (!\Yii::$app->user->isGuest){
            $address = Address::findOne(['id'=>$id,'member_id'=>\Yii::$app->user->id]);
            if(\Yii::$app->request->isPost){
                if($address->load(\Yii::$app->request->post()) && $address->validate()){
                    if($address->AddSave()){
                        $address->save(false);
                        return $this->redirect(['address/add']);
                    }
                }
            }
            $addresses = Address::find()->where('member_id='.\Yii::$app->user->id)->all();
            return $this->render('index',['address'=>$address,'addresses'=>$addresses]);
        }
    }
    //删除收货地址
    public function actionDelete($id){
        $address = Address::findOne(['id'=>$id,'member_id'=>\Yii::$app->user->id]);
        $address->delete();
        return $this->redirect(['address/add']);
    }
    //设置为默认地址
    public function actionDefault($id){
        $addresses = Address::find()->where('member_id='.\Yii::$app->user->id)->all();
        if($addresses){
            foreach ($addresses as $address){
                $address->is_default = 0;
                $address->save(false);
            }
        }
        $current_address = Address::findOne(['id'=>$id,'member_id'=>\Yii::$app->user->id]);
        $current_address->is_default = 1;
        $current_address->save(false);
        return $this->redirect(['address/add']);
    }
    //三级联动
    public function actions()
    {
        $actions = parent::actions();
        $actions['get-region']=[
            'class'=>\chenkby\region\RegionAction::className(),
            'model'=>\frontend\models\Address::className()
        ];
        return $actions;
    }
}
