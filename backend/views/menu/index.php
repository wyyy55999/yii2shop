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
</style>
<?=\yii\bootstrap\Html::a('新增菜单',['menu/add'],['class'=>'btn btn-info','style'=>'margin-bottom:8px;'])?>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>ID</th>
        <th>标签</th>
        <th>地址</th>
        <th>上级菜单</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($menus as $menu):?>
        <tr>
            <td><?=$menu->id?></td>
            <td><?=$menu->label?></td>
            <td><?=$menu->url?></td>
            <td>
                <?php
                    if($menu->parent_id){
                        echo $menu->parentLabel->label;
                    }else{
                        echo '一级菜单';
                    }
                ?>
            </td>
            <td><?=$menu->sort?></td>
            <td>
                <?=Yii::$app->user->can('menu/update') ? \yii\bootstrap\Html::a('修改',['menu/update','id'=>$menu->id]) : ''?> /
                <?=Yii::$app->user->can('menu/update') ? \yii\bootstrap\Html::a('删除',['menu/delete','id'=>$menu->id],['onclick'=>'return notice()']) : ''?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<script type="text/javascript">
    function notice() {
        return confirm('您确认删除吗？删除后数据无法恢复!');
    }
</script>

