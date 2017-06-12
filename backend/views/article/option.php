<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($article,'name');
echo $form->field($article,'intro')->textarea();
echo $form->field($detail,'content')->textarea();
echo $form->field($article,'article_category_id')->dropDownList($article_cates);
echo $form->field($article,'sort');
echo $form->field($article,'status',['inline'=>true])->radioList(\backend\models\ArticleCategory::$statusOptions);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
