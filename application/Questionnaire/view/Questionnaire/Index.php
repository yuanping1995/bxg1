<?php
namespace app\login\controller;
use think\Db;
use app\index\model\User as UserModel;
class Index extends \think\Controller
{

  /**
     * @登录、注册
     */
    public function index() {

    // if(!empty(cookie('is_tuichu')))
      //{
       	session(null);
        cookie('user_auth', null);
        cookie('signin_token', null);
         cookie('is_tuichu',null);
    // }
	$UserModel = new UserModel;
	$back   = cookie('url_back');  //返回地址
      if(!empty(input('tuijian'))){
       
      	 $type=1;
      }else{
      	 	 $type=0;
      }

      $url_back  = isset($back) ? $back : "http://m.bxgogo.com/index/user/index";

      if (request()->isAjax()) {
         $mobile   = input('mobile');  //用户名
         $password  = input('pass');  //用户密码
         // 验证数据
             // 登录
            $user = $UserModel->login($mobile, $password, true);
            if ($user) {
              $dateuser=Db::name('bankrollnd')->where('uid',$mobile)->find();
              if(empty($dateuser)){
                Db::name('bankrollnd')->insert(['uid'=>$mobile]);
              }
              
                cookie('url_back', null);
                return json(['flag' =>1,'msg' => '登录成功','back'=>$url_back]);
            } else {
                return json(['flag' =>0,'msg' => $UserModel->errMsg]);
            }
        } else {
        	  $this->assign('type',$type);
            $this->assign('tuijian',input('tuijian'));
                if( $UserModel->isLogin())
                {

                 header("Location:http://m.bxgogo.com/index/user/index"); 
                  die;
                }else{
                 return  $this->fetch();
                }
           
        }
      
   }
  // public function mimaoklogin(){
	  // header('Access-Control-Allow-Origin:*');
	//  $UserModel = new UserModel;
	 // $back   = cookie('url_back');  //返回地址
      //if(input('tuijian')){
     // 	 $type=1;
    //  }else{
    //  	 	 $type=0;
    //  }

    //  $url_back  = isset($back) ? $back : "http://m.bxgogo.com/index/user/index";

	 //  $mobile   = input('mobile');  //用户名
    //     $password  = input('pass');  //用户密码
         // 验证数据
             // 登录
    //        $user = $UserModel->login($mobile, $password, true);
     //       if ($user) {
     //         $dateuser=Db::name('bankrollnd')->where('uid',$mobile)->find();
     //         if(empty($dateuser)){
      //          Db::name('bankrollnd')->insert(['uid'=>$mobile]);
      //        }
              
