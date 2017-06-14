<style type="text/css">
    .field-goods_cate_id,.field-goods_logo_path{
        margin-bottom:-14px;
    }
</style>
<?php
/**
 * @var $this yii\web\View
 */
use yii\web\JsExpression;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($goods,'name');
//----------------------------------------------------------------------------------------------------------------
echo $form->field($goods,'logo')->hiddenInput(['id'=>'goods_logo_path']);
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将上传成功后的图片地址写入img标签  并显示
        $('#logo_img').attr("src",data.fileUrl).show();
        //将上传成功后的图片地址写入logo字段  以便保存到数据库
        $('#goods_logo_path').val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
//判断是修改还是新增
if(isset($goods->logo)){
    echo \yii\bootstrap\Html::img($goods->logo,['width'=>100,'height'=>100,'id'=>'logo_img']);
}else{
    echo \yii\bootstrap\Html::img('',['style'=>'display:none;','width'=>100,'height'=>100,'id'=>'logo_img']);
}
//----------------------------------------------------------------------------------------------------------------
//将该分类的id作为隐藏域传到控制器
echo $form->field($goods,'goods_category_id')->hiddenInput(['id'=>'goods_cate_id']);
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($goods,'brand_id')->dropDownList($brands,['prompt'=>'== 请选择品牌 ==']);
echo $form->field($goods,'market_price');
echo $form->field($goods,'shop_price');
echo $form->field($goods,'stock');
echo $form->field($goods,'is_on_sale',['inline'=>true])->radioList(\backend\models\Goods::$saleOptions);
echo $form->field($goods,'status',['inline'=>true])->radioList(\backend\models\Goods::$statusOptions);
echo $form->field($goods,'sort');
//echo $form->field($goods_intro,'content')->textarea();
echo $form->field($goods_intro,'content')->widget('kucha\ueditor\UEditor',[]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();
//加载静态资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes = \yii\helpers\Json::encode($goods_cates);
$js = new \yii\web\JsExpression(
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
           onClick:function(event,treeId,treeNode) {
              //将选中的节点的id赋值给隐藏域，作为保存到数据库的商品分类id
              $('#goods_cate_id').val(treeNode.id);
           }
        }
   };
   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$zNodes};
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
   //展开所有节点
    zTreeObj.expandAll(true);
    //获取当前节点的父节点
    var node = zTreeObj.getNodeByParam("id",$('#goods_cate_id').val(),null);
    //选中当前节点的父节点
    zTreeObj.selectNode(node);
JS
);
//必须要有这一步
$this->registerJs($js);