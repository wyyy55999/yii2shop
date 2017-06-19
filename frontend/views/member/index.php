<?php
/* @var $this yii\web\View */
?>
<?=\yii\helpers\Html::a('注册',['member/register'])?>
<?php
var_dump(Yii::$app->user->identity);
?>