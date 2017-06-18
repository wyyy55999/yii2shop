<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <style type="text/css">
        #change_pwd_button{
            text-decoration: none;
        }
    </style>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '京西商城后台',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => '首页', 'url' => ['/site/index']],
        ['label' => '商品列表', 'url' => ['/goods/index']],
        ['label' => '商品分类列表', 'url' => ['/goods-category/index']],
        ['label' => '品牌列表', 'url' => ['/brand/index']],
    ];
    if (Yii::$app->user->isGuest) {   //如果是游客  就显示登录
        $menuItems[] = ['label' => '登录', 'url' => ['/user/login']];
    } else {  //否则  就显示退出登录
        $menuItems[] = '<li>'
            . Html::beginForm(['/user/logout'], 'post')
            . Html::submitButton(
                //显示出登录名
                '退出登录 (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout','id'=>'logout_button']
            )
            .Html::a(
                '修改密码',['user/change-pwd'],
                ['class' => 'btn btn-link change_pwd_button','id'=>'change_pwd_button']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
<?php
$js = new \yii\web\JsExpression(
    <<<JS
    $('#logout_button').on('mouseover',function() {
          
    });
JS
);
$this->registerJs($js);
?>
</body>
</html>
<?php $this->endPage() ?>
