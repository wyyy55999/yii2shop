<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($permission_model,'name');
echo $form->field($permission_model,'description');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();