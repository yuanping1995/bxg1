<?php
namespace app\admin\controller;
class Ueditor
{
 public $CONFIG;
    public function contr(){
        $this->CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("./static/admin/ueditor/php/config.json")), true);
        $action = $_GET['action'];

        switch ($action) {
            case 'config':
                $result =  json_encode($this->CONFIG);
                break;

            /* 上传图片 */
            case 'uploadimage':
            /* 上传涂鸦 */
            case 'uploadscrawl':
            /* 上传视频 */
            case 'uploadvideo':
            /* 上传文件 */
            case 'uploadfile':
//                $result = include("action_upload.php");
                $result = $this->ActionUpload();
                break;

            /* 列出图片 */
            case 'listimage':
                $result = include("action_list.php");
//                $result = $this->ActionList;


                break;
            /* 列出文件 */
            case 'listfile':
                $result = include("action_list.php");
//                $result = $this->ActionList;
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = include("action_crawler.php");
//                $result = $this->ActionCrawler;
                break;

            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            
            echo $result;
        }
    }
    
    public function ActionUpload(){
//        include "Uploader.class.php";
		$imgname =new \Upload\Upload();
        /* 上传配置 */
        $base64 = "upload";
        switch (htmlspecialchars($_GET['action'])) {
            case 'uploadimage':

                $config = array(
                    "pathFormat" => '/images/goods/goods_center/'.date("Ymd").'/'.$imgname->namedefinition(1),
                    "maxSize" => $this->CONFIG['imageMaxSize'],
                    "allowFiles" => $this->CONFIG['imageAllowFiles']
                );
                $fieldName = $this->CONFIG['imageFieldName'];
                break;
            case 'uploadscrawl':
                $config = array(
                    "pathFormat" => $this->CONFIG['scrawlPathFormat'],
                    "maxSize" => $this->CONFIG['scrawlMaxSize'],
                    "allowFiles" => $this->CONFIG['scrawlAllowFiles'],
                    "oriName" => "scrawl.png"
                );
                $fieldName = $this->CONFIG['scrawlFieldName'];
                $base64 = "base64";
                break;
            case 'uploadvideo':
                $config = array(
                    "pathFormat" => $this->CONFIG['videoPathFormat'],
                    "maxSize" => $this->CONFIG['videoMaxSize'],
                    "allowFiles" => $this->CONFIG['videoAllowFiles']
                );
                $fieldName = $this->CONFIG['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $config = array(
                    "pathFormat" => $this->CONFIG['filePathFormat'],
                    "maxSize" => $this->CONFIG['fileMaxSize'],
                    "allowFiles" => $this->CONFIG['fileAllowFiles']
                );
                $fieldName = $this->CONFIG['fileFieldName'];
                break;
        }
 
        /* 生成上传实例对象并完成上传 */
        $up =new \Ueditor\Uploader($fieldName, $config, $base64);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */

        /* 返回数据 */
		$status = $up->getFileInfo();
		if($status['state'] =='SUCCESS')
		{
			$status['url'] = $this->upload('goods/goods_center',$status['url'],$status['title']);
			
		}
        return json_encode($status);
    }
	public function upload($mk_dir,$file,$name){
		//$img =new \Upload\Upload();
		$date=$mk_dir . "/" . date("Ymd");
		//$bendi = $img->images(1,$date);
		$bendi = $date.'/'.$name;
		$config=config('aliyun_oss');
		$dir= str_replace(array("/./application/"),"",APP_PATH);
		$dir .= ltrim($file,'.');
		$filename = ltrim($bendi,'./');
		if($this->uploadFile($config['Bucket'],$filename,$dir))
		{
			$upimg="http://".$config['Bucket'].'.'.$config['Endpoint'].'/'.$filename;
		}else{
			$upimg = $bendi;
		}
		return $upimg;
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
	function new_oss(){
			//获取配置项，并赋值给对象$config
			$config=config('aliyun_oss');
			//实例化OSS
			$oss=new \OSS\OssClient($config['KeyId'],$config['KeySecret'],$config['Endpoint']);
			return $oss;
	}

	
}
