<?php
namespace frontend\components;

use yii\base\Component;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class Sms extends Component
{
    public $app_key;
    public $app_secret;
    public $sign_name;
    public $template_code;
    private $_num;
    private $_param=[];

    //设置手机号码
    public function setTel($num){
        $this->_num = $num;
        return $this;
    }
    //设置短信内容
    public function setParam(array $param){
        $this->_param = $param;
        return $this;
    }
    //设置短信签名
    public function setSign($sign){
        $this->sign_name = $sign;
        return $this;
    }
    //设置短信模版
    public function setTemplate($template){
        $this->template_code = $template;
        return $this;
    }
    //使用大于
    public function send(){
        // 使用方法一
        $client = new Client(new App(['app_key'=>$this->app_key,'app_secret'=>$this->app_secret]));
        $req    = new AlibabaAliqinFcSmsNumSend;
        //$code = rand(100000, 999999);
        $req->setRecNum($this->_num)   //设置发给某用户
        ->setSmsParam($this->_param)
            ->setSmsFreeSignName($this->sign_name)//设置短信签名，必须是已审核的
            ->setSmsTemplateCode($this->template_code);//设置短信模版id  必须审核通过

        $resp = $client->execute($req);
        return $resp;
        //var_dump($resp);
        //var_dump($code);
    }
}