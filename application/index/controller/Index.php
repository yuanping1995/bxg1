<?php
namespace app\index\controller;
use Excel\Excelf;
use Verify\Verify;
use Voice\Voice;
use Zip\Zip;
use Time\Time;
use Catalog\Catalog;
use ArrayHelper\ArrayHelper;
use Message\api_demo\SmsDemo;
//use Thirdlogin\QC;
use kuange\qqconnect\QC;
//use QRcode\QRcode;
use Endroid\QrCode\QrCode;
use think\captcha\Captcha;
use emile\email;
use express\express;
use  xss\xss;
use wchat\Wechat;
use wchat\Snoopy;
use wx\wx;
class Index
{

    public function index(){
////        dump("144145455");
//        return view('index',['name'=>'thinkphp']);
//        $content = ob_get_contents();//取得php页面输出的全部内容
//        $fp = fopen("./public/tmp/new/0001.html", "w");
//        fwrite($fp, $content);
//        fclose($fp);

            $options = array(
                'token' => '0oPIuuWv1zK8', // 填写你设定的token
                'appid' => 'wxa57215e710d7bd39', // 填写高级调用功能的appid
                'appsecret' => '0602d5aac7eafb6b2eafdc67fe7269eb' // 填写高级调用功能的密钥
            );
//这两个类我已放到附件，有需要可以下载
//        Vendor('weixin.Wechat');
//        Vendor('weixin.Snoopy');
            $weObj = new Wechat($options);

            $weObj->valid(); // 验证key
            //获取菜单操作:
            $menu = $weObj->getMenu();
            //设置菜单
            $newmenu =  array(

                'button' => array (
                    0 => array (
                        'name' => '扫码',
                        'sub_button' => array (
                            0 => array (
                                'type' => 'scancode_waitmsg',
                                'name' => '扫码带提示',
                                'key' => 'rselfmenu_0_0',
                            ),
                            1 => array (
                                'type' => 'scancode_push',
                                'name' => '扫码推事件',
                                'key' => 'rselfmenu_0_1',
                            ),
                        ),
                    ),
                    1 => array (
                        'name' => '发图',
                        'sub_button' => array (
                            0 => array (
                                'type' => 'pic_sysphoto',
                                'name' => '系统拍照发图',
                                'key' => 'rselfmenu_1_0',
                            ),
                            1 => array (
                                'type' => 'pic_photo_or_album',
                                'name' => '拍照或者相册发图',
                                'key' => 'rselfmenu_1_1',
                            ),
                            2 => array (
                                'type' => 'view',
                                'name' => 'baidu',
                                'url' => 'http://www.baidu.com',
                                'key' => 'rselfmenu_1_2',
                            ),
                        ),
                    ),
                   2 => array (
                        'name' => '微信登录',
                        'sub_button' => array (
                            0 => array (
                                'type' => 'view',
                                'name' => '微信登录',
                                'url' => 'http://123.207.163.87/index.php/login/INDEX/wx',
                                'key' => 'rselfmenu_1_0',
                            ),
                            1 => array (
                                'type' => 'pic_photo_or_album',
                                'name' => '拍照或者相册发图',
                                'key' => 'rselfmenu_1_1',
                            ),
                            2 => array (
                                'type' => 'view',
                                'name' => 'baidu',
                                'url' => 'http://www.baidu.com',
                                'key' => 'rselfmenu_1_2',
                            ),
                        ),
                    ),



                ),

            );
            $result = $weObj->createMenu($newmenu);

//获得用户发送过来的消息的类型，有"text","music","image"等
            $type = $weObj->getRev()->getRevType();

        }
        /*
         * 图文推送消息index
         *
         *
         */
    public function tsxx(){
        $raa = new wx();
        $postdata ='{"touser":"o5BkRs_vRwfPqAb1ceXHfJDzmQ5o","msgtype":"text","text":{"content":"Hello World"}}';
        $opts = array(
            'http' => array(
                'method' => 'POST',
                'Content-Length' => strlen($postdata),
                'Host' => 'api.weixin.qq.com',
                'Content-Type' => 'application/json',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $oder_re = $raa->access_token("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxa57215e710d7bd39&secret=0602d5aac7eafb6b2eafdc67fe7269eb");

        $result = file_get_contents('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$oder_re['access_token'], true, $context);
        echo $result;
    }
        public function aa(){
            //请求url地址
            $appId = 'wxa57215e710d7bd39';
            $appSecret = '0602d5aac7eafb6b2eafdc67fe7269eb';
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appSecret;
//初始化curl
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//跳过证书验证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//3.设置参数
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//4.调用接口

            $res = curl_exec($ch);
            if(curl_errno($ch)){
                var_dump(curl_error($ch));
            }
            $resArr = json_decode($res,1);
            var_dump($resArr);
//5.关闭curl
            curl_close($ch);

        }
    public function dd(){
        $access_token = '3IAogtlQGnW-i7gQUzEbGL5Gz_Ki7aiqBPLsNQRQtjeK5CKmqE9dBq-YPDTgA-CO8wKHWM_jlppXqw4l5i4nKLLBbg_UE_PxgSV5Bt3TdQw1zfmM6XHDF_9VQzlIBSJSWHFgAAAGRP';

//$ac = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=SECRET');
//$wxt = json_decode($ac,true);
$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' .$access_token;//access_token改成你的有效值

$data = array(
    'first' => array(
        'value' => '有一名客户进行了一次预约！'
    ),
    'keyword1' => array(
        'value' => '2015/10/5 14:00~14:45'
    ),
    'keyword2' => array(
        'value' => '都会型SPA'
    ),
    'keyword3' => array(
        'value' => '1cvvvv'
    ),
    'keyword4' => array(
        'value' => '上海市浦东新区XXXXSPA馆'
    ),

    'remark' => array(
        'value' => '请您务必准时到场为客户提供SPA服务！'
    )
);
$template_msg=array('touser'=>'onusmwHsXyidTSfMIDxWismGqJiI','template_id'=>'PUYvSEvSMBG1VxeMHxHrDNiBEddbkyJ1Y3BvWOlxAE8','topcolor'=>'#FF0000','data'=>$data);

$curl = curl_init($url);
$header = array();
$header[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
// 不输出header头信息
curl_setopt($curl, CURLOPT_HEADER, 0);
// 伪装浏览器
curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
// 保存到字符串而不是输出
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// post数据
curl_setopt($curl, CURLOPT_POST, 1);
// 请求数据
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($template_msg));
$response = curl_exec($curl);
curl_close($curl);
dump($response) ;
    }

    public function emial(){
        $a = new email();
        $a->E('1019979673@qq.com','nicai','nicai');

    }
    public function express(){
        $express = new express();
        $arr = $express->exress('1202516745301','auto');
        dump($arr);
    }
    public function view()
    {
        $qrCode=new QrCode();
        $url = 'https://www.baidu.com';//加http://这样扫码可以直接跳转url
        $qrCode->setText($url)
            ->setSize(300)//大小
            ->setLabelFontPath(VENDOR_PATH.'endroid\qrcode\assets\noto_sans.otf')
            ->setErrorCorrectionLevel('high')
          //  ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabel('推广码')
            ->setLabelFontSize(16);
        header('Content-Type: '.$qrCode->getContentType());
//        dump($qrCode->writeString());
        $qrCode->writeString();
        return view('view',['name'=>'thinkphp']);

    }
    public function Verification(){
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,
            // 验证码位数
            'length'      =>    3,
            // 关闭验证码杂点
            'useNoise'    =>    false,
        ];
        $captcha = new Captcha();
       return $captcha->entry();
//        echo "<div><img src=". $captcha->entry()." alt=\"captcha\" /></div>";
    }
    public function mas(){
//        dump(config("alyun")['AccessKeyId']);
//        exit;
        header('Content-Type: text/plain; charset=utf-8');
        $demo = new SmsDemo(
            config("alyun")['AccessKeyId'],
            config("alyun")['AccessKeySecret']

        );
        $response = $demo->sendSms(
            "小袁科技", // 短信签名
            "SMS_95460068", // 短信模板编号
            "18233297067", // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>"12345"
            ),
            "123"
        );
        var_dump($response->Message);
    }
    public function xssa(){
        $xss = new xss();
        $res_xss = $xss->remove_xss("18233297067");
        dump($res_xss);
    }
    public function img(){
        $image = \think\Image::open('./illness.png');
//将图片裁剪为300x300并保存为crop.png
        $image->thumb(40, 40)->save('./crop.png');
    }
    public function phone(){
        $phone_Number = new Verify();
        $Result = $phone_Number->phone_Number("18233297067");
        var_dump($Result);
        $email = $phone_Number->email("1019979673@qq.com");
        var_dump($email);
    }
    public function v(){
        $Voice  = new Voice();
        dump($Voice->voice('./test.pcm'));
    }
    public function password(){
        $phone_Number = new Verify();
        $password  = $phone_Number->password("1823329s7067","15282s751103");
        dump($password);
    }
    public function addzip(){
        $addZip = new Zip();
        $arr = $addZip->Zip('./','ces.zip');
//        $addZip->ZipAndDownload("./");

    }
    public function decompression(){
        $decompression = new  Zip();
        $arr  = $decompression->decompression("./cs/ces.zip","asd/");
    }
    public function time(){
        $arr = new  Time();
//        $arrive= $arr->getyear("1505356272",1);
        $arrive= $arr->getmonth("1505356272",3);
        dump($arrive);
    }
    public function forcemkdir(){
        $forcemkdir = new Catalog();
//       $aa = mkdir('./cs');
//       dump($aa);
//       $arr = $forcemkdir->forcemkdir("./cs2");
//       $arr = $forcemkdir->cleardir("./cs");
//        dump(is_dir("./15"));
//       $arr = $forcemkdir->edie_Name("./145.docx",'./1415.docx');
       $arr = $forcemkdir->read_Directory("./yuan");
       dump($arr);
    }
    public function arraya(){
        $arr = array(
	   array(1, '1-1'),
	    array( 2, '2-1'),
	  );


//        ArrayHelper::removeEmpty($arr);

	  $hashmap = ArrayHelper::groupBy($arr, 0);
         dump($arr);
    }
    public function login(){
         $qc = new QC();
         return redirect($qc->qq_login());
            }
    public function asd(){
        $image = \think\Image::open('./illness.png');
// 返回图片的宽度
        $width = $image->width();
// 返回图片的高度
        $height = $image->height();
// 返回图片的类型
        $type = $image->type();
// 返回图片的mime类型
        $mime = $image->mime();
// 返回图片的尺寸数组 0 图片宽度 1 图片高度
        $size = $image->size();
        dump($size);
    }

}
