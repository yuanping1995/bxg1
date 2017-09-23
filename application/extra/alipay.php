<?php

return [
	'partner'       => '2088421675557941',//合作身份者id，以2088开头的16位纯数字
	'seller_id'     => '2088421675557941',//收款支付宝账号，一般情况下收款账号就是签约账号
	'payment_type'  => '1',//支付类型
	'notify_url'    => '',//服务器异步通知页面路径
	'return_url'    => '',//页面跳转同步通知页面路径
	'sign_type'     => strtoupper( 'RSA2' ),//签名方式 不需修改
	'input_charset' => strtolower( 'utf-8' ),//字符编码格式
	'app_id'         => '2017021805736078',//应用ID
	'format'        => 'json',//仅支持JSON
	'it_b_pay'      => '5m',//笔订单允许的最晚付款时间
	'PrivateKeyFilePath'=> ROOT_PATH . 'cert/rsa_private_key.pem',//应用私钥
	'PublicKeyFilePath'=> ROOT_PATH . 'cert/alipay_public_key.pem',//支付宝公钥
	'PublicKeyli'=> 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAt5iLqOqU1uWyZ9Dq7kP6a9drLXJnZ/OQM3YN76T+kYNNyDUcXon2onj6ADNc4mDtKusaMqOe2x7k5FVbGxJnkKptcWVDMGl2R7EnxPloJ96R3xCGdf2nikS9CqQpcgHN7JNRelZ51zw5YXrODq6clDUSaydYG+EqYRgLI7ykAs4uddOg0i+w/5BeL6yfHE4WOaedXjND9nv2w/im/3QB6RHCQSdeVSZL4H0TVS7hj7J+1KS6FKPeLgM9AftkekSy2Gwp5jtFpvY6+fQyhBhyvpwsIzQtwgjUYM4OTGyqcJ9oCKVlhOAqcCctLGTNv5JqlLFLWFVpRaqkS+sBgvhrRwIDAQAB',//支付宝公钥
/*
阿里大于配置文件
*/
             'app_key'     => '23514547',//阿里大于APPKEY
	'secretKey'   => '798526bdd2e42e6ca3e332644b7d9dbf',//阿里大于secretKey
	'sign_name'   => '百信购',//你的短信签名
	'cachepath'      => '', //设置SDK缓存目录（可选，默认位置在./Wechat/Cache下，请保证写权限）
];