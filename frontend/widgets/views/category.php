<div class="cat_bd">
    <?php
    use yii\helpers\Html;
    foreach ($goods_cates as $k=>$goods_cate):
        ?>
        <div class="cat <?=($k==0)? 'item1':'';?>">
            <h3><?=\yii\helpers\Html::a($goods_cate->name,['goods/list','goods_cate_id'=>$goods_cate->id],['id'=>'goods_cates_id'])?></a> <b></b></h3>
            <div class="cat_detail">
                <!--在循环中根据分类的父id查询到儿子-->
                <?php foreach (\backend\models\GoodsCategory::find()->where('parent_id = '.$goods_cate->id)->all() as $k1=>$son):?>
                    <dl <?=($k1==0)? 'class="dl_1st"' : "";?>>
                        <dt>
                            <?=Html::a($son->name,['goods/list','goods_cate_id'=>$son->id]);?></a>
                        </dt>
                        <?php foreach (\backend\models\GoodsCategory::find()->where('parent_id = '.$son->id)->all() as $grandson):?>
                            <dd>
                                <?=Html::a($grandson->name,['goods/list','goods_cate_id'=>$grandson->id]);?></a>
                            </dd>
                        <?php endforeach;?>
                    </dl>
                <?php endforeach;?>
            </div>
        </div>
    <?php endforeach; ?>
</div>