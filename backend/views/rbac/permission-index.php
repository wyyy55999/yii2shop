<?=\yii\bootstrap\Html::a('添加权限',['rbac/permission-add'],['class'=>'btn btn-info','style'=>'margin-bottom:8px;'])?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>权限名</th>
            <th>权限描述</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($permissions as $permission): ?>
            <tr>
                <td><?=$permission->name?></td>
                <td><?=$permission->description?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('修改',['rbac/permission-update','name'=>$permission->name])?> /
                    <?=\yii\bootstrap\Html::a('删除',['rbac/permission-delete','name'=>$permission->name],['onclick'=>'return notice()'])?>
                </td>
            </tr>
        <?php endforeach; ?>
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
        return confirm('您确认删除吗？删除后数据无法恢复!');
    }
</script>