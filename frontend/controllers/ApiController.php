<?php
namespace frontend\controllers;


use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Member;
use frontend\models\MemberLoginForm;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    //关闭跨站攻击
    public $enableCsrfValidation = false;
    //设置响应头
    public function init()
    {
        //相当于设置响应头为json格式
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }
    //会员注册
    public function actionUserRegister(){
        if(\Yii::$app->request->isPost){
            $member_model = new Member();
            $request = \Yii::$app->request;
            if($member_model->validate()){
                $member_model->username = $request->post('username');
                $member_model->password_hash = \Yii::$app->security->generatePasswordHash($request->post('password'));
                $member_model->email = $request->post('email');
                $member_model->tel = $request->post('tel');
                if($member_model->save(false)){
                return ['status'=>1,'msg'=>'注册成功'];
                }else{
                    return ['status'=>0,'msg'=>'注册失败'];
                }
            }else{
                return ['status'=>'-1','msg'=>$member_model->getErrors()];
            }
        }else{
            return ['status'=>'-1','msg'=>'请求方式有误'];
        }
    }
    //会员登录
    public function actionUserLogin(){
        if(\Yii::$app->request->isPost) {
            $member_model = new MemberLoginForm();
            //if ($member_model->validate()) {
                //获取username
                $username = \Yii::$app->request->post('username');
                //判断是否存在该username
                $member = Member::findOne(['username' => $username]);
                if ($member != null) {
                    //存在则匹配密码
                    if (\Yii::$app->security->validatePassword(\Yii::$app->request->post('password'), $member->password_hash)) {
                        //匹配成功登录并显示所有信息
                        \Yii::$app->user->login($member);
                        //最后登陆时间和ip
                        $member->last_login_time = time();
                        $member->last_login_ip = \Yii::$app->request->userIP;
                        $member->save(false);
                        return ['status' => 1, 'msg' => '登录成功', 'data' => $member];
                    } else {
                        //匹配失败
                        return ['status' => 0, 'msg' => '密码错误'];
                    }
                } else {
                    return ['status' => '-1', 'msg' => '用户名错误'];
                }
            //}
        }else{
            return ['status'=>'-1','msg'=>'请求方式错误'];
        }
    }
    //修改密码
    public function actionUpdatePwd(){
        if(\Yii::$app->request->isPost){
            //判断旧密码是否正确
            $old_pwd_model = Member::findOne(['id'=>\Yii::$app->user->id]);
            //判断新密码和旧密码是否一致
            if(\Yii::$app->security->validatePassword(\Yii::$app->request->post('old_password'),$old_pwd_model->password_hash)){
                if(\Yii::$app->request->post('password') === \Yii::$app->request->post('repassword')){
                    //一致就修改
                    $old_pwd_model->password_hash = \Yii::$app->security->generatePasswordHash(\Yii::$app->request->post('password'));
                    if ($old_pwd_model->save(false)){
                        return ['status'=>1,'msg'=>'修改密码成功'];
                    }else{
                        return ['status'=>0,'msg'=>'修改密码失败'];
                    }
                }else{
                    return ['status'=>'-1','msg'=>'两次输入的密码不一致'];
                }
            }else{
                return ['status'=>'-1','msg'=>'密码错误'];
            }
        }
    }
    //获取当前登录的用户信息
    public function actionGetCurrentUserInfo(){
        if($cur_member = \Yii::$app->user->identity){
            return ['status'=>1,'msg'=>'获取成功','data'=>$cur_member];
        }else{
            return ['status'=>0,'msg'=>'获取失败'];
        }
    }
    //注销登录
    public function actionUserLogout(){
        if(\Yii::$app->user->logout()){
            return ['status'=>1,'msg'=>'注销成功'];
        }else{
            return ['status'=>0,'msg'=>'注销失败'];
        }
    }
    //添加地址
    public function actionAddressAdd(){
        if(\Yii::$app->request->isPost){
            $address_model = new Address();
            $request = \Yii::$app->request;
            //if($address_model->validate()){
                $address_model->consignee = $request->post('consignee');
                $address_model->province = $request->post('province');
                $address_model->city = $request->post('city');
                $address_model->area = $request->post('area');
                $address_model->detail_address = $request->post('detail_address');
                $address_model->tel = $request->post('tel');
                $address_model->is_default = $request->post('is_default');
                $address_model->member_id = \Yii::$app->user->id;
                if($address_model->AddSave()){
                    if($address_model->save(false)){
                        return ['status'=>1,'msg'=>'添加地址成功'];
                    }else{
                        return ['status'=>0,'msg'=>'添加地址失败'];
                    }
                }
            //}
        }
    }
    //修改地址
    public function actionAddressUpdate(){

    }
    //删除地址
    public function actionAddressDelete(){
        if($address_id = \Yii::$app->request->get('address_id')){
            $address = Address::findOne(['id'=>$address_id,'member_id'=>\Yii::$app->user->id]);
            if($address->delete()){
                return ['status'=>1,'msg'=>'删除地址成功'];
            }else{
                return ['status'=>0,'msg'=>'删除地址失败'];
            }
        }
    }
    //地址列表
    public function actionAddressList(){
        $addresses = Address::findAll(['member_id'=>\Yii::$app->user->id]);
        if($addresses != null){
            return ['status' => 1, 'msg' => '', 'data' => $addresses];
        }else{
            return ['status' => 0, 'msg' => '暂无地址信息'];
        }
    }
    //获取所有商品分类
    public function actionGoodsCategoryList(){
        $goods_cates = GoodsCategory::find()->all();
        if($goods_cates != null){
            return ['status' => 1, 'msg' => '', 'data' => $goods_cates];
        }else{
            return ['status' => 0, 'msg' => '暂无分类信息'];
        }
    }
    //获取某分类的所有子分类
    public function actionGetSonCates(){
        if($goods_cate_id = \Yii::$app->request->get('goods_cate_id')){
            //找到该条记录
            $goods_cate = GoodsCategory::findOne(['id'=>$goods_cate_id]);
            //查询左值大于他的且右值小于他的数据
            $son_cates = GoodsCategory::find()->where("tree={$goods_cate->tree}")->andWhere("rgt<{$goods_cate->rgt}")->andWhere("lft>{$goods_cate->lft}")->all();
            if($son_cates != null){
                return ['status' => 1, 'msg' => '', 'data' => $son_cates];
            }else{
                return ['status' => 0, 'msg' => '暂无子分类信息'];
            }
        }
    }
    //获取某分类的父分类
    public function actionGetParentCate(){
        if($goods_cate_id = \Yii::$app->request->get('goods_cate_id')){
            $goods_cate_info = GoodsCategory::findOne(['id'=>$goods_cate_id]);
            $parent_cate = GoodsCategory::findOne(['id'=>$goods_cate_info->parent_id]);
            if($parent_cate != null){
                return ['status' => 1, 'msg' => '', 'data' => $parent_cate];
            }else{
                return ['status' => 0, 'msg' => '未获取到父分类信息'];
            }
        }
    }
    //获取某分类下面的所有商品
    public function actionGetGoodsByCategory(){
        if($goods_cate_id = \Yii::$app->request->get('goods_cate_id')){
            $goodses = Goods::find()->where('goods_category_id='.$goods_cate_id)->all();
            if($goodses != null){
                return ['status' => 1, 'msg' => '', 'data' => $goodses];
            }else{
                return ['status' => 0, 'msg' => '该分类下暂无商品'];
            }
        }
    }
    //获取某品牌下面的所有商品
    public function actionGetGoodsByBrand(){
        if($brand_id = \Yii::$app->request->get('brand_id')){
            $goods = Goods::findAll(['brand_id'=>$brand_id]);
            if($goods != null){
                return ['status' => 1, 'msg' => '', 'data' => $goods];
            }else{
                return ['status' => 0, 'msg' => '该品牌下暂无商品'];
            }
        }
    }
    //获取文章分类
    public function actionGetArticleCategory(){
        $article_cates = ArticleCategory::find()->all();
        if($article_cates != null){
            return ['status' => 1, 'msg' => '', 'data' => $article_cates];
        }else{
            return ['status' => 0, 'msg' => '暂无文章分类'];
        }
    }
    //获取某分类下面的所有文章
    public function actionGetArticleByArticleCate(){
        if($article_category_id = \Yii::$app->request->get('article_category_id')){
            $articles = Article::findAll(['article_category_id'=>$article_category_id]);
            if($articles != null){
                return ['status' => 1, 'msg' => '', 'data' => $articles];
            }else{
                return ['status' => 0, 'msg' => '该品牌下暂无商品'];
            }
        }
    }
    //获取某文章所属分类
    public function actionGetArticleCateByArticle(){
        if($article_id = \Yii::$app->request->get('article_id')){
            //获取该文章的信息
            $article = Article::findOne(['id'=>$article_id]);
            //查找分类
            $article_cate = ArticleCategory::findOne(['id'=>$article->article_category_id]);
            if($article_cate != null){
                return ['status' => 1, 'msg' => '', 'data' => $article_cate];
            }else{
                return ['status' => 0, 'msg' => '未查找到文章所属分类'];
            }
        }
    }
}