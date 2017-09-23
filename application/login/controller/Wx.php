<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/20 0020
 * Time: 上午 10:27
 */

namespace app\login\controller;


class Wx extends \think\Controller
{
  public function wcheat_Login(){
      $appid='wxa57215e710d7bd39';
      $redirect_uri = urlencode('http://123.207.163.87/wx2.php' );
      $url ="https://open.weixin.qq.com/connect/qrconnect?appid=wx2d3955a79caf7b3a&redirect_uri=http%3a%2f%2fhoutai.bxgogo.com&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
      header("Location:".$url);
  }
}