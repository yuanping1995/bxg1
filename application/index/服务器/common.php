<?php

//设置阿里云OSS储存的类位置设置命名空间
\think\Loader::addNamespace('OSS','./extend/oos/src/OSS');
// 应用公共文件

	//获取随机数
	function baserand( $len = 6 ) {
		$data = '0123456789';
		$str  = '';
		while ( strlen( $str ) < $len ) {
			$str .= substr( $data, mt_rand( 0, strlen( $data ) - 1 ), 1 );
		}

		return $str;
	}
 /**  
 * 生成一定数量的不重复随机数  
 * @param int $min ,$max指定随机数的范围  
 * @param int $max  
 * @param int $num 指定生成数量  
 * @return array  
 */ 
  function unique_rand($min, $max, $num) {  
    $count = 0;  
    $return = array();  
    while ($count < $num) {  
        $return[] = mt_rand($min, $max);  
        $return = array_flip(array_flip($return));  
        $count = count($return);  
    }  
    shuffle($return);  
    return $return;  
  }
	/*
          * 生成唯一的订单号
     */
    function baseOrderSn(){
	     return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
	//中文字符串截取
   function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
	switch($charset){
		case 'utf-8':$char_len=3;break;
		case 'UTF8':$char_len=3;break;
		default:$char_len=2;
	}
	//小于指定长度，直接返回
    if(strlen($str)<=($length*$char_len)) return $str;
	if(function_exists("mb_substr")){   
	 	$slice= mb_substr($str, $start, $length, $charset);
	} else if(function_exists('iconv_substr')){
        $slice=iconv_substr($str,$start,$length,$charset);
    } else { 
	   $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
	}
       if($suffix) return $slice."…";
       return $slice;
   }
//php获取中文字符拼音首字母
    function getFirstCharter($str){
      if(empty($str))
      {
            return '';          
      }
      $fchar=ord($str{0});
      if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
      $s1=iconv('UTF-8','gb2312',$str);
      $s2=iconv('gb2312','UTF-8',$s1);
      $s=$s2==$str?$s1:$str;
      $asc=ord($s{0})*256+ord($s{1})-65536;
     if($asc>=-20319&&$asc<=-20284) return 'A';
     if($asc>=-20283&&$asc<=-19776) return 'B';
     if($asc>=-19775&&$asc<=-19219) return 'C';
     if($asc>=-19218&&$asc<=-18711) return 'D';
     if($asc>=-18710&&$asc<=-18527) return 'E';
     if($asc>=-18526&&$asc<=-18240) return 'F';
     if($asc>=-18239&&$asc<=-17923) return 'G';
     if($asc>=-17922&&$asc<=-17418) return 'H';
     if($asc>=-17417&&$asc<=-16475) return 'J';
     if($asc>=-16474&&$asc<=-16213) return 'K';
     if($asc>=-16212&&$asc<=-15641) return 'L';
     if($asc>=-15640&&$asc<=-15166) return 'M';
     if($asc>=-15165&&$asc<=-14923) return 'N';
     if($asc>=-14922&&$asc<=-14915) return 'O';
     if($asc>=-14914&&$asc<=-14631) return 'P';
     if($asc>=-14630&&$asc<=-14150) return 'Q';
     if($asc>=-14149&&$asc<=-14091) return 'R';
     if($asc>=-14090&&$asc<=-13319) return 'S';
     if($asc>=-13318&&$asc<=-12839) return 'T';
     if($asc>=-12838&&$asc<=-12557) return 'W';
     if($asc>=-12556&&$asc<=-11848) return 'X';
     if($asc>=-11847&&$asc<=-11056) return 'Y';
     if($asc>=-11055&&$asc<=-10247) return 'Z';
     return null;
    } 

/**
       * 腾讯地图坐标转百度地图坐标
       * @param [String] $lat 腾讯地图坐标的纬度
       * @param [String] $lng 腾讯地图坐标的经度
       * @return [Array] 返回记录纬度经度的数组
*/
function Convert_GCJ02_To_BD09($lat=1,$lng=1){
  $lat = empty(trim($lat))?1:trim($lat);
  $lng = empty(trim($lng))?1:trim($lng);
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng;
        $y = $lat;
        $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta) + 0.0065;
        $lat = $z * sin($theta) + 0.006;
        return array('lng'=>$lng,'lat'=>$lat);
}

/**
* 百度地图BD09坐标---->中国正常GCJ02坐标
* 腾讯地图用的也是GCJ02坐标
* @param double $lat 纬度
* @param double $lng 经度
* @return array();
*/
function Convert_BD09_To_GCJ02($lat=1,$lng=1){
  $lat = empty(trim($lat))?1:trim($lat);
  $lng = empty(trim($lng))?1:trim($lng);
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
        return array('lng'=>$lng,'lat'=>$lat);
}
//百度地图坐标计算
function rad($d=1)  
{
		$d = empty(trim($d))?1:trim($d);
         return trim($d) * 3.1415926535898 / 180.0;  
}  

