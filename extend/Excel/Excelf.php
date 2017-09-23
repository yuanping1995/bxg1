<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13 0013
 * Time: 上午 10:48
 */
namespace Excel;
/*
 *
 */

class Excelf
{
    /*
     * excel的导出
     */
    public function excel_Export($header="",$date=""){
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=Export_test.xls");
        $tab="\t"; $br="\n";
        $num = count($header);
        $excle = "";
        //栏目设置
        for ($i = 0;$i<$num;$i++){
            if($i<$num){
                $excle.=$header[$i].$tab;
            }else{
                $excle.=$header[$i].$br;
            }
        }
        $dnum = count($date);
        echo $excle.$br;
        //内容设置
        for ($n=0;$n<$dnum;$n++){
            $rnum = count($date[$n]);
            for ($b=0;$b<$rnum;$b++){
                if($b<$rnum){
                    echo  $date[$n][$b].$tab;
                }else{
                    echo  $date[$n][$b];
                }
            }
            echo  $br;
        }
    }

}