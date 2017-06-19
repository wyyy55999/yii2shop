<style type="text/css">
    table th{
        text-align: center;
    }
    table td{
        text-align: center;
        vertical-align: middle!important;
    }
</style>
<?=\yii\bootstrap\Html::a('新增',['brand/add'],['class'=>'btn btn-info','style'=>'margin-bottom:8px;'])?>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>品牌简介</th>
        <th>品牌LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
<?php foreach ($brands as $brand):?>
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->intro?></td>
        <td><?=\yii\bootstrap\Html::img($brand->logo,['class'=>'img-circle','width'=>50,'height'=>50])?></td>
        <td><?=$brand->sort?></td>
        <td><?=\backend\models\Brand::$statusOptions[$brand->status]?></td>
        <td>
            <?=Yii::$app->user->can('brand/update') ? \yii\bootstrap\Html::a('修改',['brand/update','id'=>$brand->id]).' / ' : ''; ?>
            <!-- 物理删除-->
            <?=Yii::$app->user->can('brand/delete') ? \yii\bootstrap\Html::a('删除',['brand/delete','id'=>$brand->id],['onclick'=>'return notice()']) : '';?>
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