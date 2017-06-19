<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($menu,'label');
echo $form->field($menu,'url')->textInput(['placeholder'=>'若为一级菜单，不填，若为二级菜单，必填，格式为：xxx/xxx']);
echo $form->field($menu,'parent_id')->dropDownList($parent_menus,['prompt'=>' == 请选择上级菜单 ==']);
echo $form->field($menu,'sort');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();