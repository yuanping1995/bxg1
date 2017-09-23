<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
/**
 * 过滤数组元素前后空格 (支持多维数组)
 * @param $array 要过滤的数组
 * @return array|string
 */
function trim_array_element($array){
    if(!is_array($array))
        return trim($array);
    return array_map('trim_array_element',$array);
}
/**
 * 检查手机号码格式
 * @param $mobile 手机号码
 */
function check_mobile($mobile){
    if(preg_match('/1[34578]\d{9}$/',$mobile))
        return true;
    return false;
}
/**
 * 检查固定电话
 * @param $mobile
 * @return bool
 */
function check_telephone($mobile){
    if(preg_match('/^([0-9]{3,4}-)?[0-9]{7,8}$/',$mobile))
        return true;
    return false;
}
/**
 * 检查邮箱地址格式
 * @param $email 邮箱地址
 */
function check_email($email){
    if(filter_var($email,FILTER_VALIDATE_EMAIL))
        return true;
    return false;
}
/**
 * @param $arr
 * @param $key_name
 * @return array
 * 将数据库中查出的列表以指定的 id 作为数组的键名 
 */
function convert_arr_key($arr, $key_name)
{
  $arr2 = array();
  foreach($arr as $key => $val){
    $arr2[$val[$key_name]] = $val;        
  }
  return $arr2;
}
/**
 * 二维数组排序
 * @param $arr
 * @param $keys
 * @param string $type
 * @return array
 */
function array_sort($arr, $keys, $type = 'desc')
{
    $key_value = $new_array = array();
    foreach ($arr as $k => $v) {
        $key_value[$k] = $v[$keys];
    }
    if ($type == 'asc') {
        asort($key_value);
    } else {
        arsort($key_value);
    }
    reset($key_value);
    foreach ($key_value as $k => $v) {
        $new_array[$k] = $arr[$k];
    }
    return $new_array;
}
/**
 * 多维数组转化为一维数组
 * @param 多维数组
 * @return array 一维数组
 */
function array_multi2single($array)
{
    static $result_array = array();
    foreach ($array as $value) {
        if (is_array($value)) {
            array_multi2single($value);
        } else
            $result_array [] = $value;
    }
    return $result_array;
}
/**
 * *
 * 判断一个数组是否存在于另一个数组中
 *
 * @param unknown $arr            
 * @param unknown $contrastArr            
 * @return boolean
 */
function is_all_exists($arr, $contrastArr)
{
    if (! empty($arr) && ! empty($contrastArr)) {
        for ($i = 0; $i < count($arr); $i ++) {
            if (! in_array($arr[$i], $contrastArr)) {
                return false;
            }
        }
        return true;
    }
}
  /*
   * 无限极分类 
   * $data  被整理的数据源
   * $name  分类后 下一级的名字
   * $pid   分类标示 寻找下一级用
   * $pk    主键名
   * $ppk   分类标示字段名
   */
  function getTree($data, $name = 'child',$pid = 0,$pk='id',$ppk='pid'){
		$arr = array();
		foreach ($data as $v) {
			if ($v[$ppk] == $pid) {
				$v[$name] = getTree($data, $name, $v[$pk]);
				$arr[] = $v;
			}
		}
		return $arr;
	}
/** 
 +---------------------------------------------------------- 
 * 功能：计算两个日期相差 天数
 +---------------------------------------------------------- 
 * @param date   $date1 起始日期 
 * @param date   $date2 截止日期日期 
 */
function timediff( $begin_time, $end_time )
{
	$begin_time = strtotime($begin_time);
    $end_time = strtotime($end_time);
	if ( $begin_time < $end_time ) {
		$starttime = $begin_time;
		$endtime = $end_time;
	} else {
		$starttime = $end_time;
		$endtime = $begin_time;
	}
	$timediff = $endtime - $starttime;
	$days = intval( $timediff / 86400 );
	return $days;
}
/**
 * 获得几天前，几小时前，几月前
 * @param int $time 时间戳
 * @param array $unit 时间单位
 * @return bool|string
 */
