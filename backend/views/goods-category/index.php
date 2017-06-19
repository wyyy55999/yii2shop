<style type="text/css">
    table th{
        text-align: center;
    }
    table td{
        text-align: center;
        vertical-align: middle!important;
    }
</style>
<?=\yii\bootstrap\Html::a('新增',['goods-category/add'],['class'=>'btn btn-warning','style'=>'margin-bottom:8px;'])?>
<table class="cate table table-bordered table-hover table-striped">
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th>所属分类</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods_cates as $cate):?>
        <!--左值  右值   树-->
        <tr data-lft="<?=$cate->lft?>" data-rgt="<?=$cate->rgt?>" data-tree="<?=$cate->tree?>">
            <td><?=$cate->id?></td>
            <td style="text-align: left;width: 500px;">
                <?=str_repeat(' - - -',$cate->depth).$cate->name?>
                <span class="toogle_cate glyphicon glyphicon-chevron-down" style="float: right;"></span>
            </td>
            <td><?php
                //这里必须判断  否则会报错  因为顶级分类没有父id  所以查找不出那个对象
                if($cate->parent_id){
                    echo $cate->parentname->name;
                }else{
                    echo '顶级分类';
                }
                ?></td>
            <td>
                <?=Yii::$app->user->can('goods-category/update') ? \yii\bootstrap\Html::a('修改',['goods-category/update','id'=>$cate->id]) : ''?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
   $js = <<<JS
    function notice() {
        return confirm('您确认删除吗？删除后数据无法恢复!');
    }
    $('.toogle_cate').click(function(){
        //获取左值  右值  tree
        var tr = $(this).closest('tr');
        var tree = parseInt(tr.attr('data-tree'));
        var lft = parseInt(tr.attr('data-lft'));
        var rgt = parseInt(tr.attr('data-rgt'));
        //获取当前的class  检查被选元素是否包含指定的 class。
        var show = $(this).hasClass('toogle_cate glyphicon glyphicon-chevron-up');
        //切换图标
        $(this).toggleClass('toogle_cate glyphicon glyphicon-chevron-down');
        $(this).toggleClass('toogle_cate glyphicon glyphicon-chevron-up');
        //遍历查找当前分类的子孙分类（根据当前分类的tree和左右值）
        $('.cate tr:not(:first)').each(function() {
            //$(this).attr('data-tree')  是指所有tr的树
            if(parseInt($(this).attr('data-tree'))==tree && parseInt($(this).attr('data-lft'))>lft && parseInt($(this).attr('data-rgt'))<rgt){
                         //淡入显示              //淡出隐藏
                 show ? $(this).fadeIn() : $(this).fadeOut(); 
                //$(this).fadeToggle();  //fadeToggle()方法可以在 fadeIn() 与 fadeOut() 方法之间进行切换  有bug！！！
            }
        })
    });
JS;
   $this->registerJs($js);