<?php

$host = "http://jisukdcx.market.alicloudapi.com";
$path = "/express/query";
$method = "GET";
$appcode = "ddb44c356de344649644c7f98206b8a7";
$headers = array();
array_push($headers, "Authorization:APPCODE " . $appcode);
$querys = "number=1202516745301&type=auto";
$bodys = "";
$url = $host . $path . "?" . $querys;

$curl = curl_init();
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_FAILONERROR, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HEADER, true);
if (1 == strpos("$".$host, "https://"))
{
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
}
var_dump(curl_exec($curl));
