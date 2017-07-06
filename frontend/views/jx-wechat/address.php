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
            <h1 class="page__title">地址</h1>
            <p class="page__desc">address</p>
        </div>
        <div class="page__bd">
            <div class="weui-cells">
                <?php foreach ($addresses as $k=>$address):?>
                    <a class="weui-cell weui-cell_access" href="javascript:;">
                    <div class="weui-cell__bd">
                        <p>
                            <?php
                                echo ($k+1).'. ';
                                echo $address->consignee.' '.$address->pro->name.' '.$address->cit->name.' '.$address->are->name.' '.$address->detail_address;
                                echo $address->tel;
                            ?>
                        </p>
                    </div>
                    <div class="weui-cell__ft"><?=$address->is_default ? '<span style="color: red;">[ 默认地址 ]</span>' : '';?></div>
                </a>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</body>
</html>