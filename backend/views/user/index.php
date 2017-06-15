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
<?=\yii\bootstrap\Html::a('新增',['user/add'],['class'=>'btn btn-info','style'=>'margin-bottom:8px;'])?>
<?=\yii\bootstrap\Html::a('注销登录',['user/logout'],['class'=>'btn btn-warning','style'=>'margin-bottom:8px;margin-left:8px;'])?>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <th>ID</th>
        <th>管理员名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>添加时间</th>
        <th>修改时间</th>
        <th>最后登陆时间</th>
        <th>最后登陆ip</th>
        <th>操作</th>
    </tr>
    <?php foreach ($admins as $admin):?>
    <tr>
        <td><?=$admin->id?></td>
        <td><?=$admin->username?></td>
        <td><?=$admin->email?></td>
        <td><?=\backend\models\User::$statusOptions[$admin->status]?></td>
        <td><?=date('Y-m-d H:i:s',$admin->created_at)?></td>
        <td><?=date('Y-m-d H:i:s',$admin->updated_at)?></td>
        <td><?=date('Y-m-d H:i:s',$admin->last_login_time)?></td>
        <td><?=$admin->last_login_ip?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['user/update','id'=>$admin->id])?> /
            <!-- 物理删除-->
            <?=\yii\bootstrap\Html::a('删除',['user/delete','id'=>$admin->id],['onclick'=>'return notice()'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<script type="text/javascript">
    function notice() {
        return confirm('您确认删除吗？删除后数据无法恢复!');
    }
</script>

