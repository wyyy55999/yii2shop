<style type="text/css">
    table th{
        text-align: center;
    }
    table td{
        text-align: center;
        vertical-align: middle!important;
    }
</style>
<?=\yii\bootstrap\Html::a('新增',['article/add'],['class'=>'btn btn-info','style'=>'margin-bottom:8px;'])?>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>ID</th>
        <th>文章名称</th>
        <th>文章简介</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->articleCategory->name?></td>
            <td><?=$article->sort?></td>
            <td><?=\backend\models\Brand::$statusOptions[$article->status]?></td>
            <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['article/update','id'=>$article->id])?> /
                <?=\yii\bootstrap\Html::a('删除',['article/delete','id'=>$article->id],['onclick'=>'return notice()'])?> /
                <?=\yii\bootstrap\Html::a('查看内容',['article/detail','id'=>$article->id])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<script type="text/javascript">
    function notice() {
        return confirm('您确认删除吗？删除后数据无法恢复!');
    }
</script>

