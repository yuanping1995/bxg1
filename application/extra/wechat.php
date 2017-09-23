<?php
//微信配置
return [
// wx2d3955a79caf7b3a
// 8f7305d889491844097a29c959047c4b
    'token'          => 'bxgogo', //填写你设定的token
    'appid'          => 'wx38c09ae77f8484e7', //填写高级调用功能的app id, 请在微信开发模式后台查询
    'appsecret'      => 'd832dc1e6695e260b72a1531f5c8443a', //填写高级调用功能的密钥
    'encodingaeskey' => 'U6jjw5W7ZYO9uW4U3f9j05jJfstq4BbH1gVdn1jEvk4', //填写加密用的EncodingAESKey（可选，接口传输选择加密时必需）
    'mch_id'         => '1299733701',  //微信支付，商户ID（可选）
    'partnerkey'     => '03125658992wih8267688Adyxkdzswyx',  //微信支付，密钥（可选）
    'ssl_cer'        => ROOT_PATH  . '/cert/apiclient_cert.pem', //微信支付，双向证书（可选，操作退款或打款时必需）
    'ssl_key'        => ROOT_PATH  . '/cert/apiclient_key.pem',  //微信支付，双向证书（可选，操作退款或打款时必需）
    'cachepath'      => '', //设置SDK缓存目录（可选，默认位置在./Wechat/Cache下，请保证写权限）
];