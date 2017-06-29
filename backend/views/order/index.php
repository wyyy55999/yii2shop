<?php
/* @var $this yii\web\View */
?>
<style type="text/css">
    table th{
        text-align: center;
    }
    table td{
        text-align: center;
        vertical-align: middle!important;
    }
    .status_button:hover{
        cursor:not-allowed;
    }
</style>
<table class="table table-hover table-striped table-bordered">
    <thead>
        <tr>
            <th>用户名</th>
            <th>收货人</th>
            <th>地址</th>
            <th>电话</th>
            <th>配送方式</th>
            <th>支付方式</th>
            <th>订单金额</th>
            <th>状态</th>
            <th>订单生成时间</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order):?>
        <tr>
            <td><?=\frontend\models\Member::findOne(['id'=>$order->member_id])->username?></td>
            <td><?=$order->name?></td>
            <td><?=$order->province.$order->city.$order->area.$order->address?></td>
            <td><?=$order->tel?></td>
            <td><?=$order->delivery_name?></td>
            <td><?=$order->payment_name?></td>
            <td><?=$order->total?></td>
            <td><?=\backend\models\Order::$orderStatus[$order->status]?></td>
            <td><?=date('Y-m-d H:i:s',$order->create_time)?></td>
            <td class="data-status"><?php
                if($order->status != 2){
                    echo \yii\bootstrap\Html::button('修改为已发货',['class'=>'btn btn-xs btn-default status_button']);
                }else{
                    echo \yii\bootstrap\Html::a('修改为已发货',['order/status-update','order_id'=>$order->id],['class'=>'btn btn-xs btn-warning','onclick'=>'return notice()']);
                }
                ?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
/**
 * @var $this yii\web\View
 */
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({

});')
?>
<script type="text/javascript">
    function notice() {
        return confirm('确认发货？');
    }
</script>