      //          cookie('url_back', null);
      //          return json(['flag' =>1,'msg' => '登录成功','back'=>$url_back]);
     //       } else {
      //          return json(['flag' =>0,'msg' => $UserModel->errMsg]);
      //      }
   //}
   	function tuijiantime(){
		return true;
		//员工推荐 是否在指定时间内
		$file_path = "tuijian.txt";
		if(file_exists($file_path)){
			$str = file_get_contents($file_path);
			$str = str_replace("\r\n","-时间间隔符-",$str);
			$arr = explode('-时间间隔符-',$str);
			foreach($arr as $value)
			{
				$time = explode('_',$value);
				if(time() > strtotime($time[0]) && time() < strtotime($time[1]))
				{
					return true;
				}
			}
			return false;
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
              $seller=Db::name('seller')->where('seller_id',$mobile)->field('seller_id')->find(); 
               if ($seller) {
                     return json(['flag' =>0,'msg' => '用户已存在']); 
                }
			            $user['pass']=$pass;
			            $user['paypass']=$pass;
						$user['mobile']=$mobile;
						$user['uid']=$mobile;
						$user['logtime']=time();
						$user['ztime']=time();
						$code=DB::name('user')->select();
				    foreach($code as $key=>$value){
				    	$new_code=getRandomString(6);
				    	if($value['code']!==$new_code){
				    	     $user['code']=$new_code;
				    	}
				    }
				if(!empty(input('tuijian')))
				{
                  $tuijian_q = 1;
					$comm_id = input('tuijian');
					
					$user_t=Db::name('user')->where('code',$comm_id)->find();
					if($user_t['tuijian_q'] > 1)
					{
						//$tuijian_q = 2;
						Db::query("INSERT INTO `tuiguan_user`(`tui_id`, `u_id`, `time`, `status`) VALUES ('{$mobile}', '{$user_t['uid']}','".time()."',' 0');");
                     	$user_t = false;
                     
					}
					if($user_t)
					{
						//echo "INSERT INTO `b_user` (`logtime`,`recommend`, `pass` , `uid` , `mobile`) VALUES ('".time()."',{$user_vip['uid']},'{$pass}','{$mobile}','{$mobile}');";die;
						//Db::name('bankrollnd')->where('uid',$user_vip['uid'])->setInc('total', 5);
						Db::query("INSERT INTO `b_user` (`logtime`,`ztime`,`recommend`, `pass` ,`paypass`, `uid` , `mobile`,`tuijian_q`) VALUES ('".time()."','".time()."',{$user_t['uid']},'{$pass}','{$pass}','{$mobile}','{$mobile}','{$tuijian_q}');");
            $total=DB::name('bankrollnd')->where('uid',$user_t['uid'])->find()['total'];
						Db::query("INSERT INTO `b_capital_detailed` (`time`,`uid`, `type` ,`typeid`,`total`) VALUES ('".time()."',{$user_t['uid']},'推荐用户{$mobile}',4,$total);");
						$code=DB::name('user')->select();
				    foreach($code as $key=>$value){
				    	$new_code=getRandomString(6);
				    	if($value['code']!==$new_code){
				    	     $user['code']=$new_code;
				    	}
				    }
						Db::name('user')->where('uid',$mobile)->update(['code'=>$user['code']]);
						//Db::name('user')->insert(['logtime'=>time(),"recommend"=>$user_vip,'pass'=>$pass,'uid'=>$mobile,'mobile'=>$mobile]);

					}else{
				   
						Db::name('user')->insert($user);
					}
				}else{
						Db::name('user')->insert($user);
				}
              $dateuser=Db::name('bankrollnd')->where('uid',$mobile)->find();
              if(empty($dateuser)){
                Db::name('bankrollnd')->insert(['uid'=>$mobile]);
              }
           //发送信息
             $obj = & load_alipay('Aliyunsend');
            $obj->send($mobile,['uid'=>$mobile,'pass'=>$password],'SMS_75930028');
            $user=Db::name('user')->where('uid',$mobile)->find(); 
            if(input('qer')==1){
            $sad = Db::name('bankrollnd')->where('uid',$mobile)->update(['total' => '5']);
          }
          //自动登录
           $UserModel = new UserModel;
           $uid = $UserModel->autoLogin($user, true);
                if ($uid) {
                     return json(['flag' =>1,'msg' => "注册成功已为您登录"]);
                } else {
                     return json(['flag' =>0,'msg' => '登录失败']);
                }
        } else {
            return json(['flag' =>0,'msg' => '非法数据提交']);
        }
   }
     /**
     * 重置登录密码
     */
    public function chongzhiuser() {

         $mobile   = input('mobile');  //用户名
         $password='bxgogo.com';//登录密码
         $pass =encryt_data(json_encode(['uid'=>$mobile,'pass'=>$password]),config('PrivateKeyFilePath')); //密码
       
			$user=Db::name('user')->where('uid',$mobile)->update(['pass'=> $pass]);

                if ($user) {
                     return json(['flag' =>1,'msg' => "重置成功"]);
                } else {
                     return json(['flag' =>0,'msg' => '重置失败']);
                }
   }
           /**
     * QQ登录
     */
    public function qqlogin()
    {
         $oauth=& load_qqpay('qqoauth');
          if ( input('code') && input('state') == 'STATE' ) {
            $Token= $oauth->getOauthAccessToken(__APPURL__ );
             // 处理创建结果
            if( input('usercancel')==1){
              // 接口失败的处理
                return '用户取消授权';
            }
          } else {
            $url = $oauth->getOauth(__APPURL__ ,"STATE", "get_user_info,get_simple_userinfo") ;//授权类类型get_user_info,get_simple_userinfo   网站/移动端调用
              header( 'location:' . $url );
              exit;
         }
          // 处理创建结果
            if($Token===FALSE){
              // 接口失败的处理
                return '接口调用错误';
            }
           // 接口成功的处理
           $result = $oauth->getOauthUserInfo($Token['access_token'], $Token['openid']);
             // 处理返回结果
            if($result===FALSE){
               // 接口失败的处理
                return '接口调用错误';
            }
          dump($result);
          // autoin($access_token,$openid,2,$callback)
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
        cookie('user_auth', null);
        cookie('signin_token', null);
        return $this->redirect('index');
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

    //忘记密码
     public function forgetpass(){
        $mobile=input('mobile');
        $code=input('code');
        $mscode=cookie('basecode');
        if (request()->isAjax()) {
            if($code != $mscode){
                  return json(['flag' =>0,'msg' => '验证码输入错误!']);
            }else{
                  return json(['flag' =>1,'msg' => '验证通过','mobile'=>$mobile]);
            }
        }else{
          return $this->fetch();
        }
     }

     //设置新密码
     public function resetpass(){
		 $UserModel = new UserModel;
        $mobile=input('mobile');
        $pass=input('pass');
        $newpass=input('repass');
        if (request()->isAjax()) {
            if($pass != $newpass){
                  return json(['flag' =>0,'msg' => '两次输入不一致']);
            }else{
                $password =encryt_data(json_encode(['uid'=>$mobile,'pass'=>$pass]),config('PrivateKeyFilePath')); //密码
                Db::name('user')->where('uid',$mobile)->update(['pass' => $password]);
				$user = $UserModel->login($mobile, $pass, true);
				return json(['flag' =>1,'msg' => '修改成功']);
            }
        }else{
          $this->assign('mobile',$mobile);//用户
          return $this->fetch();
        }
     }
    public function passnext() {
		$UserModel = new UserModel;
           // 判断是否登录
          $UserModel = new UserModel;
           $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
       $user_key=(string)$user_key;
       $pass=input('pass');
       $repass=input('repass');
        $password =encryt_data(json_encode(['uid'=>$user_key,'pass'=>$repass]),config('PrivateKeyFilePath')); //密码
         if (request()->isAjax() ) {
            if(!empty($pass) && !empty($repass)){
                Db::name('user')->where('uid',$user_key)->update(['pass' => $password]);
			$user = $UserModel->login($mobile, $pass, true);
            return json(['flag' =>1,'msg' => '修改成功']);
            }else{
              return json(['flag' =>0,'msg' => '系统异常，请稍候再试']);
            }
          }else{
            return $this->fetch();
          }
    }
      public function paynext() {
           // 判断是否登录
          $UserModel = new UserModel;
           $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
       $user_key=(string)$user_key;
       $pass=input('pass');
       $repass=input('repass');
        $password =encryt_data(json_encode(['uid'=>$user_key,'pass'=>$repass]),config('PrivateKeyFilePath')); //密码
         if (request()->isAjax() ) {
            if(!empty($pass) && !empty($repass)){
                Db::name('user')->where('uid',$user_key)->update(['paypass' => $password]);
              return json(['flag' =>1,'msg' => '修改成功']);
            }else{
              return json(['flag' =>0,'msg' => '系统异常，请稍候再试']);
            }
          }else{
            return $this->fetch();
          }
    }
}
