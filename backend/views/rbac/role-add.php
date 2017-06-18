<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($role_model,'name');
echo $form->field($role_model,'description');
echo $form->field($role_model,'permission',['inline'=>true])->checkboxList(\backend\models\RoleForm::getPermissions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();