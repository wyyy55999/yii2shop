<?php
/* @var $this yii\web\View */
?>
<style type="text/css">
    .form-control-inline{
        width: 150px;
    }
</style>
<!-- 右侧内容区域 start -->
<div class="content fl ml10">
    <div class="address_hd">
        <h3>收货地址薄</h3>
        <?php foreach ($addresses as $info):?>
        <dl>
            <dt><?=$info->id?>.<?=$info->consignee?>
                <?=\frontend\models\Locations::findOne(['id'=>$info->province])->name; ?>
                <?=\frontend\models\Locations::findOne(['id'=>$info->city])->name; ?>
                <?=\frontend\models\Locations::findOne(['id'=>$info->area])->name; ?>
                <?=$info->detail_address?> <?=$info->tel?></dt>
            <dd>
                <?=\yii\helpers\Html::a('修改',['address/update','id'=>$info->id])?>
                <?=\yii\helpers\Html::a('删除',['address/delete','id'=>$info->id])?>
                <?=$info->is_default ? '<span style="color: red;">[ 默认地址 ]</span>':\yii\helpers\Html::a('设为默认地址',['address/default','id'=>$info->id])?>
            </dd>
        </dl>
        <?php endforeach; ?>
    </div>

    <div class="address_bd mt10">
        <h4>新增收货地址</h4>
        <?php
        $form = \yii\widgets\ActiveForm::begin([
            'fieldConfig'=>[
                'options'=>[
                    'tag'=>'li'
                ],
                'errorOptions'=>[
                    'tag'=>'p'
                ],
            ]
        ]);
        echo '<ul>';
        echo $form->field($address,'consignee')->textInput(['class'=>'txt'])->label('收货人：');
        /*echo $form->field($address,'province')->dropDownList(['北京'=>'北京','上海'=>'上海','天津'=>'天津',],['prompt'=>'请选择省份']);
        echo $form->field($address,'city')->dropDownList(['朝阳区'=>'朝阳区','东城区'=>'东城区','海淀区'=>'海淀区',],['prompt'=>'请选择城市']);
        echo $form->field($address,'area')->dropDownList(['西二旗'=>'西二旗','西三旗'=>'西三旗','三环以内'=>'三环以内',],['prompt'=>'请选择区县']);*/
        echo $form->field($address, 'area')->widget(\chenkby\region\Region::className(),[
            'model'=>$address,
            'url'=> \yii\helpers\Url::toRoute(['get-region']),
            'province'=>[
                'attribute'=>'province',
                'items'=>\frontend\models\Address::getRegion(),
                'options'=>['class'=>'form-control form-control-inline','prompt'=>'请选择省份']
            ],
            'city'=>[
                'attribute'=>'city',
                'items'=>\frontend\models\Address::getRegion($address['province']),
                'options'=>['class'=>'form-control form-control-inline','prompt'=>'请选择城市']
            ],
            'area'=>[
                'attribute'=>'area',
                'items'=>\frontend\models\Address::getRegion($address['city']),
                'options'=>['class'=>'form-control form-control-inline','prompt'=>'请选择区县']
            ]
        ])->label('所在地区：');
        echo $form->field($address,'detail_address')->textInput(['class'=>'txt'])->label('详细地址：');
        echo $form->field($address,'tel')->textInput(['class'=>'txt'])->label('手机号码：');

        echo $form->field($address,'is_default')->checkbox([1=>'设为默认地址','class'=>'check']);
        ?>
        <li>
            <label for="">&nbsp;</label>
            <input type="submit" name="" class="btn" value="保存" />
        </li>
        <?php
        echo '</ul>';
        \yii\widgets\ActiveForm::end();
        ?>
    </div>

</div>
<!-- 右侧内容区域 end -->