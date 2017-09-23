<?php


// 定义应用目录
define('APP_PATH', __DIR__ . '/./application/');
define( '__APPURL__', trim( 'http://' . $_SERVER['HTTP_HOST'] . '/' . trim( $_SERVER['REQUEST_URI'], '/\\' ), '/' ) );
define( 'IS_QQ', isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( $_SERVER['HTTP_USER_AGENT'], 'QQ' ) !== FALSE );
define( 'IS_WEIXIN', isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( $_SERVER['HTTP_USER_AGENT'], 'MicroMessenger' ) !== FALSE );
define( 'IS_ALIPAY', isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( $_SERVER['HTTP_USER_AGENT'], 'AlipayClient' ) !== FALSE );
define( '__APPROOT__', trim( 'http://' . $_SERVER['HTTP_HOST'] . dirname( $_SERVER['SCRIPT_NAME'] ), '/\\' ) );
define('APP_AUTO_BUILD', true);
// 加载框架引导文件
require __DIR__ . '/./thinkphp/start.php';

// \think\Build::module('index');
//生成目录
//调试模式

//define("APP_DEBUG", false);
