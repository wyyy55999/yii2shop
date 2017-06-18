<?=\yii\bootstrap\Html::a('添加角色',['rbac/role-add'],['class'=>'btn btn-info','style'=>'margin-bottom:8px;'])?>
<table class="table table-bordered table-hover">
    <tr>
        <th>角色名</th>
        <th>角色描述</th>
        <th>角色权限</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role): ?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td><?php
                foreach(Yii::$app->authManager->getPermissionsByRole($role->name) as $permission){
                    echo $permission->description.'、';
                }
                ?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['rbac/role-update','name'=>$role->name])?> /
                <?=\yii\bootstrap\Html::a('删除',['rbac/role-delete','name'=>$role->name],['onclick'=>'return notice()'])?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<script type="text/javascript">
    function notice() {
        return confirm('您确认删除吗？删除后数据无法恢复!');
    }
</script>