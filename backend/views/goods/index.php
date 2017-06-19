<style type="text/css">
    table th{
        text-align: center;
    }
    table td{
        text-align: center;
        vertical-align: middle!important;
    }
    #search-form{
        margin-bottom: 26px;
    }
    #search-form div{
        margin-bottom: 0;
    }
</style>
<?php
/**
 * @var $this yii\web\View
 */
$config = [
        'method'=>'get',
        'class'=>'form-inline',
        'id'=>'search-form',
        'action'=>'index'   //写了action之后地址栏才会更新  而不是一直向后面拼接！！！
];
$form = \yii\bootstrap\ActiveForm::begin($config);
echo '<div class="form-group col-lg-2">';
echo $form->field($goods,'name')->textInput(['placeholder'=>'请输入商品名','id'=>'goods_name'])->label(false);
echo '</div>';
echo '<div class="form-group col-lg-2">';
echo $form->field($goods,'sn')->textInput(['placeholder'=>'请输入商品货号'])->label(false);
echo '</div>';
/*echo '<div class="form-group col-lg-2">';
echo $form->field($goods,'goods_category_id')->dropDownList($goods_cates,['prompt'=>'请选择分类'])->label(false);
echo '</div>';*/
echo '<div class="form-group col-lg-2">';
echo $form->field($goods,'min_price')->textInput(['placeholder'=>'最低价'])->label(false);
echo '</div> <p style="float:left;margin-top: 5px;"> -- </p> ';
echo '<div class="form-group col-lg-2">';
echo $form->field($goods,'max_price')->textInput(['placeholder'=>'最高价'])->label(false);
echo '</div>';
echo '<div class="form-group col-lg-2">';
echo $form->field($goods,'brand_id')->dropDownList($brands,['prompt'=>'请选择品牌'])->label(false);
echo '</div>';
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info','id'=>'submitbutton']);
\yii\bootstrap\ActiveForm::end();
?>

<?=\yii\bootstrap\Html::a('新增',['goods/add'],['class'=>'btn btn-warning','style'=>'margin-bottom:8px;'])?>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>商品编号</th>
        <th>所属分类</th>
        <th>商品LOGO</th>
        <th>商品品牌</th>
        <th>市场售价</th>
        <th>本店售价</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goodses as $goods):?>
    <tr>
        <td><?=$goods->id?></td>
        <td><?=\yii\bootstrap\Html::a("{$goods->name}",['goods-album/add','goods_id'=>$goods->id])?></td>
        <td><?=$goods->sn?></td>
        <td><?php
            if($goods->goods_category_id) {
                echo $goods->goodsCate->name;
            }else {
                echo '顶级分类';
            }
            ?></td>
        <td><?=\yii\bootstrap\Html::img(Yii::getAlias('@web').$goods->logo,['width'=>100,'height'=>80])?></td>
        <td><?=$goods->goodsBrand->name?></td>
        <td><?=$goods->market_price?></td>
        <td><?=$goods->shop_price?></td>
        <td><?=$goods->stock?></td>
        <td><?=\backend\models\Goods::$saleOptions[$goods->is_on_sale]?></td>
        <td><?=\backend\models\Goods::$statusOptions[$goods->status]?></td>
        <td><?=$goods->sort?></td>
        <td><?=date('Y-m-d H:i:s',$goods->create_time)?></td>
        <td>
            <?=Yii::$app->user->can('goods/update') ? \yii\bootstrap\Html::a('修改',['goods/update','id'=>$goods->id]) : ''?> /
            <?=Yii::$app->user->can('goods/delete') ? \yii\bootstrap\Html::a('删除',['goods/delete','id'=>$goods->id],['onclick'=>'return notice()']) : ''?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'hideOnSinglePage'=>false,
]);
?>
<script type="text/javascript">
    function notice() {
        return confirm('您确认删除吗？删除后数据无法恢复!');
    }
</script>
