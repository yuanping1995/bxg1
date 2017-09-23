<?php
namespace app\admin\controller;
use think\Db;
use think\Reinputuest;
class Upload extends Base
{
	public $def_dir;
	
	function new_oss(){
			//获取配置项，并赋值给对象$config
			$config=config('aliyun_oss');
			//实例化OSS
			$oss=new \OSS\OssClient($config['KeyId'],$config['KeySecret'],$config['Endpoint']);
			return $oss;
	}	
	public function upload($mk_dir){
		$img =new \Upload\Upload();
		$date='/'.$mk_dir . "/" . date("Ymd").'/';
		$bendi = $img->images(1,$date);
		$config=config('aliyun_oss');
		$dir= str_replace(array("/./application/"),"",APP_PATH);
		$dir .= ltrim($bendi,'.');
		$filename = ltrim($bendi,'./');
		if($this->uploadFile($config['Bucket'],$filename,$dir))
		{
			//OSS原地址
			//$upimg="http://".$config['Bucket'].'.'.$config['Endpoint'].'/'.$filename;
			//加速地址
			$upimg="http://img.bxgogo.com/".$filename;
		}else{
			$upimg = $bendi;
		}
		return $upimg;
	}
	//分类图片
	public function levelimg(){
			$upimg =$this->upload('levelimg');
			$msg['status'] = 200;
			$msg['url'] = $upimg;
			return json($msg);
	}
	//品牌图片
	public function brandimg(){
		$upimg =$this->upload('brandimg');
		$msg['status'] = 200;
		$msg['url'] = $upimg;
		return json($msg);
	}
	//商家logo
	public function seller_logo(){
		$upimg =$this->upload('seller/seller_logo');
		$msg['status'] = 200;
		$msg['url'] = $upimg;
		return json($msg);
	}
	//商家门头照
	public function seller_Facade(){
		$upimg =$this->upload('seller/seller_Facade');
		$msg['status'] = 200;
		$msg['url'] = $upimg;
		return json($msg);
	}
	//商品主图
	public function goods_img(){
		$upimg =$this->upload('goods/goods_img');
		$msg['status'] = 200;
		$msg['url'] = $upimg;
		return json($msg);
	}
	//商品主图
	public function goods_images(){
		$upimg =$this->upload('goods/goods_imgs');
		$msg['status'] = 200;
		$msg['url'] = $upimg;
		return json($msg);
	}
	//商品图片集
	public function goods_imgs(){
		header('Access-Control-Allow-Origin:*');
		$upimg =$this->upload('goods/goods_images');
		$msg['status'] = 200;
		$msg['url'] = $upimg;
		return json($msg);
	}
	//商品图片集
	public function goods_center(){
		$upimg =$this->upload('goods/goods_center');
		$msg['status'] = 200;
		$msg['url'] = $upimg;
		return json($msg);
	}
	//广告上传文件夹轮播广告等
	public function ad(){
		$upimg =$this->upload('ad');
		$msg['status'] = 200;
		$msg['url'] = $upimg;
		return json($msg);
	}
	public function uploadimg() {
		$img =new \Upload\Upload();
		$date="/goodsimg/".date("Ymd").'/';
		$bendi = $img->images(1,$date);
		 $config=config('aliyun_oss');
		$dir= str_replace(array("/./application/"),"",APP_PATH);
		$dir .= ltrim($bendi,'.');
		$filename = ltrim($bendi,'./');
		if($this->uploadFile($config['Bucket'],$filename,$dir))
		{
			$upimg="http://".$config['Bucket'].'.'.$config['Endpoint'].'/'.$filename;
		}else{
			$upimg = $bendi;
		}
			$msg['status'] = 200;
			$msg['url'] = $upimg;//substr($upimg,1);
			return json($msg);

		die ;
	}
	

function uploadFile($bucket,$object,$Path){
    //try 要执行的代码,如果代码执行过程中某一条语句发生异常,则程序直接跳转到CATCH块中,由$e收集错误信息和显示
    try{
        //没忘吧，new_oss()是我们上一步所写的自定义函数
        $ossClient = $this->new_oss();
        //uploadFile的上传方法
        $ossClient->uploadFile($bucket, $object, $Path);
		unlink($Path);
    } catch(OssException $e) {
        //如果出错这里返回报错信息
        return $e->getMessage();
    }
    //否则，完成上传操作
    return true;
}
	
	public function images(){
		$img =new \Upload\Upload();

		$date="/sellerlogo/".date("Ymd").'/';
		$bendi = $img->images(1,$date);
		$config=config('aliyun_oss');
		$dir= str_replace(array("/./application/"),"",APP_PATH);
		$dir .= ltrim($bendi,'.');
		
		$filename = ltrim($bendi,'./');
		if($this->uploadFile($config['Bucket'],$filename,$dir))
		{
			$upimg="http://".$config['Bucket'].'.'.$config['Endpoint'].'/'.$filename;
		}else{
			$upimg = $bendi;
		}
			$msg['status'] = 200;
			$msg['url'] = $upimg;//substr($upimg,1);
			return json($msg);

		die ;
	}

	/*
	 * Layui 富文本编辑器上传图片方法
	 */

	public function uploadfuwenben() {
		$img =new \Tools\Upload();
		$aa=$img->images(2,'touxiang');
		$msg['code'] = 0;
		//0表示成功，其它失败
		$msg['status'] = 200;
		$msg['data']['src'] = __ROOT__ .'/'. $aa;
		$msg['data']['title'] = '图片加载错误';
		//可选
		$msg['msg'] = "上传失败";
		echo json_encode($msg);
		die ;
	}

	public function uptouxiang() {
		$base64_img = trim($_POST['image']);
		$time = date('Ymd');
		$atime = date('YmdHis');
		$strlen = rand(1000, 9999);
		$up_dir = './Public/image/touxiang/' . $time . '/';
		if (!file_exists($up_dir)) {
			mkdir($up_dir, 0777);
		}
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)) {
			$type = $result[2];
			if (in_array($type, array('pjpeg', 'jpeg', 'jpg', 'gif', 'bmp', 'png'))) {
				$new_file = $up_dir . $atime . $strlen . '.' . $type;
				if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))) {
					$img_path = str_replace('../../..', '', $new_file);
					$src = ltrim($img_path, '.');
					$msg['img'] = $src;
					$msg['result'] = 'ok';

				} else {
					$msg['result'] = 'no';
					$msg['str'] = '图片上传失败';

				}
			} else {
				$msg['result'] = 'no';
				$msg['str'] = '图片上传类型错误';
			}
		} else {
			$msg['result'] = 'no';
			$msg['str'] = '文件错误';
		}

		echo json_encode($msg);
	}
}
