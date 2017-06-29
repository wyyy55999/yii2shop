<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
\frontend\assets\IndexAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <!-- 顶部导航 start -->
    <div class="topnav">
        <div class="topnav_bd w1210 bc">
            <div class="topnav_left">

            </div>
            <div class="topnav_right fr">
                <ul>
                    <li>您好，欢迎来到京西！<?php
                        if(Yii::$app->user->isGuest){
                            echo Html::a('[ 登录 ]',['member/login']);
                            echo Html::a('[ 免费注册 ]',['member/register']);
                        }else{
                            echo Html::a(' 注销登陆 ('.Yii::$app->user->identity->username.') ',['member/logout']);
                        }
                        ?>
                    </li>
                    <li class="line">|</li>
                    <li><?php
                        if(Yii::$app->user->isGuest){
                            echo '我的订单';
                        }else{
                            echo Html::a('我的订单',['order/detail']);
                        }?>
                    </li>
                    <li class="line">|</li>
                    <li>客户服务</li>

                </ul>
            </div>
        </div>
    </div>
    <!-- 顶部导航 end -->
    <div style="clear:both;"></div>
    <!-- 头部 start -->
    <div class="header w1210 bc mt15">

            <?=$content?>
            <!--  商品分类部分 end-->


        <!-- 导购区域 start -->
        <div class="guide w1210 bc mt15">
            <!-- 导购左边区域 start -->
            <div class="guide_content fl">
                <h2>
                    <span class="on">新品上架</span>
                    <span>热卖商品</span>
                    <span>精品推荐</span>
                </h2>

                <div class="guide_wrap">
                    <!-- 疯狂抢购 start-->
                    <div class="crazy">
                        <ul>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/crazy1.jpg')?></a></dt>
                                    <dd><a href="">惠普G4-1332TX 14英寸</a></dd>
                                    <dd><span>售价：</span><strong> ￥2999.00</strong></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/crazy2.jpg')?></a></dt>
                                    <dd><a href="">直降100元！TCL118升冰箱</a></dd>
                                    <dd><span>售价：</span><strong> ￥800.00</strong></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/crazy3.jpg')?></a></dt>
                                    <dd><a href="">康佳液晶37寸电视机</a></dd>
                                    <dd><span>售价：</span><strong> ￥2799.00</strong></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/crazy4.jpg')?></a></dt>
                                    <dd><a href="">梨子平板电脑7.9寸</a></dd>
                                    <dd><span>售价：</span><strong> ￥1999.00</strong></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/crazy5.jpg')?></a></dt>
                                    <dd><a href="">好声音耳机</a></dd>
                                    <dd><span>售价：</span><strong> ￥199.00</strong></dd>
                                </dl>
                            </li>
                        </ul>
                    </div>
                    <!-- 疯狂抢购 end-->

                    <!-- 热卖商品 start -->
                    <div class="hot none">
                        <ul>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/hot1.jpg')?></a></dt>
                                    <dd><a href="">索尼双核五英寸四核手机！</a></dd>
                                    <dd><span>售价：</span><strong> ￥1386.00</strong></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/hot2.jpg')?></a></dt>
                                    <dd><a href="">华为通话平板仅需969元！</a></dd>
                                    <dd><span>售价：</span><strong> ￥969.00</strong></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/hot3.jpg')?></a></dt>
                                    <dd><a href="">卡姿兰明星单品7件彩妆套装</a></dd>
                                    <dd><span>售价：</span><strong> ￥169.00</strong></dd>
                                </dl>
                            </li>
                        </ul>
                    </div>
                    <!-- 热卖商品 end -->

                    <!-- 推荐商品 atart -->
                    <div class="recommend none">
                        <ul>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/recommend1.jpg')?></a></dt>
                                    <dd><a href="">黄飞红麻辣花生整箱特惠装</a></dd>
                                    <dd><span>售价：</span><strong> ￥139.00</strong></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/recommend2.jpg')?></a></dt>
                                    <dd><a href="">戴尔IN1940MW 19英寸LE</a></dd>
                                    <dd><span>售价：</span><strong> ￥679.00</strong></dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt><a href=""><?=Html::img('@web/images/recommend3.jpg')?></a></dt>
                                    <dd><a href="">罗辑思维音频车载CD</a></dd>
                                    <dd><span>售价：</span><strong> ￥24.80</strong></dd>
                                </dl>
                            </li>
                        </ul>
                    </div>
                    <!-- 推荐商品 end -->

                </div>

            </div>
            <!-- 导购左边区域 end -->

            <!-- 侧栏 网站首发 start-->
            <div class="sidebar fl ml10">
                <h2><strong>网站首发</strong></h2>
                <div class="sidebar_wrap">
                    <dl class="first">
                        <dt class="fl"><a href=""><?=Html::img('@web/images/viewsonic.jpg')?></a></dt>
                        <dd><strong><a href="">ViewSonic优派N710 </a></strong> <em>首发</em></dd>
                        <dd>苹果iphone 5免费送！攀高作为全球智能语音血压计领导品牌，新推出的黑金刚高端智能电子血压计，改变传统测量方式让血压测量迈入一体化时代。</dd>
                    </dl>

                    <dl>
                        <dt class="fr"><a href=""><?=Html::img('@web/images/samsung.jpg')?></a></dt>
                        <dd><strong><a href="">Samsung三星Galaxy</a></strong> <em>首发</em></dd>
                        <dd>电视百科全书，360°无死角操控，感受智能新体验！双核CPU+双核GPU+MEMC运动防抖，58寸大屏打造全新视听盛宴！</dd>
                    </dl>
                </div>


            </div>
            <!-- 侧栏 网站首发 end -->

        </div>
        <!-- 导购区域 end -->

        <div style="clear:both;"></div>

        <!--1F 电脑办公 start -->
        <div class="floor1 floor w1210 bc mt10">
            <!-- 1F 左侧 start -->
            <div class="floor_left fl">
                <!-- 商品分类信息 start-->
                <div class="cate fl">
                    <h2>电脑、办公</h2>
                    <div class="cate_wrap">
                        <ul>
                            <li><a href=""><b>.</b>外设产品</a></li>
                            <li><a href=""><b>.</b>鼠标</a></li>
                            <li><a href=""><b>.</b>笔记本</a></li>
                            <li><a href=""><b>.</b>超极本</a></li>
                            <li><a href=""><b>.</b>平板电脑</a></li>
                            <li><a href=""><b>.</b>主板</a></li>
                            <li><a href=""><b>.</b>显卡</a></li>
                            <li><a href=""><b>.</b>打印机</a></li>
                            <li><a href=""><b>.</b>一体机</a></li>
                            <li><a href=""><b>.</b>投影机</a></li>
                            <li><a href=""><b>.</b>路由器</a></li>
                            <li><a href=""><b>.</b>网卡</a></li>
                            <li><a href=""><b>.</b>交换机</a></li>
                        </ul>
                        <p><a href=""><?=Html::img('@web/images/notebook.jpg')?></a></p>
                    </div>


                </div>
                <!-- 商品分类信息 end-->

                <!-- 商品列表信息 start-->
                <div class="goodslist fl">
                    <h2>
                        <span class="on">推荐商品</span>
                        <span>精品</span>
                        <span>热卖</span>
                    </h2>
                    <div class="goodslist_wrap">
                        <div>
                            <ul>
                                <li>
                                    <dl>
                                        <dt><a href=""><?=Html::img('@web/images/hpG4.jpg')?></a></dt>
                                        <dd><a href="">惠普G4-1332TX 14英寸笔</a></dd>
                                        <dd><span>售价：</span> <strong>￥2999.00</strong></dd>
                                    </dl>
                                </li>

                                <li>
                                    <dl>
                                        <dt><a href=""><?=Html::img('@web/images/thinkpad e420.jpg')?></a></dt>
                                        <dd><a href="">ThinkPad E42014英寸笔..</a></dd>
                                        <dd><span>售价：</span> <strong>￥4199.00</strong></dd>
                                    </dl>
                                </li>

                                <li>
                                    <dl>
                                        <dt><a href=""><?=Html::img('@web/images/acer4739.jpg')?></a></dt>
                                        <dd><a href="">宏碁AS4739-382G32Mnk</a></dd>
                                        <dd><span>售价：</span> <strong>￥2799.00</strong></dd>
                                    </dl>
                                </li>

                                <li>
                                    <dl>
                                        <dt><a href=""><?=Html::img('@web/images/samsung6800.jpg')?></a></dt>
                                        <dd><a href="">三星Galaxy Tab P6800.</a></dd>
                                        <dd><span>售价：</span> <strong>￥4699.00</strong></dd>
                                    </dl>
                                </li>

                                <li>
                                    <dl>
                                        <dt><a href=""><?=Html::img('@web/images/lh531.jpg')?></a></dt>
                                        <dd><a href="">富士通LH531 14.1英寸笔记</a></dd>
                                        <dd><span>售价：</span> <strong>￥2189.00</strong></dd>
                                    </dl>
                                </li>

                                <li>
                                    <dl>
                                        <dt><a href=""><?=Html::img('@web/images/qinghuax2.jpg')?></a></dt>
                                        <dd><a href="">清华同方精锐X2笔记本 </a></dd>
                                        <dd><span>售价：</span> <strong>￥2499.00</strong></dd>
                                    </dl>
                                </li>
                            </ul>
                        </div>

                        <div class="none">
                            <ul>
                                <li>
                                    <dl>
                                        <dt><a href=""><?=Html::img('@web/images/hpG4.jpg')?></a></dt>
                                        <dd><a href="">惠普G4-1332TX 14英寸笔</a></dd>
                                        <dd><span>售价：</span> <strong>￥2999.00</strong></dd>
                                    </dl>
                                </li>

                                <li>
                                    <dl>
                                        <dt><a href=""><?=Html::img('@web/images/qinghuax2.jpg')?></a></dt>
                                        <dd><a href="">清华同方精锐X2笔记本 </a></dd>
                                        <dd><span>售价：</span> <strong>￥2499.00</strong></dd>
                                    </dl>
                                </li>

                            </ul>
                        </div>

                        <div class="none">
                            <ul>
                                <li>
                                    <dl>
                                        <dt><a href=""><?=Html::img('@web/images/thinkpad e420.jpg')?></a></dt>
                                        <dd><a href="">ThinkPad E42014英寸笔..</a></dd>
                                        <dd><span>售价：</span> <strong>￥4199.00</strong></dd>
                                    </dl>
                                </li>

                                <li>
                                    <dl>
                                        <dt><a href=""><?=Html::img('@web/images/acer4739.jpg')?></a></dt>
                                        <dd><a href="">宏碁AS4739-382G32Mnk</a></dd>
                                        <dd><span>售价：</span> <strong>￥2799.00</strong></dd>
                                    </dl>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                <!-- 商品列表信息 end-->
            </div>
            <!-- 1F 左侧 end -->

            <!-- 右侧 start -->
            <div class="sidebar fl ml10">
                <!-- 品牌旗舰店 start -->
                <div class="brand">
                    <h2><a href="">更多品牌&nbsp;></a><strong>品牌旗舰店</strong></h2>
                    <div class="sidebar_wrap">
                        <ul>
                            <li><a href=""><?=Html::img('@web/images/dell.gif')?></a></li>
                            <li><a href=""><?=Html::img('@web/images/acer.gif')?></a></li>
                            <li><a href=""><?=Html::img('@web/images/fujitsu.jpg')?></a></li>
                            <li><a href=""><?=Html::img('@web/images/hp.jpg')?></a></li>
                            <li><a href=""><?=Html::img('@web/images/lenove.jpg')?></a></li>
                            <li><a href=""><?=Html::img('@web/images/samsung.gif')?></a></li>
                            <li><a href=""><?=Html::img('@web/images/dlink.gif')?></a></li>
                            <li><a href=""><?=Html::img('@web/images/seagate.jpg')?></a></li>
                            <li><a href=""><?=Html::img('@web/images/intel.jpg')?></a></li>
                        </ul>
                    </div>
                </div>
                <!-- 品牌旗舰店 end -->

                <!-- 分类资讯 start -->
                <div class="info mt10">
                    <h2><strong>分类资讯</strong></h2>
                    <div class="sidebar_wrap">
                        <ul>
                            <li><a href=""><b>.</b>iphone 5s土豪金大量到货</a></li>
                            <li><a href=""><b>.</b>三星note 3低价促销</a></li>
                            <li><a href=""><b>.</b>thinkpad x240即将上市</a></li>
                            <li><a href=""><b>.</b>双十一来临，众商家血拼</a></li>
                        </ul>
                    </div>

                </div>
                <!-- 分类资讯 end -->

                <!-- 广告 start -->
                <div class="ads mt10">
                    <a href=""><?=Html::img('@web/images/canon.jpg')?></a>
                </div>
                <!-- 广告 end -->
            </div>
            <!-- 右侧 end -->

        </div>
        <!--1F 电脑办公 start -->


        <div style="clear:both;"></div>

        <!-- 底部导航 start -->
        <div class="bottomnav w1210 bc mt10">
            <div class="bnav1">
                <h3><b></b> <em>购物指南</em></h3>
                <ul>
                    <li><a href="">购物流程</a></li>
                    <li><a href="">会员介绍</a></li>
                    <li><a href="">团购/机票/充值/点卡</a></li>
                    <li><a href="">常见问题</a></li>
                    <li><a href="">大家电</a></li>
                    <li><a href="">联系客服</a></li>
                </ul>
            </div>

            <div class="bnav2">
                <h3><b></b> <em>配送方式</em></h3>
                <ul>
                    <li><a href="">上门自提</a></li>
                    <li><a href="">快速运输</a></li>
                    <li><a href="">特快专递（EMS）</a></li>
                    <li><a href="">如何送礼</a></li>
                    <li><a href="">海外购物</a></li>
                </ul>
            </div>


            <div class="bnav3">
                <h3><b></b> <em>支付方式</em></h3>
                <ul>
                    <li><a href="">货到付款</a></li>
                    <li><a href="">在线支付</a></li>
                    <li><a href="">分期付款</a></li>
                    <li><a href="">邮局汇款</a></li>
                    <li><a href="">公司转账</a></li>
                </ul>
            </div>

            <div class="bnav4">
                <h3><b></b> <em>售后服务</em></h3>
                <ul>
                    <li><a href="">退换货政策</a></li>
                    <li><a href="">退换货流程</a></li>
                    <li><a href="">价格保护</a></li>
                    <li><a href="">退款说明</a></li>
                    <li><a href="">返修/退换货</a></li>
                    <li><a href="">退款申请</a></li>
                </ul>
            </div>

            <div class="bnav5">
                <h3><b></b> <em>特色服务</em></h3>
                <ul>
                    <li><a href="">夺宝岛</a></li>
                    <li><a href="">DIY装机</a></li>
                    <li><a href="">延保服务</a></li>
                    <li><a href="">家电下乡</a></li>
                    <li><a href="">京东礼品卡</a></li>
                    <li><a href="">能效补贴</a></li>
                </ul>
            </div>
        </div>
        <!-- 底部导航 end -->

        <div style="clear:both;"></div>
        <!-- 底部版权 start -->
        <div class="footer w1210 bc mt10">
            <p class="links">
                <a href="">关于我们</a> |
                <a href="">联系我们</a> |
                <a href="">人才招聘</a> |
                <a href="">商家入驻</a> |
                <a href="">千寻网</a> |
                <a href="">奢侈品网</a> |
                <a href="">广告服务</a> |
                <a href="">移动终端</a> |
                <a href="">友情链接</a> |
                <a href="">销售联盟</a> |
                <a href="">京西论坛</a>
            </p>
            <p class="copyright">
                © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
            </p>
            <p class="auth">
                <a href=""><?=Html::img('@web/images/xin.png')?></a>
                <a href=""><?=Html::img('@web/images/kexin.jpg')?></a>
                <a href=""><?=Html::img('@web/images/police.jpg')?></a>
                <a href=""><?=Html::img('@web/images/beian.gif')?></a>
            </p>
        </div>
        <!-- 底部版权 end -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
