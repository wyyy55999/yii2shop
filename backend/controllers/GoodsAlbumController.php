<?php

namespace backend\controllers;


use backend\models\GoodsAlbum;
use xj\uploadify\UploadAction;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsAlbumController extends PublicController
{
    //添加商品相册图片页面并且显示已经添加了的图片
    public function actionAdd($goods_id){
        $goods = new GoodsAlbum();
        $relationBanners = GoodsAlbum::find()->where(['goods_id' => $goods_id])->asArray()->all();
        // @param $p1 Array 需要预览的商品图，是商品图的一个集合
        // @param $p2 Array 对应商品图的操作属性，我们这里包括商品图删除的地址和商品图的id
        $p1 = $p2 = [];
        if ($relationBanners) {
            foreach ($relationBanners as $k => $v) {
                $p1[$k] = $v['album_path'];  //这里要和数据库的字段名一致
                $p2[$k] = [
                    // 要删除商品图的地址
                    'url' => Url::toRoute('/goods-album/delete'),
                    // 商品图对应的商品图id
                    'key' => $v['id'],
                ];
            }
        }
        return $this->render('album-option', [
            // other params
            'p1' => $p1,
            'p2' => $p2,
            // 商品id
            'id' => $goods_id,
            'goods'=>$goods
        ]);
/*

        $request = new Request();
        if($request->isPost){
            var_dump($request->post());
        }else{
            return $this->render('ablum-option',['goods'=>$goods,'goods_id'=>$goods_id]);
        }*/
    }
    //保存添加
    public function actionAddSave(){
        //实例化
        $model = new GoodsAlbum();
        // 商品ID
        $id = \Yii::$app->request->post('goods_id');
        // $p1 $p2是我们处理完图片之后需要返回的信息，其参数意义可参考上面的讲解
        $p1 = $p2 = [];
        // 如果没有商品图或者商品id非真，返回空                               这里使用在模型中自定义的相册字段  album
        if (empty($_FILES['GoodsAlbum']['name']) || empty($_FILES['GoodsAlbum']['name']['album']) || !$id) {
            echo '{}';
            return;
        }
        // 循环多张商品相册图进行上传和上传后的处理     这里使用在模型中自定义的相册字段  album
        for ($i = 0; $i<count($_FILES['GoodsAlbum']['name']['album']); $i++) {
            // 上传之后的商品图是可以进行删除操作的，我们为每一个商品成功的商品图指定删除操作的地址
            $url = '/goods-album/delete';
            // 调用图片接口上传后返回的图片地址，注意是可访问到的图片地址哦
            $imageUrl = '';
            //这里使用在模型中自定义的相册字段  album
            $model->album = UploadedFile::getInstance($model,'album');
            if($model->validate()){   //如果验证成功   将图片存到upload下
                $web_path = \Yii::getAlias('@webroot');   //绝对路径
                //文件夹路径
                $dirname = '/upload/goods_album/'.date('Ymd').'/'.sprintf("%05d",$id).'/';
                if(!is_dir($web_path.$dirname)){   //不存在该文件夹就创建
                    mkdir($web_path.$dirname,0777,true);
                }
                //文件名
                $filename = uniqid().'.'.$model->album->getExtension();
                //分目录存入upload目录下
                $model->album->saveAs($web_path.$dirname.$filename);
            }else{
                var_dump($model->getErrors());
                exit;
            }
            // 保存商品图信息
            $model->goods_id = $id;
            //数据库保存的路劲是文件夹  +  文件名  +  后缀
            $model->album_path = $dirname.$filename;
            $key = 0;
            if ($model->save(false)) {
                $key = $model->id;
            }
            // 这是一些额外的其他信息，如果你需要的话
            // $pathinfo = pathinfo($imageUrl);
            // $caption = $pathinfo['basename'];
            // $size = $_FILES['Banner']['size']['banner_url'][$i];
            $p1[$i] = $imageUrl;
            $p2[$i] = ['url' => $url, 'key' => $key];   //返回的结果在delete操作中使用
        }
        // 返回上传成功后的商品图信息
        echo json_encode([
            'initialPreview' => $p1,
            'initialPreviewConfig' => $p2,
            'append' => true,
        ]);
        return;
    }
    //删除
    public function actionDelete ()
    {
        // 前面我们已经为成功上传的相册图album指定了key,此处的key也即时相册图的id
        if ($id = \Yii::$app->request->post('key')) {  //
            $model = GoodsAlbum::findOne(['id'=>$id]);
            $model->delete();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['success' => true];
    }
    //uploadify插件
    public function actions() {
        return [
            //uploadify插件
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
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
                    return "goods_album/{$p1}/{$p2}/{$filehash}.{$fileext}";
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
                    $action->output['fileUrl'] = $action->getWebUrl();
                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
}