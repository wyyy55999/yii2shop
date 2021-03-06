
<?php
/**
 * @var $this \yii\web\View
 */
//jqzoom 效果
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $(function(){
        $('.jqzoom').jqzoom({
            zoomType: 'standard',
            lens:true,
            preloadImages: false,
            alwaysOn:false,
            title:false,
            zoomWidth:400,
            zoomHeight:400
        });
    })
JS
))
?>
<!-- 导航条部分 start -->
<div class="nav w1210 bc mt10">
    <!--  商品分类部分 start-->
    <div class="category fl cat1">
        <div class="cat_hd off">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，并将cat_bd设置为不显示(加上类none即可)，鼠标滑过时展开菜单则将off类换成on类 -->
            <h2>全部商品分类</h2>
            <em></em>
        </div>

        <div class="cat_bd none">

            <div class="cat item1">
                <h3><a href="">图像、音像、数字商品</a> <b></b></h3>
                <div class="cat_detail none">
                    <dl class="dl_1st">
                        <dt><a href="">电子书</a></dt>
                        <dd>
                            <a href="">免费</a>
                            <a href="">小说</a>
                            <a href="">励志与成功</a>
                            <a href="">婚恋/两性</a>
                            <a href="">文学</a>
                            <a href="">经管</a>
                            <a href="">畅读VIP</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">数字音乐</a></dt>
                        <dd>
                            <a href="">通俗流行</a>
                            <a href="">古典音乐</a>
                            <a href="">摇滚说唱</a>
                            <a href="">爵士蓝调</a>
                            <a href="">乡村民谣</a>
                            <a href="">有声读物</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">音像</a></dt>
                        <dd>
                            <a href="">音乐</a>
                            <a href="">影视</a>
                            <a href="">教育音像</a>
                            <a href="">游戏</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">文艺</a></dt>
                        <dd>
                            <a href="">小说</a>
                            <a href="">文学</a>
                            <a href="">青春文学</a>
                            <a href="">传纪</a>
                            <a href="">艺术</a>
                            <a href="">经管</a>
                            <a href="">畅读VIP</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">人文社科</a></dt>
                        <dd>
                            <a href="">历史</a>
                            <a href="">心理学</a>
                            <a href="">政治/军事</a>
                            <a href="">国学/古籍</a>
                            <a href="">哲学/宗教</a>
                            <a href="">社会科学</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">经管励志</a></dt>
                        <dd>
                            <a href="">经济</a>
                            <a href="">金融与投资</a>
                            <a href="">管理</a>
                            <a href="">励志与成功</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">人文社科</a></dt>
                        <dd>
                            <a href="">历史</a>
                            <a href="">心理学</a>
                            <a href="">政治/军事</a>
                            <a href="">国学/古籍</a>
                            <a href="">哲学/宗教</a>
                            <a href="">社会科学</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">生活</a></dt>
                        <dd>
                            <a href="">烹饪/美食</a>
                            <a href="">时尚/美妆</a>
                            <a href="">家居</a>
                            <a href="">娱乐/休闲</a>
                            <a href="">动漫/幽默</a>
                            <a href="">体育/运动</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">科技</a></dt>
                        <dd>
                            <a href="">科普</a>
                            <a href="">建筑</a>
                            <a href="">IT</a>
                            <a href="">医学</a>
                            <a href="">工业技术</a>
                            <a href="">电子/通信</a>
                            <a href="">农林</a>
                            <a href="">科学与自然</a>
                        </dd>
                    </dl>

                </div>
            </div>

            <div class="cat">
                <h3><a href="">家用电器</a><b></b></h3>
                <div class="cat_detail">
                    <dl class="dl_1st">
                        <dt><a href="">大家电</a></dt>
                        <dd>
                            <a href="">平板电视</a>
                            <a href="">空调</a>
                            <a href="">冰箱</a>
                            <a href="">洗衣机</a>
                            <a href="">热水器</a>
                            <a href="">DVD</a>
                            <a href="">烟机/灶具</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">生活电器</a></dt>
                        <dd>
                            <a href="">取暖器</a>
                            <a href="">加湿器</a>
                            <a href="">净化器</a>
                            <a href="">饮水机</a>
                            <a href="">净水设备</a>
                            <a href="">吸尘器</a>
                            <a href="">电风扇</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">厨房电器</a></dt>
                        <dd>
                            <a href="">电饭煲</a>
                            <a href="">豆浆机</a>
                            <a href="">面包机</a>
                            <a href="">咖啡机</a>
                            <a href="">微波炉</a>
                            <a href="">电磁炉</a>
                            <a href="">电水壶</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">个护健康</a></dt>
                        <dd>
                            <a href="">剃须刀</a>
                            <a href="">电吹风</a>
                            <a href="">按摩器</a>
                            <a href="">足浴盆</a>
                            <a href="">血压计</a>
                            <a href="">体温计</a>
                            <a href="">血糖仪</a>
                        </dd>
                    </dl>

                    <dl>
                        <dt><a href="">五金家装</a></dt>
                        <dd>
                            <a href="">灯具</a>
                            <a href="">LED灯</a>
                            <a href="">水槽</a>
                            <a href="">龙头</a>
                            <a href="">门铃</a>
                            <a href="">电器开关</a>
                            <a href="">插座</a>
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="cat">
                <h3><a href="">手机、数码</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

            <div class="cat">
                <h3><a href="">电脑、办公</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

            <div class="cat">
                <h3><a href="">家局、家具、家装、厨具</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

            <div class="cat">
                <h3><a href="">服饰鞋帽</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

            <div class="cat">
                <h3><a href="">个护化妆</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

            <div class="cat">
                <h3><a href="">礼品箱包、钟表、珠宝</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

            <div class="cat">
                <h3><a href="">运动健康</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

            <div class="cat">
                <h3><a href="">汽车用品</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

            <div class="cat">
                <h3><a href="">母婴、玩具乐器</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

            <div class="cat">
                <h3><a href="">食品饮料、保健食品</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

            <div class="cat">
                <h3><a href="">彩票、旅行、充值、票务</a><b></b></h3>
                <div class="cat_detail none">

                </div>
            </div>

        </div>

    </div>
    <!--  商品分类部分 end-->

    <div class="navitems fl">
        <ul class="fl">
            <li class="current"><a href="">首页</a></li>
            <li><a href="">电脑频道</a></li>
            <li><a href="">家用电器</a></li>
            <li><a href="">品牌大全</a></li>
            <li><a href="">团购</a></li>
            <li><a href="">积分商城</a></li>
            <li><a href="">夺宝奇兵</a></li>
        </ul>
        <div class="right_corner fl"></div>
    </div>
