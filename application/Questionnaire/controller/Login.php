<?php
namespace app\index\controller;
use think\Db;
use app\index\model\User as UserModel;
class Login extends \think\Controller
{
  /**
     * @登录、注册
     */
    public function index() {
      $UserModel = new UserModel;
      $back   = cookie('url_back');  //返回地址
      $url_back  = isset($back) ? $back : "http://m.bxgogo.com/index/user/index";
     
      if (request()->isAjax()) {
         $mobile   = input('mobile');  //用户名
         $password  = input('pass');  //用户密码
         // 验证数据
             // 登录
            $user = $UserModel->login($mobile, $password, true);
            if ($user) {
                cookie('url_back', null);
                return json(['flag' =>1,'msg' => '登录成功','back'=>$url_back]);
            } else {
                return json(['flag' =>0,'msg' => $UserModel->errMsg]);
            }
        } else {
            return $UserModel->isLogin() ? $this->herurl('http://m.bxgogo.com/index/user/index') : $this->fetch();
        }
   }
     /**
     * @登录、注册
     */
    public function reg() {
      if (request()->isAjax()) {
         $mobile   = input('mobile');  //用户名
         $Tracy    = input('mesCode');  //验证码
         $password=baserand();//登录密码
         $pass =encryt_data(json_encode(['uid'=>$mobile,'pass'=>$password]),config('PrivateKeyFilePath')); //密码
         // 验证数据
              if(empty($mobile) && empty($Tracy)){//注册
                    return json(['flag' =>0,'msg' => '手机号或验证码不能为空']);
              }
              if($Tracy !==cookie('mesCode')){
                    return json(['flag' =>0,'msg' => '验证码输入错误']);
              }
              $db=Db::name('user');
              $user=$db->where('uid',$mobile)->field('uid')->find(); 
               if ($user) {
                     return json(['flag' =>0,'msg' => '用户已存在']); 
                }
                $db->insert(['uid'=>$mobile,'pass'=>$pass,'logtime'=>time(),'mobile'=>$mobile]);
				$dateuser=Db::name('bankrollnd')->where('uid',$mobile)->find();
                if(empty($dateuser)){
                   Db::name('bankrollnd')->insert(['uid'=>$mobile]);
                }
	            Db::name('power')->insert(['uid'=>$mobile,'power'=>1,'powername'=>'普通会员']);
	         //发送信息
	           $obj = & load_alipay('Aliyunsend');
	          $obj->send($mobile,['uid'=>$mobile,'pass'=>$password],'SMS_49285066');
	          $user=Db::name('user')->where('uid',$mobile)->find(); 
	        //自动登录
	         $UserModel = new UserModel;
	         $uid = $UserModel->autoLogin($user, true);
                if ($uid) {
                     return json(['flag' =>1,'msg' => "登录成功"]);
                } else {
                     return json(['flag' =>0,'msg' => '登录失败']);
                }
        } else {
            return json(['flag' =>0,'msg' => '非法数据提交']);
        }
   }
  /**
     * 成功跳转
     */
    public function herurl($url) {
       header("location:$url");
    exit;
   }
   /**
     * 退出登录
     */
    public function loginout()
    {
        session(null);
      cookie('is_tuichu','1');
        cookie('user_auth', null);
        cookie('signin_token', null);
        return $this->redirect('http://login.bxgogo.com/index/index/loginout');
    }

   /**
     * //发送验证码
     */
  public function sendco() {
    $mobile=input('mobile');
    if(request()->isAjax() && !empty($mobile)){
	    $code=baserand();//获取随机数
       $user=Db::name('user')->where('uid',$mobile)->field('uid')->find(); 
       if ($user) {
          return json(['flag' =>0,'msg' => '用户已存在']); 
       }
	    cookie('mesCode',$code,600);
	    $obj = & load_alipay('Aliyunsend');
	    $result = $obj->send($mobile,['code'=>$code,'product'=>'百信购'],'SMS_44440686');
         // 接口成功的处理
	   if($result !== true){
		return json(['flag' =>0,'msg' => $obj->errMsg]);
	   }else{
		return json(['flag' =>1,'msg' => "短信下发成功"]);
	   }
     }
   }
      /**
     * 微信登录
     */
    public function wxlogin() {
        $callback="http://m.bxgogo.com";
    $state="bxgogo";
    $scope="snsapi_userinfo";
        // SDK实例对象
        $oauth = & load_wechat('Oauth');
    $result = $oauth->getOauthRedirect($callback, $state,$scope);
         // 接口成功的处理
     dump($result);
    }
      /**
     * 微信登录开放平台
     */
    public function openwxlogin() {
        $callback="http://login.bxgogo.com/index/index/openwx_url";
         $state="bxgogo";
        // SDK实例对象
        $oauth = & load_wechat('Oauth');
       $result = $oauth->getOauth($callback, $state);
    }
     /**
     * 微信同步通知页面
     */
    public function openwx_url() {
      
    }
     /**
     * 支付宝登陆
     */
    public function alipayoauth() {
        // SDK实例对象
        $obj = & load_alipay('Aliyunoauth');
         $result = $obj->oauth(['return_url'=>'http://login.bxgogo.com/index/index/alipay_url']);//支付宝处理完请求后，当前页面自动跳转到商户网站里指定页面的http路径
    }
    /**
     * 支付宝服务器同步通知页面路径
     */
    public function alipay_url() {
        // SDK实例对象
        $obj = & load_alipay('Alipaynotify');
        $result = $obj->verifyNotify(input());
       if ($result) {//验证成功.
            $user_id= input('user_id'); //Y用户账号
            $token= input('token'); //授权token
            if (input('is_success') !== 'T' && empty($user_id)) {
                return '数据异常';
            }
              $user=Db::name('user')->where('user_id',$user_id)->where('token',$token)->find(); 
              if($user){
                //自动登录
	            $UserModel = new UserModel;
	            $uid = $UserModel->autoLogin($user, true);
                  if ($uid) {
                         return $this->herurl(cookie('url_back ')) ;
                   } else {
                        return json(['flag' =>0,'msg' => '登录失败']);
                   }
              }else{
                     return '此支付宝未绑定账号';
                }
        } else {
            echo "验证失败";
        }
    }
}
