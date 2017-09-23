<?php
namespace app\index\model;
use think\Model;
use think\Db;
/**
 * 后台用户模型
 */
class User extends Model
{
     public $errMsg;//错误

      /**
     * 用户登录
     * @param string $username 用户名
     * @param string $password 密码
     * @param bool $rememberme 记住登录
     * @return bool|mixed
     */
    public function login($username = '', $password = '', $rememberme = false)
    {
      $this->errMsg= '密码错误';
        $username = trim($username);
        $password = trim($password);
        $pass =encryt_data(json_encode(['uid'=>$username,'pass'=>$password]),config('PrivateKeyFilePath')); //密码
            // 手机号登录
        $user=Db::name('user')->where('uid',$username)->find(); 
       if (!$user) {
            $this->errMsg = '用户不存在或被禁用！';
        } else {
            if ($pass !== $user['pass']) {
                $this->errMsg= '密码错误！';
             } else {
               Db::name('user')->where('uid',$user['uid'])->update(['logtime' => time(),'stime'=>$user['logtime']]);
               return $this->autoLogin($user, $rememberme);
            }
        }
         return false;
    }
      /**
     * 自动登录
     * @param $user 用户对象
     * @param bool $rememberme 是否记住登录，
     */
    public function autoLogin($user, $rememberme = false)
    {
        // 记录登录SESSION和COOKIES
        $auths = array('uid'=>$user['uid'],'appid'=>'baixingou');
        $user_auth =encryt_data(json_encode($auths),config('PrivateKeyFilePath')); //密码
        $signin_token =sign(getSignContent($auths),strtoupper('RSA'),config('PrivateKeyFilePath')); //签名
        session('userara',$user['did']);
        session('user_sign_auth',$user_auth);
        session('user_auth_sign',$signin_token);
        // 记住登录
        if ($rememberme) {
            cookie('user_auth',$user_auth,3600);
            cookie('userara',$user['did'],3600);
            cookie('signin_token',$signin_token,3600);
        }
        return $auths;
    }
       /**
     * 判断是否登录
     * @return int 0或用户id
     */
    public function isLogin()
    {
        $user_auth=session('user_sign_auth');
        $user = session('user_auth_sign');//签名
        if (empty($user_auth)  && empty($user) ) {
            // 判断是否记住登录
            if ( cookie('?user_auth') && cookie('?signin_token')) {
                $signin_key =decrypt_data(cookie('user_auth'),config('PublicKeyFilePath')); //密码
                $signin_key =json_decode($signin_key,true); //密码
                $isSign=verify(getSignContent($signin_key),cookie('signin_token'),strtoupper( 'RSA' ),config('PublicKeyFilePath'));//验证签名
                if($isSign){
                     $user = Db::name('user')->where('uid',$signin_key['uid'])->find(); 
                  if ($user) {
                    $auth = array('uid'=>$user['uid'],'appid'=>'baixingou');
                    $user_auth =encryt_data(json_encode($auth),config('PrivateKeyFilePath')); //密码
                    $signin_token =sign(getSignContent($auth),strtoupper( 'RSA' ),config('PrivateKeyFilePath')); //密码
                    if (cookie('signin_token') == $signin_token) {
                        // 自动登录
                       session('userara',cookie('userara'));
                       session('user_sign_auth',$user_auth);
                       session('user_auth_sign',$signin_token);
                       Db::name('user')->where('uid',$signin_key['uid'])->update(['logtime' => time(),'stime'=>$user['logtime']]);
                       cookie('userara',cookie('userara'),3600);
                       cookie('user_auth',$user_auth,3600);
                       cookie('signin_token',$signin_token, 3600);
                        return $user['uid'];
                    }
                }
              }
            };
            return 0;
        }else{
            $signin_key=[];
            $signin_key =decrypt_data(session('user_sign_auth'),config('PublicKeyFilePath')); //密码
            $signin_key =json_decode($signin_key,true); //密码
            $isSign=verify(getSignContent($signin_key),session('user_auth_sign'),strtoupper( 'RSA' ),config('PublicKeyFilePath'));
             if ($isSign){
                return $signin_key['uid'] ;
             }else{
                return 0;
             }
            
        }
    }
}

