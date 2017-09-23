<?php
$appid='wxa57215e710d7bd39';
$redirect_uri = urlencode('http://123.207.163.87/wx2.php' );
$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
header("Location:".$url);