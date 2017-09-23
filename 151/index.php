<?php
//$app_key = "zOflrIgmFfFu6WAd";
//$app_secret = "hEglZMrlKqXNvCNYKDY2S7setCSIAa";
//$request_paras = array(
//    'ParamString' => '{"code":"1234"}',
//    'RecNum' => '18233297067',
//    'SignName' =>'小袁科技',
//    'TemplateCode' => 'SMS_95460068'
//);
//参数说明
//$RecNum 目标手机号
//$ParamString 短信模板中的变量；数字需要转换为字符串；个人用户每个变量长度必须小于15个字符。
//$SignName 管理控制台中配置的短信签名（状态必须是验证通过）
//$TemplateCode 管理控制台中配置的审核通过的短信模板的模板CODE（状态必须是验证通过）
//$AccessKeyId 阿里云颁发给用户的访问服务所用的密钥ID
//$AccessKeySecret 阿里云颁发给用户的访问服务所用的验证串
//返回值
//发送成功返回true,结果仅供参数，不保证完全正确
var_dump(SendSMS(18233297067,'147',"小袁科技","SMS_954600068","zOflrIgmFfFu6WAd","hEglZMrlKqXNvCNYKDY2S7setCSIAa"));
function SendSMS($RecNum,$ParamString,$SignName,$TemplateCode,$AccessKeyId,$AccessKeySecret)
{
    $url='https://sms.aliyuncs.com/';//短信网关地址
    $Params['Action']='SingleSendSms';//操作接口名，系统规定参数，取值：SingleSendSms
    //$Params['RegionId']='cn-hangzhou';//机房信息
    $Params['AccessKeyId']=$AccessKeyId;//阿里云颁发给用户的访问服务所用的密钥ID
    //$Params['Format']='JSON';//返回值的类型，支持JSON与XML。默认为XML
    $Params['ParamString']=rawurlencode($ParamString);//短信模板中的变量；数字需要转换为字符串；个人用户每个变量长度必须小于15个字符。
    $Params['RecNum']=$RecNum;//目标手机号
    $Params['SignatureMethod']='HMAC-SHA1';//签名方式，目前支持HMAC-SHA1
    $Params['SignatureNonce']=time();//唯一随机数
    $Params['SignatureVersion']='1.0';//签名算法版本，目前版本是1.0
    $Params['SignName']=rawurlencode($SignName);//管理控制台中配置的短信签名（状态必须是验证通过）
    $Params['TemplateCode']=$TemplateCode;//管理控制台中配置的审核通过的短信模板的模板CODE（状态必须是验证通过）
    $Params['Timestamp']=rawurlencode(gmdate("Y-m-d\TH:i:s\Z"));//请求的时间戳。日期格式按照ISO8601标准表示，
    //并需要使用UTC时间。格式为YYYY-MM-DDThh:mm:ssZ
    $Params['Version']='2016-09-27';//API版本号，当前版本2016-09-27
    ksort($Params);
    $PostData='';
    foreach ($Params as $k => $v) $PostData.=$k.'='.$v.'&';
    $PostData.='&Signature='.rawurlencode(base64_encode(hash_hmac('sha1','POST&%2F&'.rawurlencode(substr($PostData,0,-1)),$AccessKeySecret.'&',true)));
    $httphead['http']['method']="POST";
    $httphead['http']['header']="Content-type:application/x-www-form-urlencoded\n";
    $httphead['http']['header'].="Content-length:".strlen($PostData)."\n";
    $httphead['http']['content']=$PostData;
    $httphead=stream_context_create($httphead);
    $result=@simplexml_load_string(file_get_contents($url,false,$httphead));
    return !isset($result->Code);
}

