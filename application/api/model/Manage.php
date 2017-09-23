<?php
namespace app\index\controller;
use think\Db;

class Manage extends \think\Controller
{


    public function index() {
                $seller_id = input('seller_id'); //用户id
          $indexId = input('id',0); //用户id      
      $this->assign('seller_id',$seller_id);
      $this->assign('indexId',$indexId);
      $this->assign('shopcount',Db::name('order')->where('status','NEQ',3)->where('seller_id',$seller_id)->count());
         return $this->fetch();
   }
     //订单管理ajax
    public function ajaxorder()
    {
      $seller_id = input('seller_id'); //用户id
        $page=input('page',1);
        $indexId=input('indexId',0);//条目状态
         switch ($indexId) {
             case 0://全部
                $data=Db::name('order')->where('seller_id',$seller_id)->where('status','NEQ',3)->field('oid,surname,mobile,otime,price,status,pricetotal')->page($page,10)->select();
             break;
             case 1://待发货
                $data=Db::name('order')->where('seller_id',$seller_id)->where('status',1)->field('oid,surname,mobile,otime,price,status,pricetotal')->page($page,10)->select();
             break;
              case 2://待收货
                $data=Db::name('order')->where('seller_id',$seller_id)->where('status',2)->field('oid,surname,mobile,otime,price,status,pricetotal')->page($page,10)->select();
             break;
              case 3://交易成功
                $data=Db::name('order')->where('seller_id',$seller_id)->where('status',9)->field('oid,surname,mobile,otime,price,status,pricetotal')->page($page,10)->select();
             break;
              case 4://已退货
                $data=Db::name('order')->where('seller_id',$seller_id)->where('status',7)->field('oid,surname,mobile,otime,price,status,pricetotal')->page($page,10)->select();
             break;        
         }
     if($data){  
          foreach ($data as $key => $value){ 
            $filter_spec[$value['oid']][] = array(
                                         'oid' => $value['oid'],
                                         'surname'=> $value['surname'],
                                          'mobile' => $value['mobile'],
                                          'otime' => $value['otime'],
                                          'price' => $value['price'],
                                           'status' => $value['status'],
                                            'pricetotal' => $value['pricetotal'],
                               );
        }
        $this->assign('shoplist', $filter_spec);
        $this->assign('seller_id',$seller_id);
         return $this->fetch();
      }
       
    }
    //
    public function orderdetail()
    {
        $orderId = input('orderId'); //订单号
        $seller_id = input('seller_id'); //用户id
        $data=Db::name('order')->where('oid',$orderId)->field('oid,goods_id,area,status,user_note,surname,spec_key_name,mobile,pic,sname,price,num,pricetotal,yunprice,order_prom_amount,otime')->select();
         foreach ($data as $key => $value){ 
            $filter_spec[$value['oid']][] = array(
                                         'oid' => $value['oid'],
                                          'goods_id'=> $value['goods_id'],
                                           'area'=> $value['area'],
                                            'user_note'=> $value['user_note'],
                                             'spec_key_name'=> $value['spec_key_name'],
                                              'mobile'=> $value['mobile'],
                                               'pic'=> $value['pic'],
                                                'price'=> $value['price'],
                                                'sname'=> $value['sname'],
                                                 'num'=> $value['num'],
                                                  'pricetotal'=> $value['pricetotal'],
                                                   'yunprice'=> $value['yunprice'],
                                                    'order_prom_amount'=> $value['order_prom_amount'],
                                                     'surname'=> $value['surname'],
                                                    'otime' => $value['otime'],
                                                    'status' => $value['status']
                               );
        }

         $this->assign('data',$filter_spec);
          $this->assign('seller_id',$seller_id);
         return $this->fetch();
        
}
 
}

