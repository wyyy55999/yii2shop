<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <?php
            $form = \yii\widgets\ActiveForm::begin(
                ['fieldConfig'=>[
                    'options'=>[
                        'tag'=>'li'
                    ],
                    'errorOptions'=>[
                        'tag'=>'p'
                    ],
                ]]);
            echo '<ul>';
            echo $form->field($member_reg,'username')->textInput(['class'=>'txt'])->label('用户名：');
            echo $form->field($member_reg,'password')->passwordInput(['class'=>'txt'])->label('密码：');
            echo $form->field($member_reg,'repassword')->passwordInput(['class'=>'txt'])->label('确认密码：');
            echo $form->field($member_reg,'email')->textInput(['class'=>'txt'])->label('邮箱：');
            echo $form->field($member_reg,'tel')->textInput(['class'=>'txt'])->label('手机号码：');
            $button = '<input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>';
            echo $form->field($member_reg,'sms_code',['template'=>"{label}\n{input} $button\n{hint}\n{error}"])->textInput(['class'=>'txt','placeholder'=>'请输入短信验证码','id'=>'captcha','style'=>'height: 25px;padding:3px 8px'])->label('验证码：');
            echo $form->field($member_reg,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['template'=>'{input}{image}'])->label('验证码：');
        echo '<li>
                    <label for="">&nbsp;</label>
                    <input type="checkbox" class="chb" checked="checked" id="member-read_agree"/> 我已阅读并同意《用户注册协议》
                </li>';
            /*$txt = "我已阅读并同意《用户注册协议》";
            echo $form->field($member_reg,'read_agree',['template'=>"{label}{input} $txt {hint}{error}"])->checkbox(['style'=>'float:left;margin-left:50px;'])->label(false,['class'=>'read_agree_label','style'=>'width:100px!important;line-height:32px']);*/
            echo '<li>
                    <label for="" style="clear: both;">&nbsp;</label>
                    <input type="submit" value="" class="login_btn" id="submit_button" disabled="disabled"/>
                </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>
        </div>
        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>
    </div>
</div>
<!-- 登录主体部分end -->
<script type="text/javascript">
    function bindPhoneNum(){
        //启用输入框
        $('#captcha').prop('disabled',false);
        //正则
        var pattern = /^1[34578]\d{9}$/;
        if($('#member-tel').val() == ''){
            alert('请输入手机号');
            return;
        }else {
            if(!pattern.test($('#member-tel').val())){
                alert('请输入有效的手机号');
                return;
            }
        }
        var time=60;
        var interval = setInterval(function(){
            time--;
            if(time<=0){
                clearInterval(interval);
                var html = '获取验证码';
                $('#get_captcha').prop('disabled',false);
            } else{
                var html = time + ' 秒后再次获取';
                $('#get_captcha').prop('disabled',true);
            }

            $('#get_captcha').val(html);
        },1000);
    }
</script>
<?php
/**
 * @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(['member/send-sms']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $('#get_captcha').click(function() {
        //获取用户填写的手机号
        var tel = $('#member-tel').val();
        //将手机号提交到后台
        $.post('$url',{tel:tel},function(response) {
            if(response.msg == 'success'){
                console.debug(response);
            }else {
                console.debug(544545454);
            }
        },'json');
    });
    //设置一开始进网页时阅读协议为未选中状态
    $('#member-read_agree').attr('checked',false);
    //如果选中了，就将disabled改为false  也就是可以点击
    if($('#member-read_agree').is(':checked')){
        $('#submit_button').prop('disabled',false);
    }
    $('#member-read_agree').on('click',function() {
        if($('#member-read_agree').is(':checked')){
            $('#submit_button').prop('disabled',false);
        //如果没有选中，就将disabled改为true  也就是不可以点击
        }else {
            $('#submit_button').prop('disabled',true);
        }
    })
JS
));
?>