<?php
namespace app\oder\model;
use think\Model;
use think\Db;
class oder extends Model
{
    /** 执行错误消息及代码 */
    public $errMsg;
    public $errCode;

    public function edit_Oder($id="",$pir=""){
        $oder_infor = Db::name('oder')->where('id', 1)->find();
        var_dump($oder_infor['oder_infor']);
        $oder_infor_arr = json_decode($oder_infor['oder_infor'], true);
       dump($oder_infor_arr);
       /**
        * {"id":{"a":1,"b":2,"c":3,"d":4,"e":5},{"a":1,"b":2,"c":3,"d":4,"e":5}｝
        *
        */
     $oder_infor_arr[$id]['pir'] = $pir;
         $oder_infor_arr=json_encode($oder_infor_arr);
         $oder_infor_arr_is = DB::name('oder')->where('id',1)->update(['oder_infor'=>$oder_infor_arr]);
         if(!empty($oder_infor_arr_is)){
                echo "Success";
         }

    }
    /*
     *$goods_id
     *
     */
    public function  add_Oder($goods_id="",$mun = "",$colour = ""){

    }

}
