<style type="text/css">
    .field-userloginform-is_remember{
        float: right;
    }
</style>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($user_login,'username');
echo $form->field($user_login,'password')->passwordInput();
echo '<div class="form-group col-lg-2">';
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-success']);
echo $form->field($user_login,'is_remember')->checkbox([1=>'记住密码']);
echo '</div>';
\yii\bootstrap\ActiveForm::end();