</div>
<!-- 导航条部分 end -->
<!-- 头部 end-->
<!-- 商品页面主体 start -->
<div class="main w1210 mt10 bc">
    <!-- 面包屑导航 start -->
    <div class="breadcrumb">
        <h2>当前位置：<a href="">首页</a> > <a href="">电脑、办公</a> > <a href="">笔记本</a> > ThinkPad X230(23063T4）12.5英寸笔记本</h2>
    </div>
    <!-- 面包屑导航 end -->

    <!-- 主体页面左侧内容 start -->
    <div class="goods_left fl">
        <!-- 相关分类 start -->
        <div class="related_cat leftbar mt10">
            <h2><strong>相关分类</strong></h2>
            <div class="leftbar_wrap">
                <ul>
                    <li><a href="">笔记本</a></li>
                    <li><a href="">超极本</a></li>
                    <li><a href="">平板电脑</a></li>
                </ul>
            </div>
        </div>
        <!-- 相关分类 end -->

        <!-- 热销排行 start -->
        <div class="hotgoods leftbar mt10">
            <h2><strong>热销排行榜</strong></h2>
            <div class="leftbar_wrap">
                <ul>
                    <li></li>
                </ul>
            </div>
        </div>
        <!-- 热销排行 end -->


        <!-- 浏览过该商品的人还浏览了  start 注：因为和list页面newgoods样式相同，故加入了该class -->
        <div class="related_view newgoods leftbar mt10">
            <h2><strong>浏览了该商品的用户还浏览了</strong></h2>
            <div class="leftbar_wrap">
                <ul>
                    <li>
                        <dl>
                            <dt><a href=""><?=\yii\helpers\Html::img('@web/images/relate_view1.jpg')?></a></dt>
                            <dd><a href="">ThinkPad E431(62771A7) 14英寸笔记本电脑 (i5-3230 4G 1TB 2G独显 蓝牙 win8)</a></dd>
                            <dd><strong>￥5199.00</strong></dd>
                        </dl>
                    </li>

                    <li>
                        <dl>
                            <dt><a href=""><?=\yii\helpers\Html::img('@web/images/relate_view2.jpg')?></a></dt>
                            <dd><a href="">ThinkPad X230i(2306-3V9） 12.5英寸笔记本电脑 （i3-3120M 4GB 500GB 7200转 蓝牙 摄像头 Win8）</a></dd>
                            <dd><strong>￥5199.00</strong></dd>
                        </dl>
                    </li>

                    <li>
                        <dl>
                            <dt><a href=""><?=\yii\helpers\Html::img('@web/images/relate_view3.jpg')?></a></dt>
                            <dd><a href="">T联想（Lenovo） Yoga13 II-Pro 13.3英寸超极本 （i5-4200U 4G 128G固态硬盘 摄像头 蓝牙 Win8）晧月银</a></dd>
                            <dd><strong>￥7999.00</strong></dd>
                        </dl>
                    </li>

                    <li>
                        <dl>
                            <dt><a href=""><?=\yii\helpers\Html::img('@web/images/relate_view4.jpg')?></a></dt>
                            <dd><a href="">联想（Lenovo） Y510p 15.6英寸笔记本电脑（i5-4200M 4G 1T 2G独显 摄像头 DVD刻录 Win8）黑色</a></dd>
                            <dd><strong>￥6199.00</strong></dd>
                        </dl>
                    </li>

                    <li class="last">
                        <dl>
                            <dt><a href=""><?=\yii\helpers\Html::img('@web/images/relate_view5.jpg')?></a></dt>
                            <dd><a href="">ThinkPad E530c(33662D0) 15.6英寸笔记本电脑 （i5-3210M 4G 500G NV610M 1G独显 摄像头 Win8）</a></dd>
                            <dd><strong>￥4399.00</strong></dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>
        <!-- 浏览过该商品的人还浏览了  end -->

        <!-- 最近浏览 start -->
        <div class="viewd leftbar mt10">
            <h2><a href="">清空</a><strong>最近浏览过的商品</strong></h2>
            <div class="leftbar_wrap">
                <dl>
                    <dt><a href=""><?=\yii\helpers\Html::img('@web/images/hpG4.jpg')?></a></dt>
                    <dd><a href="">惠普G4-1332TX 14英寸笔记...</a></dd>
                </dl>

                <dl class="last">
                    <dt><a href=""><?=\yii\helpers\Html::img('@web/images/crazy4.jpg')?></a></dt>
                    <dd><a href="">直降200元！TCL正1.5匹空调</a></dd>
                </dl>
            </div>
        </div>
        <!-- 最近浏览 end -->

    </div>
    <!-- 主体页面左侧内容 end -->

    <!-- 商品信息内容 start -->
    <div class="goods_content fl mt10 ml10">
        <!-- 商品概要信息 start -->
        <div class="summary">
            <h3><strong><?=$goods_info->name?></strong></h3>

            <!-- 图片预览区域 start -->
            <div class="preview fl">
                <div class="midpic">
                    <a href="http://admin.yii2shop.com<?=(isset($goods_albums[0])) ? $goods_albums[0]->album_path : ''?>" class="jqzoom" rel="gal1">   <!-- 第一幅图片的大图 class 和 rel属性不能更改 -->
                        <?=(isset($goods_albums[0])) ? \yii\helpers\Html::img('http://admin.yii2shop.com'.$goods_albums[0]->album_path,[
                            'width'=>'350','height'=>'350'
                        ]) : ''?>               <!-- 第一幅图片的中图 -->
                    </a>
                </div>

                <!--使用说明：此处的预览图效果有三种类型的图片，大图，中图，和小图，取得图片之后，分配到模板的时候，把第一幅图片分配到 上面的midpic 中，其中大图分配到 a 标签的href属性，中图分配到 img 的src上。 下面的smallpic 则表示小图区域，格式固定，在 a 标签的 rel属性中，分别指定了中图（smallimage）和大图（largeimage），img标签则显示小图，按此格式循环生成即可，但在第一个li上，要加上cur类，同时在第一个li 的a标签中，添加类 zoomThumbActive  -->

                <div class="smallpic">
                    <a href="javascript:;" id="backward" class="off"></a>
                    <a href="javascript:;" id="forward" class="on"></a>
                    <div class="smallpic_wrap">
                        <ul>
                            <?php
                            foreach ($goods_albums as $k=>$goods_album):
                            ?>
                            <li <?=($k==0) ? 'class="cur"': ''?>>
                                <a <?=($k==0) ? 'class="zoomThumbActive"': ''?> href="javascript:void(0);" rel="{gallery: 'gal1', smallimage: 'http://admin.yii2shop.com<?=$goods_album->album_path?>',largeimage: 'http://admin.yii2shop.com<?=$goods_album->album_path?>'}"><?=\yii\helpers\Html::img('http://admin.yii2shop.com'.$goods_album->album_path)?></a>
                            </li>
                            <?php endforeach;   ?>

                        </ul>
                    </div>

                </div>
            </div>
            <!-- 图片预览区域 end -->

            <!-- 商品基本信息区域 start -->
            <div class="goodsinfo fl ml10">
                <ul>
                    <li><span>商品编号： </span><?=$goods_info->sn?></li>
                    <li class="market_price"><span>定价：</span><em>￥<?=$goods_info->market_price?></em></li>
                    <li class="shop_price"><span>本店价：</span> <strong>￥<?=$goods_info->shop_price?></strong> <a href="">(降价通知)</a></li>
                    <li><span>上架时间：</span><?=date('Y-m-d',$goods_info->create_time)?></li>
                    <li class="star"><span>商品评分：</span> <strong></strong><a href="">(已有21人评价)</a></li> <!-- 此处的星级切换css即可 默认为5星 star4 表示4星 star3 表示3星 star2表示2星 star1表示1星 -->
                </ul>
                <form action="<?=\yii\helpers\Url::to(['goods/add-cart'])?>" method="post" class="choose">
                    <ul>

                        <li>
                            <dl>
                                <dt>购买数量：</dt>
                                <dd>
                                    <a href="javascript:;" id="reduce_num"></a>
                                    <input type="hidden" name="goods_id" value="<?=$goods_info->id?>">
                                    <input type="hidden" name="_csrf-frontend" id="_csrf-frontend" value="<?=Yii::$app->request->csrfToken?>">
                                    <input type="text" name="amount" value="1" class="amount"/>
                                    <a href="javascript:;" id="add_num"></a>
                                </dd>
                            </dl>
                        </li>

                        <li>
                            <dl>
                                <dt>&nbsp;</dt>
                                <dd>
                                    <input type="submit" value="" class="add_btn" />
                                </dd>
                            </dl>
                        </li>

                    </ul>
                </form>
            </div>
            <!-- 商品基本信息区域 end -->
        </div>
        <!-- 商品概要信息 end -->

        <div style="clear:both;"></div>

        <!-- 商品详情 start -->
        <div class="detail">
            <div class="detail_hd">
                <ul>
                    <li class="first"><span>商品介绍</span></li>
                    <li class="on"><span>商品评价</span></li>
                    <li><span>售后保障</span></li>
                </ul>
            </div>
            <div class="detail_bd">
                <!-- 商品介绍 start -->
                <div class="introduce detail_div none">
                    <div class="attr mt15">
                        <ul>
                            <li><span>商品名称：</span><?=$goods_info->name?></li>
                            <li><span>商品编号：</span><?=$goods_info->sn?></li>
                            <li><span>品牌：</span><?=$goods_info->goodsBrand->name?></li>
                            <li><span>上架时间：</span><?=date('Y-m-d H:i:s',$goods_info->create_time)?></li>
                            <li><span>商品毛重：</span>2.47kg</li>
                            <li><span>商品产地：</span>中国大陆</li>
                            <li><span>显卡：</span>集成显卡</li>
                            <li><span>触控：</span>非触控</li>
                            <li><span>厚度：</span>正常厚度（>25mm）</li>
                            <li><span>处理器：</span>Intel i5</li>
                            <li><span>尺寸：</span>12英寸</li>
                        </ul>
                    </div>

                    <div class="desc mt10">
                        <!-- 此处的内容 一般是通过在线编辑器添加保存到数据库，然后直接从数据库中读出 -->
                        <?=\yii\helpers\Html::img('@web/images/desc1.jpg')?>
                        <p style="height:10px;"></p>
                        <?=\yii\helpers\Html::img('@web/images/desc2.jpg')?>
                        <p style="height:10px;"></p>
                        <?=\yii\helpers\Html::img('@web/images/desc3.jpg')?>
                        <p style="height:10px;"></p>
                        <?=\yii\helpers\Html::img('@web/images/desc4.jpg')?>
                        <p style="height:10px;"></p>
                        <?=\yii\helpers\Html::img('@web/images/desc5.jpg')?>
                        <p style="height:10px;"></p>
                        <?=\yii\helpers\Html::img('@web/images/desc6.jpg')?>
                        <p style="height:10px;"></p>
                        <?=\yii\helpers\Html::img('@web/images/desc7.jpg')?>
                        <p style="height:10px;"></p>
                        <?=\yii\helpers\Html::img('@web/images/desc8.jpg')?>
                        <p style="height:10px;"></p>
                        <?=\yii\helpers\Html::img('@web/images/desc9.jpg')?>
                    </div>
                </div>
                <!-- 商品介绍 end -->

                <!-- 商品评论 start -->
                <div class="comment detail_div mt10">
                    <div class="comment_summary">
                        <div class="rate fl">
                            <strong><em>90</em>%</strong> <br />
                            <span>好评度</span>
                        </div>
                        <div class="percent fl">
                            <dl>
                                <dt>好评（90%）</dt>
                                <dd><div style="width:90px;"></div></dd>
                            </dl>
                            <dl>
                                <dt>中评（5%）</dt>
                                <dd><div style="width:5px;"></div></dd>
                            </dl>
                            <dl>
                                <dt>差评（5%）</dt>
                                <dd><div style="width:5px;" ></div></dd>
                            </dl>
                        </div>
                        <div class="buyer fl">
                            <dl>
                                <dt>买家印象：</dt>
                                <dd><span>屏幕大</span><em>(1953)</em></dd>
                                <dd><span>外观漂亮</span><em>(786)</em></dd>
                                <dd><span>系统流畅</span><em>(1091)</em></dd>
                                <dd><span>功能齐全</span><em>(1109)</em></dd>
                                <dd><span>反应快</span><em>(659)</em></dd>
                                <dd><span>分辨率高</span><em>(824)</em></dd>
                            </dl>
                        </div>
                    </div>

                    <div class="comment_items mt10">
                        <div class="user_pic">
                            <dl>
                                <dt><a href=""><?=\yii\helpers\Html::img('@web/images/user1.gif')?></a></dt>
                                <dd><a href="">乖乖</a></dd>
                            </dl>
                        </div>
                        <div class="item">
                            <div class="title">
                                <span>2013-03-11 22:18</span>
                                <strong class="star star5"></strong> <!-- star5表示5星级 start4表示4星级，以此类推 -->
                            </div>
                            <div class="comment_content">
                                <dl>
                                    <dt>心得：</dt>
                                    <dd>东西挺好，挺满意的！</dd>
                                </dl>
                                <dl>
                                    <dt>优点：</dt>
                                    <dd>反应速度开，散热性能好</dd>
                                </dl>
                                <dl>
                                    <dt>不足：</dt>
                                    <dd>暂时还没发现缺点哦！</dd>
                                </dl>
                                <dl>
                                    <dt>购买日期：</dt>
                                    <dd>2013-11-24</dd>
                                </dl>
                            </div>
                            <div class="btns">
                                <a href="" class="reply">回复(0)</a>
                                <a href="" class="useful">有用(0)</a>
                            </div>
                        </div>
                        <div class="cornor"></div>
                    </div>

                    <div class="comment_items mt10">
                        <div class="user_pic">
                            <dl>
                                <dt><a href=""><?=\yii\helpers\Html::img('@web/images/user2.jpg')?></a></dt>
                                <dd><a href="">小宝贝</a></dd>
                            </dl>
                        </div>
                        <div class="item">
                            <div class="title">
                                <span>2013-10-01 14:10</span>
                                <strong class="star star4"></strong> <!-- star5表示5星级 start4表示4星级，以此类推 -->
                            </div>
                            <div class="comment_content">
                                <dl>
                                    <dt>心得：</dt>
                                    <dd>外观漂亮同，还在使用过程中。</dd>
                                </dl>
                                <dl>
                                    <dt>型号：</dt>
                                    <dd>i5 8G内存版</dd>
                                </dl>
                                <dl>
                                    <dt>购买日期：</dt>
                                    <dd>2013-11-20</dd>
                                </dl>
                            </div>
                            <div class="btns">
                                <a href="" class="reply">回复(0)</a>
                                <a href="" class="useful">有用(0)</a>
                            </div>
                        </div>
                        <div class="cornor"></div>
                    </div>

                    <div class="comment_items mt10">
                        <div class="user_pic">
                            <dl>
                                <dt><a href=""><?=\yii\helpers\Html::img('@web/images/user3.jpg')?></a></dt>
                                <dd><a href="">天使</a></dd>
                            </dl>
                        </div>
                        <div class="item">
                            <div class="title">
                                <span>2013-03-11 22:18</span>
                                <strong class="star star5"></strong> <!-- star5表示5星级 start4表示4星级，以此类推 -->
                            </div>
                            <div class="comment_content">
                                <dl>
                                    <dt>心得：</dt>
                                    <dd>挺好的，物超所值，速度挺好，WIN8用起来也不错。</dd>
                                </dl>
                                <dl>
                                    <dt>优点：</dt>
                                    <dd>散热很好，配置不错</dd>
                                </dl>
                                <dl>
                                    <dt>不足：</dt>
                                    <dd>暂时还没发现缺点哦！</dd>
                                </dl>
                                <dl>
                                    <dt>购买日期：</dt>
                                    <dd>2013-11-24</dd>
                                </dl>
                            </div>
                            <div class="btns">
                                <a href="" class="reply">回复(0)</a>
                                <a href="" class="useful">有用(0)</a>
                            </div>
                        </div>
                        <div class="cornor"></div>
                    </div>

                    <!-- 分页信息 start -->
                    <div class="page mt20">
                        <a href="">首页</a>
                        <a href="">上一页</a>
                        <a href="">1</a>
                        <a href="">2</a>
                        <a href="" class="cur">3</a>
                        <a href="">4</a>
                        <a href="">5</a>
                        <a href="">下一页</a>
                        <a href="">尾页</a>
                    </div>
                    <!-- 分页信息 end -->

                    <!--  评论表单 start-->
                    <div class="comment_form mt20">
                        <form action="">
                            <ul>
                                <li>
                                    <label for=""> 评分：</label>
                                    <input type="radio" name="grade"/> <strong class="star star5"></strong>
                                    <input type="radio" name="grade"/> <strong class="star star4"></strong>
                                    <input type="radio" name="grade"/> <strong class="star star3"></strong>
                                    <input type="radio" name="grade"/> <strong class="star star2"></strong>
                                    <input type="radio" name="grade"/> <strong class="star star1"></strong>
                                </li>

                                <li>
                                    <label for="">评价内容：</label>
                                    <textarea name="" id="" cols="" rows=""></textarea>
                                </li>
                                <li>
                                    <label for="">&nbsp;</label>
                                    <input type="submit" value="提交评论"  class="comment_btn"/>
                                </li>
                            </ul>
                        </form>
                    </div>
                    <!--  评论表单 end-->

                </div>
                <!-- 商品评论 end -->

                <!-- 售后保障 start -->
                <div class="after_sale mt15 none detail_div">
                    <div>
                        <p>本产品全国联保，享受三包服务，质保期为：一年质保 <br />如因质量问题或故障，凭厂商维修中心或特约维修点的质量检测证明，享受7日内退货，15日内换货，15日以上在质保期内享受免费保修等三包服务！</p>
                        <p>售后服务电话：800-898-9006 <br />品牌官方网站：http://www.lenovo.com.cn/</p>

                    </div>

                    <div>
                        <h3>服务承诺：</h3>
                        <p>本商城向您保证所售商品均为正品行货，京东自营商品自带机打发票，与商品一起寄送。凭质保证书及京东商城发票，可享受全国联保服务（奢侈品、钟表除外；奢侈品、钟表由本商城联系保修，享受法定三包售后服务），与您亲临商场选购的商品享受相同的质量保证。本商城还为您提供具有竞争力的商品价格和运费政策，请您放心购买！</p>

                        <p>注：因厂家会在没有任何提前通知的情况下更改产品包装、产地或者一些附件，本司不能确保客户收到的货物与商城图片、产地、附件说明完全一致。只能确保为原厂正货！并且保证与当时市场上同样主流新品一致。若本商城没有及时更新，请大家谅解！</p>

                    </div>

                    <div>
                        <h3>权利声明：</h3>
                        <p>本商城上的所有商品信息、客户评价、商品咨询、网友讨论等内容，是京东商城重要的经营资源，未经许可，禁止非法转载使用。</p>
                        <p>注：本站商品信息均来自于厂商，其真实性、准确性和合法性由信息拥有者（厂商）负责。本站不提供任何保证，并不承担任何法律责任。</p>

                    </div>
                </div>
                <!-- 售后保障 end -->

            </div>
        </div>
        <!-- 商品详情 end -->


    </div>
    <!-- 商品信息内容 end -->


</div>
<!-- 商品页面主体 end -->