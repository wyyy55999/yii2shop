<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>我的订单</title>
    <!-- 引入 WeUI -->
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css"/>
</head>
<body>

<div class="page">
    <div class="page__hd">
        <h1 class="page__title">订单</h1>
        <p class="page__desc">order</p>
    </div>
    <div class="page__bd">
        <?php foreach ($orders as $order): ?>
        <div class="weui-form-preview">
            <div class="weui-form-preview__hd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">订单金额：</label>
                    <em class="weui-form-preview__value"><?=$order->total?></em>
                </div>
            </div>
            <div class="weui-form-preview__bd">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">订单号：</label>
                    <span class="weui-form-preview__value"><?=$order->id?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">收货人：</label>
                    <span class="weui-form-preview__value"><?=$order->name?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">电话：</label>
                    <span class="weui-form-preview__value"><?=$order->tel?></span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">状态：</label>
                    <span class="weui-form-preview__value">
                        <?php
                        if($order->status == 0){
                            echo '已取消';
                        }elseif($order->status == 1){
                            echo '待付款';
                        }elseif($order->status == 2){
                            echo '待发货';
                        }elseif($order->status == 3){
                            echo '待收货';
                        }else{
                            echo '完成';
                        }
                        ?>

                    </span>
                </div>
            </div>
            <div class="weui-form-preview__ft">
                <a class="weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:">操作</a>
            </div>
        </div>
    <?php endforeach; ?>
        <br>
    </div>
<!--    <div class="page__ft">-->
<!--        <a href="javascript:home()"><img src="./images/icon_footer_link.png" /></a>-->
<!--    </div>-->
</div>
<!--


<div class="page__hd">
    <h1 class="page__title">订单</h1>
    <p class="page__desc">order</p>
</div>
<div class="page__bd">
    <?php /*foreach ($orders as $order): */?>
    <div class="weui-cells__title">订单<?/*=$order->id*/?></div>
    <div class="weui-cells">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>收货人：<?/*=$order->name*/?></p>
            </div>
            <div class="weui-cell__ft">电话：<?/*=$order->tel*/?></div>
        </div>
    </div>
    <div class="weui-cells">
        <div class="weui-cell">
            <div class="weui-cell__bd">
                <p>状态：
                    <?php
/*                        if($order->status == 0){
                            echo '已取消';
                        }elseif($order->status == 1){
                            echo '待付款';
                        }elseif($order->status == 2){
                            echo '待发货';
                        }elseif($order->status == 3){
                            echo '待收货';
                        }else{
                            echo '完成';
                        }
                    */?>
                </p>
            </div>
            <div class="weui-cell__ft">总额：<?/*=$order->total*/?></div>
        </div>
    </div>
    <?php /*endforeach;*/?>
</div>-->


</body>
</html>