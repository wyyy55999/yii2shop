<?php
/* @var $this yii\web\View */
$this->registerJsFile('@web/js/jquery-1.8.3.min.js',['depends'=>\yii\web\JqueryAsset::className()])
?>
<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><?=\yii\helpers\Html::img('@web/images/logo.png')?></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
    <?php
    $form = \yii\widgets\ActiveForm::begin();
    ?>
    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($member_address as $address):?>
                <p>
                    <input type="radio" value="<?=$address->id?>" name="address_id" <?=$address->is_default ? 'checked' : ''?>/><?=$address->consignee?>  <?=$address->tel?>  <?=$address->pro->name?> <?=$address->cit->name?> <?=$address->are->name?> <?=$address->detail_address?> <?=$address->is_default ? '<span style="color: red;">[ 默认地址 ]</span>' : ''?>
                </p>
                <?php endforeach;?>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>
            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($deliveries as $k=>$delivery):
                    ?>
                    <tr <?=($k==0) ? 'class="cur" ' : ''?>>
                        <td>
                            <input type="radio" name="Order[delivery_id]" value="<?=$delivery['delivery_id']?>" <?=($k==0) ? 'checked="checked"' : ''?> class="delivery_class"/><?=$delivery['delivery_name']?>
                        </td>
                        <td>￥<span class="delivery_price_span"><?=$delivery['delivery_price']?></span></td>
                        <td><?=$delivery['delivery_intro']?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>
            <div class="pay_select">
                <table>
                    <?php foreach ($payments as $p=>$payment):?>
                    <tr <?=($p==0) ? 'class="cur"' : ''?>>
                        <td class="col1"><input type="radio" name="Order[payment_id]" value="<?=$payment['payment_id']?>" <?=$p ? '' : 'checked="checked"'?>/><?=$payment['payment_name']?></td>
                        <td class="col2"><?=$payment['payment_intro']?></td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <!--<div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>-->
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total_amount = 0;
                $total_price = 0;
                foreach ($member_goodses as $member_goods):
                    //商品价格小计
                    $goods_small_price = (\backend\models\Goods::findOne(['id'=>$member_goods->goods_id])->shop_price)*$member_goods->amount;
                    $total_amount += 1;
                    $total_price += $goods_small_price;
                ?>
                    <input type="hidden" name="goods[]" value="<?=$member_goods->goods_id?>">
                <tr>
                    <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2shop.com'.\backend\models\Goods::findOne(['id'=>$member_goods->goods_id])->logo)?></a>  <strong><a href=""><?=\backend\models\Goods::findOne(['id'=>$member_goods->goods_id])->name?></a></strong></td>
                    <td class="col3">￥<?=\backend\models\Goods::findOne(['id'=>$member_goods->goods_id])->shop_price?></td>
                    <td class="col4"> <?=$member_goods->amount?></td>
                    <td class="col5"><span>￥<?=$goods_small_price?></span></td>
                </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$total_amount?> 件商品，总商品金额：￥</span>
                                <em id="goods_price_em"><?=$total_price?></em>
                            </li>
                            <li>
                                <span>返现：-</span>
                                <em>￥240.00</em>
                            </li>
                            <li>
                                <span>运费：￥</span>
                                <em id="delivery_em">10.00</em>
                            </li>
                            <li>
                                <span>应付总额：￥</span>
                                <em id="total_price_em"><?=$total_price?></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
<!--        <a href=""><span>提交订单</span></a>-->

        <input type="submit" value=" " id="submit_button" style="float: right; display: inline; width: 135px; height: 36px; background: url(<?=Yii::getAlias('@web').'/images/order_btn.jpg'?>) 0 0 no-repeat; vertical-align: middle; margin: 7px 10px 0;">
        <input type="hidden" name="total" id="final_total_price_hidden" value="<?=$total_price?>">
        <p>应付总额：<strong id="final_price_strong">￥<?=$total_price?>元</strong></p>
    </div>
    <?php \yii\widgets\ActiveForm::end();?>
</div>
<!-- 主体部分 end -->
<?php
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $(function() {
        //运费的text 
        var delivery_price = $('.delivery_class:checked').closest('tr').find('td span').text();
        //运费
        $('#delivery_em').text(delivery_price);
        //总商品金额
        var total_price = $('#goods_price_em').text();
        //商品金额 + 运费 = 最后需要支付的价格
        var final_price = Number(total_price) + Number(delivery_price);
        $('#total_price_em').text(final_price);
        $('#final_price_strong').text(final_price);
        $('#final_total_price_hidden').val(final_price);
    });
    $('.delivery_class').click(function() {
        var delivery_price = $(this).closest('tr').find('td span').text();
        $('#delivery_em').text(delivery_price);
        var total_price = $('#goods_price_em').text();
        var final_price = Number(total_price) + Number(delivery_price);
        $('#total_price_em').text(final_price);
        $('#final_price_strong').text(final_price);
        $('#final_total_price_hidden').val(final_price);
    });
JS
));
?>