/**
       * 腾讯地图坐标转百度地图坐标
       * @param [String] $lat1 A点的纬度
       * @param [String] $lng1 A点的经度
       * @param [String] $lat2 B点的纬度
       * @param [String] $lng2 B点的经度
       * @return [String] 两点坐标间的距离，输出单位为米
*/
function GetDistance($lat1=1, $lng1=1, $lat2=1, $lng2=1)  
{  
      $lat1 = empty(trim($lat1))?1:trim($lat1);
      $lng1 = empty(trim($lng1))?1:trim($lng1);
      $lat2 = empty(trim($lat2))?1:trim($lat2);
      $lng2 = empty(trim($lng2))?1:trim($lng2);
   $EARTH_RADIUS = 6378.137;//地球的半径
   $radLat1 = rad($lat1);   
   $radLat2 = rad($lat2);  
   $a = $radLat1 - $radLat2;  
   $b = rad($lng1) - rad($lng2);  
   $s = 2 * asin(sqrt(pow(sin($a/2),2) +  
    cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));  
   $s = $s *$EARTH_RADIUS;  
   $s = round($s * 10000) / 10000;
   $s=$s*1000;
   return ceil($s);  
}  

/**
       * 标记大概的距离，做出友好的距离提示
       * @param [$number] 距离数量
       * @return[String] 距离提示
*/
function mToKm($number=1){

		$number = empty(trim($number))?1:trim($number);
    if(!is_numeric($number)) return ' ';
    switch ($number){
    	    	case $number >1000:
			         	 $v='约'.round($number/1000,2);
            break;
			case $number<900:
                 $v=ceil($number/100)*100;
            break;
            default:
                $v=ceil($number/100)*100/1000;
            break; 
    }
    
    if($v<100){
        $v= $v.'公里';
	}else{
		$v= $v.'米以内';
	}
    return $v; 
}
/** 
 +---------------------------------------------------------- 
 * 功能：计算两个日期相差 天数
 +---------------------------------------------------------- 
 * @param date   $date1 起始日期 
 * @param date   $date2 截止日期日期 
 +---------------------------------------------------------- 
 * @return array       
 +---------------------------------------------------------- 
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
   function accdate(){
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
   * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
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
  /**
   * 中文字符串分词，
   * @param $title 要分的字符串
   * @param $arr 是否以数组格式返回true是默认否false
   * @param $num 要显示的条数【可选】留空显示全部
   * return 分词结果  字符串或者数组
   */
  
    function getTags($title="", $arr = false, $num = null) {
 	$title1[] =$title;
    $title = is_number(is_engilsh($title));
     if(!empty($title))
     {
		$pscws = new \Scws\Scws('utf8');
        $pscws -> set_charset('utf-8');
        $pscws -> set_dict('./static/Scws/dict.utf8.xdb');
        $pscws -> set_rule('./static/Scws/rules.utf8.ini');
        $pscws -> set_ignore(true);
        $pscws -> send_text($title);
        $words = $pscws -> get_tops($num);
        $pscws -> close();
        $tags = array();
        foreach ($words as $val) {
          $tags[] = $val['word'];
        }
        if (false === $arr) {
          return implode(',', $tags);
        } else {
          return $tags;
        }
     }else{
         return $title1;
     }
 }
