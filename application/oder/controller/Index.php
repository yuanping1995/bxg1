<?php
namespace app\oder\controller;
//use Snoopy\Snoopy;
//use Wechat\Wechat;
//use wchat\wechata;
use wx\wx;
class Index extends \think\Controller
{
    //构造一个请求函数
    function http_request($url,$data=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
// 我们在POST数据哦！
        curl_setopt($ch, CURLOPT_POST, 1);
// 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    public function asd(){
        $uname = array('onusmwGeZq_jBEKsVbpPb3J64CP8','onusmwHsXyidTSfMIDxWismGqJiI');
        for ($i=0;$i<2;$i++){
            $this->index($uname[$i]);
        }

    }
    public function oder_user(){
        $oder = new wx();
        $oder_re = $oder->access_token("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxa57215e710d7bd39&secret=0602d5aac7eafb6b2eafdc67fe7269eb");
        dump($oder_re['access_token']);
        //https://api.weixin.qq.com/cgi-bin/user/get?access_token=ACCESS_TOKEN&next_openid=NEXT_OPENID
        $oder_re1 = $oder->access_token("https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$oder_re['access_token']."&next_openid");
        dump($oder_re1['data']['openid']);
        for ($i=0;$i<count($oder_re1);$i++){
            $this->index($oder_re1['data']['openid'][$i]);
        }
    }

    public function index($unid=""){
        $appid="wxa57215e710d7bd39";
        $appsecret="0602d5aac7eafb6b2eafdc67fe7269eb";
       // $json_token=http_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxa57215e710d7bd39&secret=0602d5aac7eafb6b2eafdc67fe7269eb");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
// 我们在POST数据哦！
        curl_setopt($ch, CURLOPT_POST, 1);
// 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data=array());
        $output = curl_exec($ch);
        curl_close($ch);
        $access_token=json_decode($output,true);
        dump($access_token);//exit;
//获得access_token
        $this->access_token=$access_token['access_token'];
 echo $this->access_token;
//模板消息  onusmwGeZq_jBEKsVbpPb3J64CP8   onusmwHsXyidTSfMIDxWismGqJiI
        $template=array(
            'touser'=>$unid,
            'template_id'=>"sws7mOcIMAyeC-DBlvPuPl7QytqOGP9acXeESBWdWSk",
            'url'=>"http://weixin.qq.com/download",
            'topcolor'=>"#7B68EE",
            'data'=>array(
                'first'=>array('value'=>urlencode("您好,您已购买成功 "),'color'=>"#743A3A"),
                'keyword1'=>array('value'=>urlencode("商品信息:微时代电影票"),'color'=>'#000'),
                'keyword2'=>array('value'=>urlencode("商品信息:微时代电影票"),'color'=>'#000'),
                'remark'=>array('value'=>urlencode('永久有效!密码为:1231313'),'color'=>'#000'),
            )
        );
        $json_template=json_encode($template);
//echo $json_template;
//echo $this->access_token;
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->access_token;
   //    $res=$this->http_request($url,urldecode($json_template));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
// 我们在POST数据哦！
        curl_setopt($ch, CURLOPT_POST, 1);
// 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, urldecode($json_template));
        $output1 = curl_exec($ch);
       dump($output1);

//print_r($res);

    }



}
