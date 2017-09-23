<?php

header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:attachment;filename=Export_test.xls");
$tab="\t"; $br="\n";
$eader_1 = iconv("utf-8","gb2312","字类型");
$eader_2 = iconv("utf-8","gb2312","字类2型");

$head=$eader_1.$tab.$eader_2.$br;
//$head = iconv(‘GBK’,'UTF-8',$head);
//输出内容如下：
echo $head.$br;
echo  "test321318312".$tab;
echo  "string1";
echo  $br;

echo  "330181199006061234".$tab;  //直接输出会被Excel识别为数字类型
echo  "string1";
echo  $br;

echo  "330181199006061234".$tab;  //原样输出需要处理
echo  "string1";
echo  $br;
