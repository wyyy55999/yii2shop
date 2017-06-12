<style type="text/css">
    .field-goodscategory-parent_id{
        margin-bottom:-16px;
    }
</style>
<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($goods_cates,'name');
//将父id作为隐藏域传值到控制器
echo $form->field($goods_cates,'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';  //用于显示分类列表
echo $form->field($goods_cates,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();

//使用ztree  加载两个静态资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//\yii\web\JqueryAsset  是jquery的静态资源管理器
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode($options);  //解析控制器返回的所有分类选项数组
$js = new \yii\web\JsExpression(  //定义一个多行字符串
//不会解析里面的特殊字符
    <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback:{
                onClick:function(event, treeId, treeNode) {
                    //console.debug(treeNode.id);
                    //将选中节点的id赋值给表单的隐藏域作为传过去的parent_id
                    $('#goodscategory-parent_id').val(treeNode.id);
                }
            }
         };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$zNodes};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展开所有节点
        zTreeObj.expandAll(true);
        //获取当前节点的父节点  根据id
        var node = zTreeObj.getNodeByParam("id", $('#goodscategory-parent_id').val(), null);
        //选中当前节点的父节点
        zTreeObj.selectNode(node);

JS

);
//将多行字符串进行注册加载
$this->registerJs($js);
?>

