<?php
namespace app\pay\controller;

class Index extends \think\Controller {
	public $mobile_type; //访问类型
	public function index() {
		if (IS_WEIXIN) {$this->mobile_type = 'weichat';}
		if (IS_ALIPAY) {$this->mobile_type = 'alipay';}
		$out_trade_no = input('out_trade_no'); //订单号
		$subject = input('subject'); //订单名称
		$body = input('body'); //描述
		$total_fee = sprintf("%.2f", input('total_fee')); //价格
		$urltype = input('urltype');
		$sign = input('sign');
		$paydata['out_trade_no'] = $out_trade_no;
		$paydata['subject'] = $subject; //订单名称
		$paydata['body'] = $body; //描述
		$paydata['total_fee'] = floatval($total_fee); //价格
		$paydata['urltype'] = $urltype; //支付类型
		$result = verify(getSignContent($paydata), $sign, strtoupper('RSA'), config('PublicKeyFilePath')); //验证签名
		if ($result) {
//验证成功.
			switch ($this->mobile_type) {
			case "weichat":
				switch ($urltype) {
				case 0:
				    $attach=1;//账户充值
					break;
				case 1:
					$attach=2; //短信充值
					break;
				case 2:
					$attach=3; //VIP充值
					break;
				case 3:
					$attach=4; //交易
					break;
				}
				$oauth = &load_wechat('Oauth');
				if (input('code') && input('state') == 'STATE') {
					$Token = $oauth->getOauthAccessToken();
				} else {
					$url = $oauth->getOauthRedirect(__APPURL__, "STATE", "snsapi_base"); //授权回跳地址
					header('location:' . $url);
					exit;
				}
				// 处理创建结果
				if ($Token === FALSE) {
					// 接口失败的处理
					return '错误代码：' . $oauth->errCode . '错误原因：' . $oauth->errMsg;
				}
				// 接口成功的处理
				 $notify_url = __APPROOT__ . '/index/weixinnotify/'; //支付回调
				$obj = &load_wechat('Pay');
				$result = $obj->getPrepayId($Token['openid'],$body,$out_trade_no,$total_fee * 100, $notify_url,$attach, $trade_type = "JSAPI");
				// 处理创建结果
				if ($result === FALSE) {
					// 接口失败的处理
					return '错误代码：' . $obj->errMsg;
				} else {
					$options = $obj->createMchPay($result['prepay_id']);
					$this->assign('options', $options);
					return $this->fetch();
				}
				break;
			case "alipay":
				switch ($urltype) {
				case 0:
				   $extra_common_param=1;//账户充值
					break;
				case 1:
				   $extra_common_param=2;//短信充值
					break;
				case 2:
			       $extra_common_param=3; //VIP充值
					break;
				case 3:
				   $extra_common_param=4;//交易
					break;
				}
				$return_url = __APPROOT__ . '/index/alipayorderreturn/';//支付同步回调
				$notify_url = __APPROOT__ . '/index/alipayordernotify/';//支付异步回调
				$obj = &load_alipay('Aliyunpay');
				$result = $obj->pay($body,$out_trade_no,$subject,$total_fee,$return_url,$notify_url,$extra_common_param);
				break;
			default:
				switch ($urltype) {
				case 0:
				    $passback_params=1;//账户充值
					
					break;
				case 1:
				    $passback_params=2;//短信充值
					break;
				case 2:
				    $passback_params=3;//VIP充值
					break;
				case 3:
				    $passback_params=4;//交易
					break;
				}
				$return_url = __APPROOT__ . '/index/alimobilereturn/';//支付同步回调
				$notify_url = __APPROOT__ . '/index/alimobilenotify/';//支付异步回调
				$goods_type = "1"; //商品主类型：0—虚拟类商品，1—实物类商品 注：虚拟类商品不支持使用花呗渠道
				$obj = &load_alipay('Aliyunpay');
				$result = $obj->mobilepay($body,$out_trade_no,$subject,$total_fee,$return_url,$notify_url,$passback_params,$goods_type);
			}
		} else {
			// 签名文件不符
			return '验证失败';
		}
	}
	//H5微信支付为开放接口
     public function weigetPay() {
	$urltype = input('urltype');
	$out_trade_no = baseOrderSn(); //商户网站唯一订单号
	$body = '百信购订单'; //描述信息。
	$total_fee = sprintf("%.2f", input('total_fee')); //订单总金额，单位为元，精确到小数点后两位，
             switch ($urltype) {
		case 0:
		    $attach=1;//账户充值
			break;
		case 1:
			$attach=2; //短信充值
			break;
		case 2:
			$attach=3; //VIP充值
			break;
		case 3:
			$attach=4; //交易
			break;
		}
             $return_url = __APPROOT__ . '/index/weixingetPayreturn/out_trade_no/'.$out_trade_no; //支付同步回调
	$notify_url = __APPROOT__ . '/index/weixingetPaynotify/'; //支付异步回调
	$wap_url   =__APPROOT__;//网站URL地址
	$wap_name='百信购';//网站名
	$obj = &load_wechat('Pay');
	$result = $obj->getPayUrl($body,$out_trade_no,$total_fee * 100, $notify_url,$attach,$wap_url,$wap_name);
	if ($result === FALSE) {
		// 接口失败的处理
	       return '错误代码：' . $obj->errCode . '错误原因：' . $obj->errMsg;
	} else {
                          // 接口成功的处理
                //如果返回成功
                if(!array_key_exists("return_code", $result) || !array_key_exists("result_code", $result)){
                      return isset($result["err_code_des"]) ? $result["err_code_des"] : $result["return_msg"];  
                 }
                  //②、接口调用成功，明确返回调用失败
                 if($result["return_code"] == "SUCCESS" && $result['result_code'] != 'SUCCESS' ){
                     return '错误代码：' . $result["err_code"] . '错误原因：' . $result["err_code_des"] ;
                 }	
                $mweb_url=$result['mweb_url'] . '&redirect_url=' . urlencode($return_url);
                header("location:$mweb_url");
              exit;
           }
       }
  //微信提交刷卡支付，并且确认结果，接口比较慢
  public function Wxmicropay() {
  	  $urltype = input('urltype');
	  $out_trade_no =  baseOrderSn();//商户网站唯一订单号
	  $auth_code = input('auth_code');//支付授权码
	  $body      = '百信购订单';  //描述信息。
	  $total_fee = sprintf("%.2f", input('total_fee'));//订单总金额，单位为元，精确到小数点后两位，
	  switch ($urltype) {
		case 0:
		    $attach=1;//账户充值
			break;
		case 1:
			$attach=2; //短信充值
			break;
		case 2:
			$attach=3; //VIP充值
			break;
		case 3:
			$attach=4; //交易
			break;
	}
      $obj = & load_wechat('Pay');
      $result = $obj->createMicroPay($body,$auth_code, $out_trade_no, $total_fee*100,$attach);
      if($result===FALSE){
        // 接口失败的处理
           return '错误代码：' . $obj->errCode . '错误原因：' . $obj->errMsg;
       }else{// 接口成功的处理
       	   //如果返回成功
	        if(!array_key_exists("return_code", $result) || !array_key_exists("out_trade_no", $result) || !array_key_exists("result_code", $result)){
	            return isset($result["err_code_des"]) ? $result["err_code_des"] : $result["return_msg"];  
	        }
	        //②、接口调用成功，明确返回调用失败
	        if($result["return_code"] == "SUCCESS" && $result["result_code"] == "FAIL" &&  $result["err_code"] != "USERPAYING" &&   $result["err_code"] != "SYSTEMERROR"){
	          return '错误代码：' . $result["err_code"] . '错误原因：' . $result["err_code_des"] ;
	        }
	        //③、确认支付是否成功
	        $queryTimes = 1;
	        while($queryTimes < 5){
	            $queryResult = $obj->queryOrder($out_trade_no);
	            if($queryResult["return_code"] == "SUCCESS"){//查询成功
	                return $queryResult;
	                break;
	            } 
	            $queryTimes++;
	        }
	        //支付失败撤销订单
	        $reverseResult = $obj->reverse($out_trade_no);
	        dump($reverseResult );
         }
    }
 /**
     * 支付宝扫码支付
 */
    public function alimicropay() {
       $urltype = input('urltype');
	   $out_trade_no =  baseOrderSn();//商户网站唯一订单号
	   $auth_code = input('auth_code');//支付授权码
	   $body      = '百信购订单';  //描述信息。
	   $subject   = '百信购订单';//商品的标题/交易标题/订单标题/订单关键字等。
	   $total_amount = sprintf("%.2f", input('total_fee'));//订单总金额，单位为元，精确到小数点后两位，
	   switch ($urltype) {
		case 0:
		    $passback_params=1;//账户充值
			break;
		case 1:
			$passback_params=2; //短信充值
			break;
		case 2:
			$passback_params=3; //VIP充值
			break;
		case 3:
			$passback_params=4; //交易
			break;
	   }
           $obj = & load_alipay('Aliyunpay');
           $result = $obj->Micropay($body,$out_trade_no,$auth_code,$subject,$total_amount,$passback_params);
          if($result===FALSE){
               // 接口失败的处理
              return '错误代码：' . $obj->errCode . '错误原因：' . $obj->errMsg;
           }else{// 接口成功的处理
           	//如果返回成功
	if(!array_key_exists("sub_code", $result) || !array_key_exists("code", $result)){
	    return $result['alipay_trade_pay_response']["sub_code"] . $result['alipay_trade_pay_response']["msg"] . $result['alipay_trade_pay_response']["sub_msg"];  
	}
	//③、确认支付是否成功
	$queryTimes = 1;
	while($queryTimes < 5){
	      $queryResult = $obj->queryOrder($result['alipay_trade_pay_response']["out_trade_no"]);
	      if($queryResult['alipay_trade_pay_response']["code"] == "10000" && $queryResult['alipay_trade_pay_response']["sub_code"] == "ACQ.TRADE_HAS_SUCCESS"){//查询成功
		return $queryResult;
	              break;
	      } 
	   $queryTimes++;
	}
              //支付失败关闭订单
	  $reverseResult = $obj->reverse($out_trade_no);
	  dump($reverseResult );
          }
    }
//微信账户支付成功回调函数
  public function weixinnotify() {
      $obj = & load_wechat('Pay');
      $notifyInfo = $obj->getNotify();
      if($notifyInfo===FALSE){
          // 接口失败的处理
        return '错误代码：' . $obj->errCode . '错误原因：' . $obj->errMsg;
      }else{
      //支付通知数据获取成功
       if ($notifyInfo['result_code'] == 'SUCCESS' && $notifyInfo['return_code'] == 'SUCCESS') {
        // 支付状态完全成功，可以更新订单的支付状态了
         $attach=$notifyInfo['attach'];
            switch ($attach) {
		case 1:
		       
			break;//账户充值
		case 2:

			break;//短信充值
		case 3:

			break;//VIP充值
		case 4:

			break;//交易
	}
        return xml(['return_code' => 'SUCCESS', 'return_msg' => 'DEAL WITH SUCCESS']);
       }
     }
   }
  //微信H5支付成功同步回调函数
  public function weixingetPayreturn() {
      $out_trade_no =  input('out_trade_no');//商户网站唯一订单号
      $obj = & load_wechat('Pay');
        //确认支付是否成功
        $queryTimes = 1;
        while($queryTimes < 5){
            $queryResult = $obj->queryOrder($out_trade_no);
            if($queryResult["return_code"] == "SUCCESS"){//查询成功
                return $queryResult;
                break;
            } 
            $queryTimes++;
        }
        //支付失败撤销订单
        $reverseResult = $obj->reverse($out_trade_no);
        dump($reverseResult );
   }
//微信H5支付成功异步回调函数
  public function weixingetPaynotify() {
      $obj = & load_wechat('Pay');
      $notifyInfo = $obj->getNotify();
      if($notifyInfo===FALSE){
          // 接口失败的处理
        return '错误代码：' . $obj->errCode . '错误原因：' . $obj->errMsg;
      }else{
      //支付通知数据获取成功
       if ($notifyInfo['result_code'] == 'SUCCESS' && $notifyInfo['return_code'] == 'SUCCESS') {
        // 支付状态完全成功，可以更新订单的支付状态了
         $attach=$notifyInfo['attach'];//附加值
            switch ($attach) {
	case 1:
	       
		break;//账户充值
	case 2:

		break;//短信充值
	case 3:

		break;//VIP充值
	case 4:

		break;//交易
	}
        return xml(['return_code' => 'SUCCESS', 'return_msg' => 'DEAL WITH SUCCESS']);
       }
     }
   }
     /**
     * 老版本支付宝账户充值同步通知
     */
    public function alipayorderreturn() {
       $data = urldecode($_GET);
       $obj = & load_alipay('Alipaynotify');
        $result = $obj->verifyNotify($data);
      if ($result) {//验证成功.
            $out_trade_no = $data['out_trade_no']; //商户订单号
            $trade_no = $data['trade_no'];//支付宝交易号
            $trade_status = $data['trade_status'];//交易状态
            $extra_common_param = $data['extra_common_param'];//附加值
            if ($data['trade_status'] == 'TRADE_FINISHED' || $data['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
              switch ($extra_common_param) {
		case 1:
		       
			break;//账户充值
		case 2:

			break;//短信充值
		case 3:

			break;//VIP充值
		case 4:

			break;//交易
	}
              echo "验证成功";
             }
        }else {
            echo "验证失败";
        }
    }
     /**
     * 老版本支付宝账户充值异步通知
     */
    public function alipayordernotify() {
       $data = urldecode($_POST);
       $obj = & load_alipay('Alipaynotify');
      $result = $obj->verifyNotify($data);
      if ($result) {//验证成功.
            $out_trade_no = $data['out_trade_no']; //商户订单号
            $trade_no = $data['trade_no'];//支付宝交易号
            $trade_status = $data['trade_status'];//交易状态
            $extra_common_param = $data['extra_common_param'];//附加值
            if ($data['trade_status'] == 'TRADE_FINISHED' || $data['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
              switch ($extra_common_param) {
		case 1:
		       
			break;//账户充值
		case 2:

			break;//短信充值
		case 3:

			break;//VIP充值
		case 4:

			break;//交易
	}
            }
            echo "success";        //不要修改或删除
        } else {
            //验证失败不要修改或删除
            echo "fail";
        }
    }
     /**
     * 手机网站支付宝账户充值同步通知
     */
    public function alimobilereturn() {                          
        $_GET['sign_type']='';
        $data = urldecode($_GET);
     $result=verify(getSignContent($data),$data['sign'],config('alipay.sign_type'),config('alipay.PublicKeyFilePath'),config('alipay.PublicKeyli'));
      if ($result) {//验证成功.
            $out_trade_no = $data['out_trade_no']; //商户订单号
            $trade_no = $data['trade_no'];//支付宝交易号
            $passback_params=$data['passback_params'];//附加值
           if ($data['trade_status'] == 'TRADE_FINISHED' || $data['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
               switch ($passback_params) {
		case 1:
		       
			break;//账户充值
		case 2:

			break;//短信充值
		case 3:

			break;//VIP充值
		case 4:

			break;//交易
	}
              echo "验证成功";
             }
        } else {
          echo "验证失败";
        }
    }
   /**
     * 手机网站支付宝账户充值异步通知
     */
    public function alimobilenotify() {
        $_POST['sign_type']='';
        $data = urldecode($_POST);
        $result=verify(getSignContent($data),$data['sign'],config('alipay.sign_type'),config('alipay.PublicKeyFilePath'),config('alipay.PublicKeyli'));
       if ($result) {//验证成功.
            $out_trade_no = $data['out_trade_no']; //商户订单号
            $trade_no = $data['trade_no'];//支付宝交易号
            $passback_params=$data['passback_params'];//附加值
            if ($data['trade_status'] == 'TRADE_FINISHED' || $data['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
              switch ($passback_params) {
		case 1:
		       
			break;//账户充值
		case 2:

			break;//短信充值
		case 3:

			break;//VIP充值
		case 4:

			break;//交易
	}
            }
             echo "success";        //不要修改或删除
        } else {
            //验证失败不要修改或删除
            echo "fail";
        }
    }

}
