switch($type) {
case \Wechat::MSGTYPE_TEXT:
//获得用户发送过来的文字消息内容
$content=$weObj->getRev()->getRevContent();
//从消息的结尾数第二个字开始截取，截取两个字
$str = mb_substr($content,-2,2,"UTF-8");
//从消息的开头开始，截掉末尾的两个字，便得关键字。
$str_key = mb_substr($content,0,-2,"UTF-8");
//然后加以判断是否为关键字，是否为空，符合要求则调用函数查询翻译数据
if($str == '翻译' && !empty($str_key)) {
$url1="http://openapi.baidu.com/public/2.0/bmt/translate?client_id=9peNkh97N6B9GGj9zBke9tGQ&q={$str_key}&from=auto&to=auto";//百度翻译地址
//实例化采集类
$spy=new Snoopy();
//获取采集来的数据
$spy->fetch($url1);
//将结果赋给$con_json
$con_json=$spy->results;
//json解析，转换为object对象类型
$transon=json_decode($con_json);
//读取翻译内容
$con_str = $transon->trans_result[0]->dst;
//以文字的形式输出结果
$weObj->text("{$con_str}")->reply();
}
//判断是否为关键字，是否为空，符合要求则调用函数查询书本数据
if($str=='书本' && !empty($str_key)) {
$url="http://222.206.65.12/opac/search_rss.php?dept=ALL&title={$str_key}&doctype=ALL&lang_code=ALL&match_flag=forward&displaypg=20&showmode=list&orderby=DESC&sort=CATA_DATE&onlylendable=no";
$spp=new Snoopy();
$spp->fetch($url);
$fa=$spp->results;
//将采集获取的XML数据转换成object对象类型
$f=simplexml_load_string($fa);
$da1=$f->channel->item[0]->title;
$da2=$f->channel->item[1]->title;
$da3=$f->channel->item[2]->title;
$weObj->text("{$da1}\n{$da2}\n{$da3}")->reply();
}
//判断公交路线
if($str=='公交' && !empty($str_key)){

$strbus=explode('，',$str_key);
$ch = curl_init();
$url = "http://apis.baidu.com/apistore/bustransport/buslines?city={$strbus[0]}&busNo={$strbus[1]}";
$header = array(
'apikey: 这里的apikey自己去百度APIstore去注册，免费的！'
);
// 添加apikey到header
curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 执行HTTP请求
curl_setopt($ch , CURLOPT_URL , $url);
$res = curl_exec($ch);

$b=json_decode($res);
$a=$b->retData->result;
$c=simplexml_load_string($a);
$d=$c->lines->line[1]->stats;
$rq=explode(';',$d);
$ww=implode('->',$rq);
$weObj->text("{$ww}")->reply();

}
//景点查询

if($str=='景点' && !empty($str_key)){


$ch = curl_init();
$url = "http://apis.baidu.com/apistore/attractions/spot?id={$str_key}&output=json";
$header = array(
'apikey: 同上',
);
// 添加apikey到header
curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 执行HTTP请求
curl_setopt($ch , CURLOPT_URL , $url);
$res = curl_exec($ch);

$a=json_decode($res);
$name=$a->result->name;
$phone=$a->result->telephone;
$abstract=$a->result->abstract;
$description=$a->result->description;
$price=$a->result->ticket_info->price;
$open_time=$a->result->ticket_info->open_time;
$b="景点名：{$name}\n景点联系电话:{$phone}\n价格：{$price}\n开放时间：{$open_time}\n景点详情：{$abstract}{$description}";
$weObj->text("{$b}")->reply();

}
//这里做了一个健康的资讯信息查询，程序员嘛健康还是挺重要的
if($str=='健康' && !empty($str_key)){
$url = 'http://apis.baidu.com/yi18/lore/loreclass?id=1';
$header = array(
'apikey: 。。。自己申请。。。。。',
);
$thisres=$this->curl($url,$header);
$a=$this->curl($url,$header);
$thisres=$a->yi18;
for ($i=0; $i <13 ; $i++) {
$q=$thisres[$i]->id;
$w=$thisres[$i]->name;
$qq.='';
$qq.="序号:{$q}--标题:{$w}\n";
}
$weObj->text("{$qq}\n请选择您想了解的信息的序号，如：1标题")->reply();


}
if($str=='标题' && !empty($str_key)){
$ch = curl_init();
$url = "http://apis.baidu.com/yi18/lore/list?page=1&limit=10&type=id&id={$str_key}";
$header = array(
'apikey: 同上',
);
curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 执行HTTP请求
curl_setopt($ch , CURLOPT_URL , $url);
$res = curl_exec($ch);
$b=json_decode($res);

$thisres2=$b->yi18;
foreach ($thisres2 as $key => $value) {
$q=$thisres2[$key]->id;
$w=$thisres2[$key]->title;
$qq.='';
$qq.="id:{$q}--信息:{$w}\n";
}

$weObj->text("{$qq}\n请选择您想了解的信息的id，如：18069信息")->reply();

}
if($str=='信息' && !empty($str_key)){

$url = "http://apis.baidu.com/yi18/lore/detail?id={$str_key}";
$header = array(
'apikey: ',
);
$a=$this->curl($url,$header);
$thisres=$a->yi18;

$q=$thisres->message;
$w=$thisres->title;
//$qq='标题.$w.正文.$q';


$qq="标题:{$w}\n正文:{$q}";
$str=str_replace("<strong>","",$qq);
    $ww=strip_tags($str);
    $weObj->text("{$ww}")->reply();


    }



    //天气查询
    if($str == '天气' && !empty($str_key)) {
    $url="http://api.map.baidu.com/telematics/v2/weather?location={$str_key}&ak=同上";
    $sp=new Snoopy();
    $sp->fetch($url);
    $l_xml=$sp->results;
    $f=simplexml_load_string($l_xml);
    $city=$f->currentCity;
    $da1=$f->results->result[0]->date;
    $da2=$f->results->result[1]->date;
    $da3=$f->results->result[2]->date;
    $w1=$f->results->result[0]->weather;
    $w2=$f->results->result[1]->weather;
    $w3=$f->results->result[2]->weather;
    $p1=$f->results->result[0]->wind;
    $p2=$f->results->result[1]->wind;
    $p3=$f->results->result[2]->wind;
    $q1=$f->results->result[0]->temperature;
    $q2=$f->results->result[1]->temperature;
    $q3=$f->results->result[2]->temperature;
    $k1=$f->results->result[0]->dayPictureUrl;
    $k2=$f->results->result[1]->dayPictureUrl;
    $k3=$f->results->result[2]->dayPictureUrl;
    $d1=$city.$da1.$w1.$p1.$q1;
    $d2=$city.$da2.$w2.$p2.$q2;
    $d3=$city.$da3.$w3.$p3.$q3;
    $weObj->text("{$d1}\n{$d2}\n{$d3}")->reply();
    }

    //剩下的任务就交给机器人自己去完成吧！;
    //这里我使用的是图灵机器人，通过我女朋友和机器人聊天实验表明还是付费的机器人比较聪明，免费的有点傻傻的，所以土豪们可以选择付费的，按条数付费的

    else {

    $strurl="http://www.tuling123.com/openapi/api?key=自己申请个key吧&info={$content}";
    $xhy=new Snoopy();
    $xhy->fetch($strurl);
    $x_json=$xhy->results;
    $strjson=json_decode($x_json);
    //$a=var_dump($strjson);
    $contentStr = $strjson->text;
    //$weObj->text("{$contentStr}")->reply();
    $weObj->text("{$contentStr}")->reply();
    }
    break;

    case \Wechat::MSGTYPE_LOCATION:
    //接收消息的地理位置
    $arr1=$weObj->getRev()->getRevGeo();
    $snoopy=new Snoopy();
    $url="http://api.map.baidu.com/telematics/v2/distance?waypoints=填你的位置的经纬度;{$arr1['x']},{$arr1['y']}&ak=同上";
    $snoopy->fetch($url);
    $lines_string=$snoopy->results;
    $fk=simplexml_load_string($lines_string);
    $juli=$fk->results->distance;
    $contentstring="你和我的距离有{$juli}米远";
    $weObj->text("{$contentstring}")->reply();
    break;
    //接受图片回复文字，也可以回复图片，你们自由发挥吧
    case \Wechat::MSGTYPE_IMAGE:
    $b=" ";
    $a=rand(1,3);
    switch ($a)
    {case 1;
    $b="你傻逼啊，发这么二的图片";
    break;
    case 2;
    $b="跟你一样丑";
    break;
    default;
    $b="啊，我的天哪";
    }
    $weObj->text("哈哈我知道这是图片\n：{$b}")->reply();
    //exit;
    break;
    //实现首次关注回复功能
    case \Wechat::MSGTYPE_EVENT:
    $msgEvent=$weObj->getRev()->getRevEvent();

    $weObj->text("感谢您关注阮琴专用测试版公众号\n查天气：城市+天气，如广州天气\n翻译：字词+翻译，如好翻译\n测距：发送位置\n查书：书名+书本，如php书本\n听歌：回复音乐\n查公交路线，如杭州，151公交\n健康知识：任意字+健康，如，查健康\n景点查询：景点名（请使用拼音）+景点如xihu景点\n还可选择发送图片，搞笑也会来和大家聊天哦！\n更多内容，敬请期待...")
    ->reply();
    break;

    default:
    $weObj->text("查天气：城市+天气，如广州天气\n翻译：字词+翻译，如好翻译\n测距：发送位置\n查书：书名+书本，如php书本\n听歌：回复音乐\n还可选择发送图片，谢谢你的关注，更多内容，敬请期待...")->reply();

    }