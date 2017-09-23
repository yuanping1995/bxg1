<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"D:\phpStudy\WWW/./application/admin\view\index\login.html";i:1505975330;}*/ ?>
<!--Author: W3layouts
Author URL: http://www.htmlsucai.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>后台登录</title>
<!-- Custom Theme files -->

<!-- Custom Theme files -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<meta name="keywords" content="后台登录" />
<!--Google Fonts-->
<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
--><!--Google Fonts-->
<style type="text/css">
/*--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
--*/
/* start editing from here */
html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,dl,dt,dd,ol,nav ul,nav li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline;}
article, aside, details, figcaption, figure,footer, header, hgroup, menu, nav, section {display: block;}
ol,ul{list-style:none;margin:0;padding:0;}
blockquote,q{quotes:none;}
blockquote:before,blockquote:after,q:before,q:after{content:'';content:none;}
table{border-collapse:collapse;border-spacing:0;}
/* start editing from here */
a{text-decoration:none;}
.txt-rt{text-align:right;}/* text align right */
.txt-lt{text-align:left;}/* text align left */
.txt-center{text-align:center;}/* text align center */
.float-rt{float:right;}/* float right */
.float-lt{float:left;}/* float left */
.clear{clear:both;}/* clear float */
.pos-relative{position:relative;}/* Position Relative */
.pos-absolute{position:absolute;}/* Position Absolute */
.vertical-base{	vertical-align:baseline;}/* vertical align baseline */
.vertical-top{	vertical-align:top;}/* vertical align top */
.underline{	padding-bottom:5px;	border-bottom: 1px solid #eee; margin:0 0 20px 0;}/* Add 5px bottom padding and a underline */
nav.vertical ul li{	display:block;}/* vertical menu */
nav.horizontal ul li{	display: inline-block;}/* horizontal menu */
img{max-width:100%;}
/*end reset*/
/*--header start here--*/
body{
  font-family: 'Montserrat', sans-serif;
   font-size: 100%;
   background: url(/static/admin/images/bbb.jpg)no-repeat;
       background-size: cover;
}
.login-form{
	
	padding:100px 0px 50px 0px;

}
.login-form h1{
	font-size:2em;
	color:#fff;
	font-weight:800;
	text-transform:uppercase;
	text-align:center;
	margin-bottom:2em;
/*-- w3layouts --*/
}
.top-login {
    width: 130px;
    height: 130px;
    display: block;
    -webkit-transform: rotate(45deg) translate3d(0, 0, 0);
    -moz-transform: rotate(45deg) translate3d(0, 0, 0);
    -ms-transform: rotate(45deg) translate3d(0, 0, 0);
    -o-transform: rotate(45deg) translate3d(0, 0, 0);
    transform: rotate(45deg) translate3d(0, 0, 0);
    margin: 0 auto 4em;
    background: #fff;
    position: relative;
}
.top-login span{
    border: 2px solid #F36;
    width: 105px;
    height: 105px;
    display: block;
    margin: 0px auto;
    position: absolute;
    top: 11px;
    left: 11px;
}
.top-login span img{
       -webkit-transform: rotate(-45deg) translate3d(0, 0, 0);
    -moz-transform: rotate(-45deg) translate3d(0, 0, 0);
    -ms-transform: rotate(-45deg) translate3d(0, 0, 0);
    -o-transform: rotate(-45deg) translate3d(0, 0, 0);
    transform: rotate(-1deg) translate3d(0, 0, 0);
    margin: 0;
}
/*----*/

.login-top{
	width: 460px;
	display: block;
	margin: 0 auto;
}
.login-ic {
    background: rgba(255, 255, 255, 0.32); 
	margin-bottom:1.5em;
    padding: 8px;	
}
.login-ic i {
        background: url(/static/admin/images/m.png)no-repeat 6px 6px;
    width: 38px;
    height: 38px;
    float: left;
	/*-- agileits --*/
    display: inline-block;
}
.login-ic i.icon {
    background: url(/static/admin/images/l.png)no-repeat 6px 6px;  
}
.login-ic input[type="text"],.login-ic  input[type="password"] {
	    float: left;
    background: none;
    outline: none;
    font-size: 15px;
    font-weight: 400;
    color: #fff;
    padding: 10px 16px;
    border: none;
    border-left: 1px solid #fff;
    width: 82%;
    display: inline-block;
    margin-left: 7px;
}
.log-bwn {
	text-align: center;
}
.log-bwn input[type="submit"] {
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    padding: 13px 0;
    background: #ff3366;
    display: inline-block;
    width: 100%;
	outline:none;
	border:2px solid #ff3366;
	cursor:pointer;
	text-transform:uppercase;
}
.log-bwn input[type="submit"]:hover{
	transition: all 0.3s ease;
     -webkit-transition: all 0.3s ease;
     -moz-transition: all 0.3s ease;
     -o-transition: all 0.3s ease;
	 border:2px solid #fff;
}
/*-- profile_2 --*/
p.copy{
	color:#fff;
	font-size:1em;
	text-align:center;
	margin-top:6em;
}
/*-- w3layouts --*/
p.copy a{
	color:#fff;
	text-decoration:underline;
}
p.copy a:hover{
	text-decoration:none;
}

/*--meadia quiries start here--*/
@media (max-width:1024px){
p.copy {
    margin-top: 3.5em;
}
.login-form {
    padding: 60px 0px 40px 0px;
}
}

@media (max-width:768px){
body {
    min-height: 929px;
}
}
@media (max-width:768px){
body {
    min-height: auto;
}
}
@media (max-width:600px){
.login-top {
    width: 425px;
}
.login-ic input[type="text"], .login-ic input[type="password"] {
    width: 79%;
}
}
@media (max-width:480px){
p.copy {
    font-size: 0.9em;
	padding:0 0.5em;
	line-height: 1.8em;
}
}
@media (max-width:450px){
.login-top {
    width: 360px;
}
.login-ic input[type="text"], .login-ic input[type="password"] {
    width: 76%;
	/*-- agileits --*/
}
.top-login {
    width: 90px;
    height:90px;
    margin: 0 auto 3em;
}
.top-login span img {
    margin: 5px 0 0 5px;
}
.top-login span {
    width: 75px;
    height: 75px;
    top: 5px;
    left: 5px;
}
.login-form h1 {
    margin-bottom: 1em;
    font-size: 1.7em;
}
p.copy {

    margin-top: 3em;
}
.login-form {
    padding: 80px 0px 45px 0px;
}
.login-ic {
    margin-bottom: 1em;
    padding: 3px;
}
.log-bwn input[type="submit"] {
    padding: 10px 0;
}
p.copy {
    font-size: 0.8em;
}
body {
    min-height: 672px;
}
}
@media (max-width:384px){
.login-top {
    width: 340px;
}
.login-ic input[type="text"], .login-ic input[type="password"] {
    width: 75%;
}
body {
    min-height: 600px;
}
}
@media(max-width:320px){
.login-top {
    width: 280px;
}
.login-ic input[type="text"], .login-ic input[type="password"] {
    width: 70%;
}
.login-form {
    padding: 50px 0px 45px 0px;
}
body {
    min-height: 540px;
}
}

/*--media quiries end here--*/
</style>
</head>
<body>
<!--header start here-->
<div class="login-form">
			<div class="top-login">
				<span><img src="/static/admin/images/group.jpg" alt=""/></span>
			</div>
			<h1>反馈问题请联系郑涛!<span style="color:pink; font-size:14px;">Tip:二维码_微信<span></h1>
			<div class="login-top">
			<form action="/admin/index/login" method="POST">
				<div class="login-ic">
					<i ></i>
					<input name="admin" type="text"  value="用户" onFocus="this.value = '';" onBlur="if (this.value == '') {this.value = 'User name';}"/>
					<div class="clear"> </div>
				</div>
				<div class="login-ic">
					<i class="icon"></i>
					<input name="password" type="password"  value="密码" onFocus="this.value = '';" onBlur="if (this.value == '') {this.value = 'password';}"/>
					<div class="clear"> </div>
				</div>
			
				<div class="log-bwn">
					<input type="submit"  value="Login" >
				</div>
				</form>
			</div>
			<p class="copy">(c) 2017</p>
</div>		
<!--header start here-->
</body>
</html>