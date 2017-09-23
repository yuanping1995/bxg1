<?php
namespace app\admin\controller;
use think\Db;
use think\Reinputuest;
class Upload extends Base
{
public $def_dir;
	public function uploadimg() {
		$img =new \Upload\Upload();
/*		$img->soluetu=array(
		
		array(60,60,'_small'),
		array(350,350,'_mid'),
		array(800,800,'')
		);*/
		$date="goodsimg/".date("Ymd");
		$upimg=$img->images(1,$date);
		$msg['status'] = 200;
		//定义命名格式 定义上传至目录下那个文件夹
		$msg['url'] = substr($upimg,1);
//		echo $upimg;
		echo json_encode($msg);
		die ;
	}
	public function images(){
		$img =new \Upload\Upload();
/*		$img->soluetu=array(
		
		array(60,60,'_small'),
		array(350,350,'_mid'),
		array(800,800,'')
		);*/
		$date="sellerlogo/".date("Ymd");
		$upimg=$img->images(1,$date);
		$msg['status'] = 200;
		//定义命名格式 定义上传至目录下那个文件夹
		$msg['url'] = substr($upimg,1);
//		echo $upimg;
		echo json_encode($msg);
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
