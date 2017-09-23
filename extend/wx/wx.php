<?php
namespace wx;


class wx
{
    public function access_token($url = '',$appsecret = ''){
       // $appid="wxa57215e710d7bd39";
        //$appsecret="0602d5aac7eafb6b2eafdc67fe7269eb";
        // $json_token=http_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
// 我们在POST数据哦！
        curl_setopt($ch, CURLOPT_POST, 1);
// 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data=array());
        $output = curl_exec($ch);
        curl_close($ch);
        $access_token=json_decode($output,true);
//        dump($access_token);//exit;
//获得access_token
       // $this->access_token=$access_token['access_token'];
        return $access_token;
    }
}