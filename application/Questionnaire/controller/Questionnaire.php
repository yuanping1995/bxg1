<?php
namespace app\index\controller;
use think\Db;
class Questionnaire extends \think\Controller
{
    //构造一个请求函数
   public function index(){
      return $this->fetch();
   }//调查接受
   public function addque(){
      $age = Db::name('questionnaire')->select();
      $name = array('age','Gender','shopping','repeat','colour','follow','time','Price','infor','mode');
      for ($i=0;$i<count($age);$i++){
            $age[$i][$_POST[$name[$i]]]++;
          Db::name('questionnaire')->where('id', $age[$i]['id'])->update([$_POST[$name[$i]] =>  $age[$i][$_POST[$name[$i]]]]);
      }

      dump($age);

   }
   public function addque2(){
      // dump($_POST['mode']);
       $age = Db::name('questionnaire')->where('name',$_POST['name'])->find();
         $age[$_POST[$_POST['name']]]++;
       Db::name('questionnaire')->where('name',$_POST['name'])->update([$_POST[$_POST['name']] =>  $age[$_POST[$_POST['name']]]]);

   }


}
