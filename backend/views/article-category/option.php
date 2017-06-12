<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($cates,'name');
echo $form->field($cates,'intro')->textarea();
echo $form->field($cates,'sort');
echo $form->field($cates,'status',['inline'=>true])->radioList(\backend\models\ArticleCategory::$statusOptions);
echo $form->field($cates,'is_help',['inline'=>true])->radioList([1=>'帮助型文档',2=>'非帮助型文档']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
