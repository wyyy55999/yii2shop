<style type="text/css">
    table th{
        text-align: center;
    }
    table td{
        text-align: center;
        vertical-align: middle!important;
    }
</style>
<?=\yii\bootstrap\Html::a('新增',['article-category/add'],['class'=>'btn btn-info','style'=>'margin-bottom:8px;'])?>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th>分类简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>文档类型</th>
        <th>操作</th>
    </tr>
    <?php foreach ($cates as $cate):?>
        <tr>
            <td><?=$cate->id?></td>
            <td><?=$cate->name?></td>
            <td><?=$cate->intro?></td>
            <td><?=$cate->sort?></td>
            <td><?=\backend\models\Brand::$statusOptions[$cate->status]?></td>
            <td><?=($cate->is_help == 1) ? '帮助型文档':'非帮助型文档';?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['article-category/update','id'=>$cate->id])?> /
                <?=\yii\bootstrap\Html::a('删除',['article-category/delete','id'=>$cate->id],['onclick'=>'return notice()'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<script type="text/javascript">
    function notice() {
        return confirm('您确认删除吗？删除后数据无法恢复!');
    }
</script>