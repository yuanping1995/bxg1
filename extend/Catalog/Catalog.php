<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/14 0014
 * Time: 上午 11:09
 */

namespace Catalog;

class Catalog
{
    public function forcemkdir($path) {

        if (!is_dir($path.'/')){
            mkdir($path.'/'); // 如果不存在则创建
            return 1111;
        }else{
            return 0000;
        }
    }
    //
    public 	function cleardir($dir, $forceclear = false) {
        if (!is_dir($dir)) {
            return;
        }
        $directory = dir($dir);
        while ($entry = $directory -> read()) {
            $filename = $dir . '/' . $entry;
            if (is_file($filename)) {
                @unlink($filename);
            } elseif (is_dir($filename) & $forceclear & $entry != '.' & $entry != '..') {
                chmod($filename, 0777);
                file::cleardir($filename, $forceclear);
              if( rmdir($filename)){
                  return 1111;
              }else{
                  return 0000;
              };
            }
        }
        $directory -> close();
    }
    public function edie_Name($old_Name = "",$new_Name = "",$type = ""){
            if ((is_dir($old_Name) && !is_dir($new_Name))||(file_exists($old_Name)&& !file_exists($new_Name))) {
                $arr = rename($old_Name, $new_Name);
                return 1111;
            }
            if (!is_dir($old_Name)) {
                return 1000;
            } else {
                return '0000';
            }
        }
    public function read_Directory($pathname = ""){
        if (is_dir($pathname)){
            $handler = opendir($pathname);
            $i = 0;
            $Directory= array();
            while( ($filename = readdir($handler)) !== false ) {
                if($filename != "." && $filename != ".."){
                    Array_push($Directory,$filename) ;
                }
            }
            return $Directory;
            closedir($handler);
        }else{
            return '0000';
        }
    }

}