//判断最后字符是不是英文
function is_engilsh($str){
   $strwei = substr($str,-1);
  if(preg_match("/^[\.a-zA-Z0-9\s]+$/",$strwei)){
		 $str = is_engilsh(substr($str,0,strlen($str)-1));
        
  }
  return $str;
 // $newstr = substr($str,0,strlen($str)-1); 
}
function is_number($str){
   $strwei = substr($str,0,1);

  if(preg_match("/^[\.0-9a-zA-Z\s]+$/",$strwei)){
  
		 $str = is_number(substr($str,1));
  }
  return $str;
 // $newstr = substr($str,0,strlen($str)-1); 
}
 /**
   * 中文转拼音类
   * @param $title 要转的字符串
   * @param $type 返回的类型1返回全拼 2返回首字母
   * return 转换结果 字符串
   */
  function pinYin($title,$type){
    //实例化中文转拼音类
    $py = new \pinyin\Pinyin();
    if($type==1){
      return $py->getAllPY($title);
    }else{
      return $py->getFirstPY($title);
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
  /*
   * 可节省计算
   */
  
   function saveCalculation($price,$points,$isvip='1'){
    $quzheng=1;
  
    if($points=='10')
    {
      $sum=0.92;//0.96;
      $vip=0.9;
    }else if($points=='5'){
      $sum=0.96;
      $vip=0.95;
    }else{
      return '检查你输入的第二个参数'.$points;
    }
    $newprice = $price - $sum*$price;
//    if($isvip==1){
//      $newprice =$vip*$price*0.4+$newprice;
//    }else if($isvip==3){
//      $newprice =$vip*$price*0.4;
//    }
    return round($newprice ,2);
  
  }
	 /*
   * 根据商品ID计算出可节省
   * $shop 商品的主键
   * $isvip 是否是VIP 1是其他否
   */
  function getShopPoints($shop,$isvip='1'){
  	if(is_numeric($shop)){
		$goods=\think\Db::name('goods')->field('goods_id,goods_price,level')->where("goods_id=$shop")->find();
		$pid=$goods['three_level'];
		$points=\think\Db::name('cha_level')->field('points')->where("id=$pid")->find()['points'];
		if($points=='10')
		{
			$sum=0.96;
		}else if($points == '5'){
			$sum=0.98;
		}else{
			return "请检查分类表的扣点";
		}
		$xianjia= $sum * $goods['goods_price'];
		$sheng = $goods['goods_price'] - $xianjia;
		if($isvip==1){
			$xianjia = $xianjia * 0.4 + $sheng;
		}
		dump($xianjia);
	}else{
		return "传输数据错误";
	}
  }
  /*
   * 获取VIP 推荐应得提成
   */
  function getVip($user){
  	
  	if(is_numeric($user))
	{
		//VIP每年费用
		$nianfei = 20;
		$getuser=\think\Db::name('user')->field('qid,recommend')->where("uid=$user")->find();
		//根据推荐过多少人来计算
		if($getuser['qid']==3){
			if($getuser['recommend']>200){
				$tc = 0.75;
			}else if($getuser['recommend']>50){
				$tc = 0.5;
			}else{
				$tc = 0.25;
			}
			$fanxian = $tc*$nianfei;
			
		}else{
			return "只有VIP可以推荐的";
		}
		dump($fanxian);
	}else{
		return "传输数据错误";
	}
  	
  }
  /*
   * 确认收货的订单应该返现计算
   * $order 订单ID
   */
  
 function getDetermine($order){
 	
 }

  /*
  * 生成二维码的方法
  * $text  生成的内容
  * $size 大小
  * $logo  中间的图标
  * $qrdir 默认储存位置
  */
 function qrCode($text,$size=4,$logo=FALSE,$qrdir=false){
 		$value =$text; //二维码内容 
		Vendor('phpqrcode.phpqrcode');
		$errorCorrectionLevel = 'H';//容错级别 
		$matrixPointSize = $size;//生成图片大小
	
		//生成二维码图片 
		if(!$logo)
		return \QRcode::png($value,$qrdir, $errorCorrectionLevel, $matrixPointSize, 2);
		else
			\QRcode::png($value,$qrdir, $errorCorrectionLevel, $matrixPointSize, 2);
		
		$logo =$logo;//'http://login.bxgogo.com/static/img/log_logo.png';//准备好的logo图片 
		$QR = $qrdir;//已经生成的原始二维码图 
		if ($logo !== FALSE) { 
			 $QR = imagecreatefromstring(file_get_contents($QR)); 
			 $logo = imagecreatefromstring(file_get_contents($logo));
			 $QR_width = imagesx($QR);//二维码图片宽度 
			 $QR_height = imagesy($QR);//二维码图片高度 
			 $logo_width = imagesx($logo);//logo图片宽度 
			 $logo_height = imagesy($logo);//logo图片高度 
			 $logo_qr_width = $QR_width / 5; 
			 $scale = $logo_width/$logo_qr_width; 
			 $logo_qr_height = $logo_height/$scale; 
			 $from_width = ($QR_width - $logo_qr_width) / 2; 
			 //重新组合图片并调整大小 
			imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
			 $logo_qr_height, $logo_width, $logo_height);
		}
		
		//释放缓存
		ob_clean();
		//输出图片 
		
		if(!$qrdir)
			imagepng($QR);
		else
			imagepng($QR,$qrdir);
		imagedestroy($QR);
 }
 
 function getThree($id){
   if(empty($id) || $id =='null')
   {
     return 0;
   }
		$arr = array();
		$data=\think\Db::name('cha_level')->where("id=".$id)->select();
		if($data){
			$id ='';
			foreach ($data as $v) {
				if($v['level']<3&&$v['level']>=0)
				{
					$data1 = \think\Db::name('cha_level')->where("pid=".$v['id'])->select();
					foreach ($data1 as $va) {
						if($va['level']<3&&$va['level']>=0)
						{
							$arr = \think\Db::name('cha_level')->where("pid=".$va['id'])->select();
							foreach($arr as $val){
								$id .= $val['id'].',';
							}
						}else $id .= $va['id'].',';
						
					}
					
				}else $id .= $v['id'].',';
				
			}
		}else $id .= $id;
		$id = rtrim($id,',');
		return $id;
	}
 //删除文件夹
 function del_dir($dir) {
$dh=opendir($dir);
while ($file=readdir($dh)) {
if($file!="." && $file!="..") {
$fullpath=$dir."/".$file;
if(!is_dir($fullpath)) {
@unlink($fullpath);
} else {
del_dir($fullpath);
}
}
}
closedir($dh);
if(rmdir($dir)) {
return true;
} else {
return false;
}
}
//随机生成字母加数字
function getRandomString($len, $chars=null)  
{  
    if (is_null($chars)) {  
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
    }  
    mt_srand(10000000*(double)microtime());  
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {  
        $str .= $chars[mt_rand(0, $lc)];  
    }  
    return $str;  
} 