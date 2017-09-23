<?php
namespace emile;
/*
 *
 * PHP常用功能助手
 * @author       郑涛<857351164@qq.com>
 * @version      1.0
 * @since        1.0
 */
//$a = new email();
//$a->E('857351166@qq.com','nicai','nicai');

class email  {

	public function E($tarGet, $title, $content, $chent = '') {
		include 'phpmailer/class.phpmailer.php';
		$config = [
	
		//发送主机
		'Host'=>"smtp.qq.com",
		//端口号
		'Port'=>587,
		//发送账号  不用写 @qq.com
		'Username'=>'1019979673',
		//邮箱独立密码
		'Password'=>'grbdzohpilfnbbif',
		//发件人账号
		'From'=>'1019979673@qq.com',
		//发件人昵称
		'FromName'=>'Tao',
		//默认回复地址
		'ReplyTo'=>'1019979673@qq.com',
		//回复姓名
		'ReplyToName'=>'Tao'
	];
		try {
			$mail = new \PHPMailer(true);
			
			$mail -> IsSMTP();
			$mail -> SMTPSecure = 'ssl';
			$mail -> CharSet = 'UTF-8';
			$mail -> SMTPAuth = true;
			//开启认证
			$mail -> Port = 465;
			//网易为25

			$mail -> Host = $config['Host'];
			$mail -> Username = $config['Username'];
			//qq此处为邮箱前缀名  163为邮箱名
			$mail -> Password = $config['Password'];
			$mail -> AddReplyTo($config['ReplyTo'], $config['ReplyToName']);
			//回复地址
			$mail -> From = $config['From'];
			$mail -> FromName = $config['FromName'];

			$mail -> AddAddress($tarGet);
			$mail -> Subject = $title;
			$mail -> Body = $content;
			$mail -> AltBody = "抱歉你使用邮箱不支持HTML模式,查看该消息,HTML兼容的电子邮件查看器!";
			//当邮件不支持html时备用显示
			$mail -> WordWrap = 80;
			// 设置每行字符串的长度
			if (!empty($chent)) {
				$mail -> AddAttachment($chent);
				//可以添加附件
			}
			$mail -> IsHTML(true);
			$mail -> Send();
			echo '邮件发送成功';
		} catch (phpmailerException $e) {
			//			return 2;
			echo "邮件发送失败：" . $e -> errorMessage();
		}
	}

}
