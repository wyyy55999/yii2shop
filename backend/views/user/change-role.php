<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'roles',['inline'=>true])->checkboxList(\backend\models\User::getRoles());
echo \yii\bootstrap\Html::submitButton('提交',[
    'class'=>'btn btn-primary'
]);
\yii\bootstrap\ActiveForm::end();