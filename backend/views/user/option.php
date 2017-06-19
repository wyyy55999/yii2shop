<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($admin,'username');
if(!$admin->password_hash){
    //这里使用自己定义的字段名  否则在判断重名后  会隐藏掉密码框  因为password_hash已经有值
    echo $form->field($admin,'password')->passwordInput();
    echo $form->field($admin,'repassword')->passwordInput();
}
echo $form->field($admin,'email');
echo $form->field($admin,'status',['inline'=>true])->radioList(\backend\models\User::$statusOptions);
//if(!$admin->id){
echo $form->field($admin,'roles',['inline'=>true])->checkboxList(\backend\models\User::getRoles());
//}
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();