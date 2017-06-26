<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $total_money = 0;//总金额
            foreach ($goods_models as $goods_model):
                $total_money += $goods_model['shop_price']*$goods_model['amount'];
        ?>
        <tr data-goods_id="<?=$goods_model['id']?>">
            <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2shop.com'.$goods_model['logo'])?></a>  <strong><a href=""><?=$goods_model['name']?></a></strong></td>
            <td class="col3">￥<span><?=$goods_model['shop_price']?></span></td>
            <td class="col4">
                <a href="javascript:;" class="reduce_num"></a>
                <input type="text" name="amount" value="<?=$goods_model['amount']?>" class="amount"/>
                <a href="javascript:;" class="add_num"></a>
            </td>
            <td class="col5">￥<span id="small_account"><?=$goods_model['shop_price']*$goods_model['amount']?></span></td>
            <td class="col6"><a href="javascript:;" class="del_goods">删除</a></td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total"><?=$total_money?></span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <?=\yii\helpers\Html::a('继续购物',['index/index'],['class'=>'continue'])?>
        <?php
            if(Yii::$app->user->isGuest){
                echo \yii\helpers\Html::a('结算',['member/login'],['class'=>'checkout']);
            }else{
                echo \yii\helpers\Html::a('结算',['order/fillin'],['class'=>'checkout']);
            }
        ?>
    </div>
</div>
<!-- 主体部分 end -->
<?php
/**
 * @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(['goods/update-amount']);
$csrf = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    //让总数修改后刷新页面后修改
    //监听 + - 的点击事件
    $('.reduce_num,.add_num').on('click',function() {
        //获取到当前id
        var goods_id = $(this).closest('tr').attr('data-goods_id');
        //获取到数量的值
        var amount = $(this).parent().find('.amount').val();
        //发送ajax请求
        $.post('$url',{'goods_id':goods_id,'amount':amount,'_csrf-frontend':'$csrf'},function(res) {
          console.debug(res);
        });
    });
    //删除
    $('.del_goods').click(function() {
        if(confirm('您确认删除吗？')){
            var goods_id = $(this).closest('tr').attr('data-goods_id');
            var old_total_money =  $('#total').text();  //之前的价格
            var small_account = $(this).closest('tr').find('#small_account').text();  //当前要减去的价格
            var new_total_money = old_total_money-small_account;//新的总价
            //发送ajax请求
            $.post('$url',{'goods_id':goods_id,'amount':0,'_csrf-frontend':'$csrf'});
            //修改为新的总价
            $('#total').text(new_total_money);
            //删除当前行
            $(this).closest('tr').remove();
        }
    });
JS
));
?>