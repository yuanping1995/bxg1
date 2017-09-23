<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13 0013
 * Time: 下午 3:31
 */

namespace Verify;


class Verify
{
    public function phone_Number($phone = ""){
        if(preg_match("/^1[34578]{1}\d{9}$/",$phone)){;//定义正则表达式并判断
            return 1111;
        }else{
            return 0000;
        }
    }
    public function email($email = ""){
        $checkmail="/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";//定义正则表达式
        if(isset($email) && $email!=""){            //判断文本框中是否有值
            $mail=$email;                                   //将传过来的值赋给变量$mail
            if(preg_match($checkmail,$mail)){                       //用正则表达式函数进行判断
                return 1111;
            }else{
                return 0000;
            }
        }
    }
    public function password($passordone = "",$passwordtow = ""){
        if($passordone===$passwordtow){
        $rest_1 = preg_match("/^(?![0-9]+$)(?![a-zA-Z]+$)/",$passordone);
        $rest_2 = preg_match ("/^(?![0-9]+$)(?![a-zA-Z]+$)/", $passwordtow);
        if($rest_1&&$rest_2){
            return 1111;
        }else{
            return 0000;
        }
    }else{
            return 0001;
        }
    }


}