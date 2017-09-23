<?php
/*
基站云接口调用实例
www.jizhanyun.com
全国热线：400-009-1663
客服QQ:742631333
*/

header("Content-type:text/html;charset=utf-8"); //UTF8编码
$apikey="6b4bffae320762be49d5032927c8e93a"; //设置APIKEY
$mnc=0;    //0移动 1联通
$lac=2;    //大区号
$cell=2;   //小区号


$apiurl="http://www.jizhanyun.com/api/test.php?mnc=&lac=&cell=&ishex=10&apikey=6b4bffae320762be49d5032927c8e93a&user_id=1793";
$data=curl_file_get_contents($apiurl);
$json=json_decode($data);
$code=$json->code;      //状态编号
$about=$json->about;      //状态描述

if($code=='001'){
    $data=$json->data[0];    //数据集
    $lng=$data->lng;         //纬度
    $lat=$data->lat;         //经度
    $glng=$data->glng;       //谷歌纬度
    $glat=$data->glat;       //谷歌经度
    $address=$data->address; //地址描述


    //输出结果
    echo "<b>基站云定位结果：</b><br/>";
    echo "纬度:".$lng."<br/>";
    echo "经度:".$lat."<br/>";
    echo "谷歌纬度:".$glng."<br/>";
    echo "谷歌经度:".$glat."<br/>";
    echo "地址描述:".$address."<br/>";
}else{
    echo "返回结果：".$about;
}




//HTTP请求函数
function curl_file_get_contents($durl){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $durl);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
   // curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
    //curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}
?>