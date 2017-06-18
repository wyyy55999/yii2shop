<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'old_password')->passwordInput(['placeholder'=>'请输入旧密码']);
echo $form->field($admin,'password')->passwordInput(['placeholder'=>'请输入新密码']);
echo $form->field($admin,'repassword')->passwordInput(['placeholder'=>'请再次输入新密码']);
echo \yii\bootstrap\Html::submitButton('确认修改',['class'=>'btn btn-warning']);
\yii\bootstrap\ActiveForm::end();