function date_before($time, $unit = null)
{
    $time = intval($time);
    $unit = is_null($unit) ? array("年", "月", "星期", "天", "小时", "分钟", "秒") : $unit;
    switch (true) {
        case $time < (time() - 31536000) :
            return floor((time() - $time) / 31536000) . $unit[0] . '前';
        case $time < (time() - 2592000) :
            return floor((time() - $time) / 2592000) . $unit[1] . '前';
        case $time < (time() - 604800) :
            return floor((time() - $time) / 604800) . $unit[2] . '前';
        case $time < (time() - 86400) :
            return floor((time() - $time) / 86400) . $unit[3] . '前';
        case $time < (time() - 3600) :
            return floor((time() - $time) / 3600) . $unit[4] . '前';
        case $time < (time() - 60) :
            return floor((time() - $time) / 60) . $unit[5] . '前';
        default :
            return floor(time() - $time) . $unit[6] . '前';
    }
}
   /**
     *  判断日期 所属 干支 生肖 星座
    * y,m,d 参数：日期
     *  type 参数：XZ 星座 GZ 干支 SX 生
     * @param string $type  获取信息类型
     * @return string
     */
   function magicInfo($y,$m,$d,$type) {
        $result = '';
        switch ($type) {
        case 'XZ'://星座
            $XZDict = array('摩羯','宝瓶','双鱼','白羊','金牛','双子','巨蟹','狮子','处女','天秤','天蝎','射手');
            $Zone   = array(1222,122,222,321,421,522,622,722,822,922,1022,1122,1222);
            if((100*$m+$d)>=$Zone[0]||(100*$m+$d)<$Zone[1])
                $i=0;
            else
                for($i=1;$i<12;$i++){
                if((100*$m+$d)>=$Zone[$i]&&(100*$m+$d)<$Zone[$i+1])
                  break;
                }
            $result = $XZDict[$i].'座';
            break;
        case 'GZ'://干支
            $GZDict = array(
                        array('甲','乙','丙','丁','戊','己','庚','辛','壬','癸'),
                        array('子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥')
                        );
            $i= $y -1900+36 ;
            $result = $GZDict[0][$i%10].$GZDict[1][$i%12];
            break;
        case 'SX'://生肖
            $SXDict = array('鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪');
            $result = $SXDict[($y-4)%12];
            break;
        }
        return $result;
    }
/**
 * 获得送货时间
 * @return bool|string
 */
   function acc_date(){
	$timeArray=array(
			  array("start_time"=>9,"end_time"=>15),
			  array("start_time"=>15,"end_time"=>19),
		      );
	$weekarray=array("日","一","二","三","四","五","六");
	$date=array();
	$time=array();
	//当前时间
	$day_date=time();
	$day_time=strtotime(date("Y-m-d"));
	for($i=0;$i<8;$i++){
		$date[]=array(
			"day"=>intval(date("m",$day_time+86400*$i))."-".intval(date("d",$day_time+86400*$i)),
			"date"=>date("Y",$day_time+86400*$i)."-".intval(date("m",$day_time+86400*$i))."-".intval(date("d",$day_time+86400*$i)),
			"week"=>$i==0?"今天":$weekarray[date("w",$day_time+86400*$i)]
			);
	}
	foreach($timeArray as $rs){
		$title=str_replace(".",":",sprintf("%01.2f",$rs["start_time"]))."-".str_replace(".",":",sprintf("%01.2f",$rs["end_time"]));
		$time[]=array("title"=>$title,"start_time"=>$rs["start_time"]);
	}
	$_date=array();
	$_h=intval(date("G"));
	foreach($date as $i=>$rs){
		$list=array();
		foreach($time as $r){
			if($i==0&&$_h+1>=$r["start_time"]){
				$list[]=array();
			}else{
				if($i==0){
					$list[]=array("val"=>$rs["date"]." (周".$weekarray[date("w")].") ".$r["title"]);	
				}else{
					$list[]=array("val"=>$rs["date"]." (周".$rs["week"].") ".$r["title"]);	
				}
			}	
		}
		$rs["list"]=$list;
		$_date[]=$rs;
	}
	$arrive_day=array("date"=>$_date,"time"=>$time);
      return $arrive_day;
    }
