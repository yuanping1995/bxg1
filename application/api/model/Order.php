<?php
namespace app\index\controller;
use think\Db;
/**
 * 商品列表展示
 *
 * 分页展示未完成
 */
class Order extends \think\Controller
{

    public function index() {
//查看商品详细信息
      $seller_id=input('seller_id');//货物id  
      $goods=Db::name('order')->where('uid',$seller_id)->where(['status'=>1,'remind' => 0])->find();
        if($goods){
        	Db::name('order')->where('oid',$goods['oid'])->update(['remind' => 1]);
          return json(['flag' =>1]);
        }else{
          return json(['flag' =>0]);
      } 
    }
     public function orderpay() {
//查看商品详细信息
      $oid=input('oid');//货物id  
      $goods=Db::name('order')->where('oid',$oid)->where('status',1)->find();
        if($goods){
          Db::name('spec_goods_price')->where('goods_id',$goods['goods_id'])->where('keyid',$goods['spec_key'])->setDec('sku', $goods['num']);
          Db::name('order')->where('oid',$oid)->update(['status' => 2]);
          return json(['flag' =>1,'msg'=>'发货成功']);
        }else{
          return json(['flag' =>0,'msg'=>'发货失败']);
      } 
    }
}


   


