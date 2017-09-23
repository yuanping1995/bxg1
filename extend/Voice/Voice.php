<?php
namespace Voice;
/**
 *+------------------------------------------------------
 *                生成图片处理URL口
 *+------------------------------------------------------
 *                       使用方法:传入音频地址即可
			 	$Voice  = new \Voice\Voice();
				dump($Voice->voice('./test.pcm'));
 *+------------------------------------------------------
 */

class Voice {
	private static $config = array('cuid' => '10111513', 'apiKey' => 'TlHACd5oOOvqXhfkkONwNyPG', 'secretKey' => '8c5f7cd518925e758e65acc02ec539c9');

	public static function voice($file) {
		$auth_url = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=" . self::$config['apiKey'] . "&client_secret=" . self::$config['secretKey'];
		$voice_url = $url = "http://vop.baidu.com/server_api?cuid=" . self::$config['cuid'] . "&token=" . self::voiceCurl($auth_url);
		return self::voiceCurl($voice_url,$file);
	}

	private static function voiceCurl($url,$file='') {
		$ch = curl_init();
		if(!empty($file))
		{
			$audio = file_get_contents($file);
			$content_len = "Content-Length: ".strlen($audio);
			$header = array ($content_len,'Content-Type: audio/pcm; rate=8000',);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $audio);
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
			print curl_error($ch);
		}
		curl_close($ch);
		$response = json_decode($response, true);
		return isset($response['access_token'])?$response['access_token']:$response;
	}

}
