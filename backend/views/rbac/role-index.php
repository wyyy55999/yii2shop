<style type="text/css">
    table th,#option_td{
        text-align: center;
    }
</style>
<?=Yii::$app->user->can('rbac/role-add') ? \yii\bootstrap\Html::a('添加角色',['rbac/role-add'],['class'=>'btn btn-info','style'=>'margin-bottom:8px;']) : ''?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>角色名</th>
            <th>角色描述</th>
            <th>角色权限</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($roles as $role): ?>
        <tr>
            <td width="150"><?=$role->name?></td>
            <td width="200"><?=$role->description?></td>
            <td><?php
                foreach(Yii::$app->authManager->getPermissionsByRole($role->name) as $permission){
                    echo $permission->description.'、';
                }
                ?></td>
            <td width="100" id="option_td">
                <?=Yii::$app->user->can('rbac/role-update') ? \yii\bootstrap\Html::a('修改',['rbac/role-update','name'=>$role->name]) : ''?> /
                <?=Yii::$app->user->can('rbac/role-delete') ? \yii\bootstrap\Html::a('删除',['rbac/role-delete','name'=>$role->name],['onclick'=>'return notice()']) : ''?>
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