/**
 * 数据签名
 * @param  array data  须被认证的数据
 *FilePath  私钥文件路径
 */
  function sign($data, $signType = "RSA",$FilePath) {
	  //读取私钥文件
      $priKey = file_get_contents($FilePath);
	  //转换为openssl格式密钥
	  $res = openssl_get_privatekey($priKey);
      ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置'); 
       if ("RSA2" == $signType) {
			openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
		} else {
			openssl_sign($data, $sign, $res);
		}
		//释放资源
			openssl_free_key($res);
		$sign = base64_encode($sign);
		return $sign;
	}
/**
 * 数据签名认证
 * @param  array $data 被认证的数据  
 *FilePath  公钥文件
 */
  function verify($data, $sign,$signType = 'RSA',$FilePath,$pubKey='') {
		//读取公钥文件
      if(!empty($pubKey)){
	     $res = "-----BEGIN PUBLIC KEY-----\n" .
	      wordwrap($pubKey, 64, "\n", true) .
	      "\n-----END PUBLIC KEY-----";//
      }else{
         $pubKey = file_get_contents($FilePath);
       //转换为openssl格式密钥
         $res = openssl_get_publickey($pubKey);
      }
		($res) or die('RSA公钥错误。请检查公钥文件格式是否正确');  
		//调用openssl内置方法验签，返回bool值
		if ("RSA2" == $signType) {
			$result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
		} else {
			$result = (bool)openssl_verify($data, base64_decode($sign), $res);
		}
    if(empty($pubKey)){
			//释放资源
			openssl_free_key($res);
     }
		return $result;
	}
	  /**
	 * 数据加密
	 * @param  array $data 被认证的数据  
	 *FilePath  公钥文件
	 */
	  function encryt_data($data,$FilePath) {
	    //读取公钥文件
	    $priKey = file_get_contents($FilePath);
	    //转换为openssl格式密钥
	    $res = openssl_get_privatekey($priKey);
	    ($res) or die('RSA公钥错误。请检查公钥文件格式是否正确');  
	    //调用openssl内置方法
	      openssl_private_encrypt($data,$sign, $res);
	    //释放资源
	      openssl_free_key($res);
	    $sign = base64_encode($sign);
	    return $sign;
	  }
	    /**
	 * 数据解密
	 * @param  array $data 被认证的数据  
	 *FilePath  公钥文件
	 */
	  function decrypt_data($data, $FilePath) {
	    //读取公钥文件
	    $pubKey = file_get_contents($FilePath);
	    //转换为openssl格式密钥
	    $res = openssl_get_publickey($pubKey);
	    ($res) or die('RSA公钥错误。请检查公钥文件格式是否正确');  
	    //调用openssl内置方法验签，返回bool值
	      openssl_public_decrypt (base64_decode($data), $sign, $res);
	    //释放资源
	      openssl_free_key($res);
	    return $sign;
	  }
	/** 
	 *  拼接字符串
	 **/
	function getSignContent($params) {
		ksort($params); //排序
		$para = "";
   	 while (list ($key, $val) = each ($params)) {
		  if($key == "sign" || $val == "")continue;
		    else  $para.=$key."=".$val."&";
	   }
       	//去掉最后一个&字符
    	$para = substr($para,0,count($para)-2);
		return $para; //
	}
	  /**
   * 远程获取数据，
   * 注意：
   * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
   * @param $url 指定URL完整路径地址
   * @param $cacert_url 指定当前工作目录绝对路径
   * return 远程输出的数据
   */
    function httpGetResponse($url) {
        $oCurl = curl_init();
        curl_setopt($oCurl, CURLOPT_URL, $url);
		    curl_setopt($oCurl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		   if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);//SSL证书认证
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);//严格认证
        }
        $responseText = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $responseText;
        } else {
            return false;
        }
    }