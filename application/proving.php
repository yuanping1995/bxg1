<?php

//------------------------
// ThinkPHP 验证函数
//-------------------------
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
    return 'bxg'.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
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
     if($asc>=-13318&&$asc<=-12839) return 'Time';
     if($asc>=-12838&&$asc<=-12557) return 'W';
     if($asc>=-12556&&$asc<=-11848) return 'X';
     if($asc>=-11847&&$asc<=-11056) return 'Y';
     if($asc>=-11055&&$asc<=-10247) return 'Z';
     return null;
  } 
/**
 * 获取整条字符串汉字拼音首字母
 * @param $zh
 * @return string
 */
function pinyin_long($zh){
    $ret = "";
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    for($i = 0; $i < strlen($zh); $i++){
        $s1 = substr($zh,$i,1);
        $p = ord($s1);
        if($p > 160){
            $s2 = substr($zh,$i++,2);
            $ret .= getFirstCharter($s2);
        }else{
            $ret .= $s1;
        }
    }
    return $ret;
}
  // 阿拉伯数字转中文表述，如101转成一百零一
function caps_look($number) {
  $number = intval ( $number );
  $capnum = array ("零","一","二","三","四","五","六","七","八","九" );
  $capdigit = array ("","十","百","千","万" );
  $data_arr = str_split ( $number );
  $count = count ( $data_arr );
  for($i = 0; $i < $count; $i ++) {
    $d = $capnum [$data_arr [$i]];
    $arr [] = $d != '零' ? $d . $capdigit [$count - $i - 1] : $d;
  }
  $cncap = implode ( "", $arr );
  $cncap = preg_replace ( "/(零)+/", "0", $cncap ); // 合并连续“零”
  $cncap = trim ( $cncap, '0' );
  $cncap = str_replace ( "0", "零", $cncap ); // 合并连续“零”
  $cncap == '一十' && $cncap = '十';
  $cncap == '' && $cncap = '零';
  // echo ( $data.' : '.$cncap.' <br/>' );
  return $cncap;
}
/**
 * 时间戳转时间
 *
 * @param unknown $time_stamp            
 */
function getTimeStampTurnTime($time_stamp)
{
    if ($time_stamp > 0) {
        $time = date('Y-m-d H:i:s', $time_stamp);
    } else {
        $time = "";
    }
    return $time;
}

/**
 * 时间转时间戳
 *
 * @param unknown $time            
 */
function getTimeTurnTimeStamp($time)
{
    $time_stamp = strtotime($time);
    return $time_stamp;
}