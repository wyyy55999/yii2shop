<?php
use yii\web\JsExpression;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($brand,'name');
echo $form->field($brand,'intro')->textarea();
//echo $form->field($brand,'logoFile')->fileInput(['id' => 'test']);
echo $form->field($brand,'logo')->hiddenInput(['id' => 'logo_path']);
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,  //==>跨站攻击验证
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将上传成功后的图片地址写入img标签
        $('#img_logo').attr("src",data.fileUrl).show();
        //将上传成功后的图片地址写入logo字段(数据库的logo字段)
        $('#logo_path').val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if(isset($brand->logo)){
    echo \yii\bootstrap\Html::img($brand->logo,['width'=>100,'height'=>100,'id'=>'img_logo']);
}else{
    echo \yii\bootstrap\Html::img('',['style'=>'display:none;','id'=>'img_logo','width'=>100,'height'=>100]);
}
echo $form->field($brand,'sort');
echo $form->field($brand,'status',['inline'=>true])->radioList(\backend\models\Brand::$statusOptions);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();