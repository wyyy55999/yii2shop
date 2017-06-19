<?php
namespace backend\widgets;

use backend\models\Menu;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;
use Yii;

class MenuWidget extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        NavBar::begin([
            'brandLabel' => '京西商城后台',
            'brandUrl' => \Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => '首页', 'url' => ['/goods/index']],
        ];
        if (Yii::$app->user->isGuest) {   //如果是游客  就显示登录
            $menuItems[] = ['label' => '登录', 'url' => Yii::$app->user->loginUrl];
        } else {  //否则  就显示退出登录
            $menus = Menu::findAll(['parent_id'=>0]);
            foreach ($menus as $menu) {
                $items = ['label' => $menu->label,'items'=>[]];
                //$menu->children  当前一级菜单的子菜单
                foreach ($menu->children as $child) {
                    if(Yii::$app->user->can($child->url)){  //判断用户是否有权限操作
                        //有权限操作才显示
                        $items['items'][] = ['label' => $child->label,'url'=>[$child->url]];
                    }
                }
                if(!empty($items['items'])){   //如果有子菜单才显示   不能用$menu->children 来判断  因为children是有的  只是被隐藏了，应该用是否有子菜单来判断
                    $menuItems[] = $items;
                }
            }
            $menuItems[] = ['label'=>'注销登录('.Yii::$app->user->identity->username.')','url'=>['/user/logout']];
            $menuItems[] = ['label'=>'修改密码','url'=>['user/change-pwd']];
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }
}