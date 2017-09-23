<?php
namespace app\index\controller;
use think\Db;
use app\index\model\User as UserModel;
class User extends \think\Controller
{
//生成二维码
public function qr(){
	$UserModel = new UserModel;
    $user_key= $UserModel->isLogin();
	return qrCode(url('index/user/user_vip',["user"=>$user_key],'',TRUE),10);
}
public function myqr(){
			$UserModel = new UserModel;
			$user_key= $UserModel->isLogin();
			if(!empty(input('usertui')))
			{
				$user_key = input('usertui');
			}else if(!empty($user_key))
			{
				
				echo '<script language="javascript" type="text/javascript">window.location.href="http://m.bxgogo.com//index/user/myqr/usertui/'.$user_key.'";</script>';
				die;
			}else{
				echo '<script language="javascript" type="text/javascript">window.location.href="http://login.bxgogo.com";</script>';
				die;
			}
			$this->assign('uid',$user_key);
	return $this->fetch();
}

public function tuijian(){
			$UserModel = new UserModel;
			$user_key= $UserModel->isLogin();
			if(!empty(input('usertui')))
			{
				$user_key = input('usertui');
			}else if(!empty($user_key))
			{
				
				echo '<script language="javascript" type="text/javascript">window.location.href="http://m.bxgogo.com//index/user/tuijian/usertui/'.$user_key.'";</script>';
				die;
			}else{
				echo '<script language="javascript" type="text/javascript">window.location.href="http://login.bxgogo.com";</script>';
			}
		qrCode(url('m.bxgogo/com/index/user/user_vip',["user"=>$user_key],'',TRUE),6,"http://login.bxgogo.com/static/img/log_logo.png",'./uploads/user_qr/'.$user_key.'.png');
		$bigImgPath = './uploads/user_qr/backgroud.png';
		$qCodePath = './uploads/user_qr/'.$user_key.'.png';
 
$bigImg = imagecreatefromstring(file_get_contents($bigImgPath));
$qCodeImg = imagecreatefromstring(file_get_contents($qCodePath));
 
list($qCodeWidth, $qCodeHight, $qCodeType) = getimagesize($qCodePath);
// imagecopymerge使用注解
imagecopymerge($bigImg, $qCodeImg, 190, 570, 0, 0, $qCodeWidth, $qCodeHight, 100);
 
list($bigWidth, $bigHight, $bigType) = getimagesize($bigImgPath);
 
 
switch ($bigType) {
    case 1: //gif
        header('Content-Type:image/gif');
        imagegif($bigImg);
        break;
    case 2: //jpg
        header('Content-Type:image/jpg');
        imagejpeg($bigImg);
        break;
    case 3: //jpg
        header('Content-Type:image/png');
        imagepng($bigImg);
        break;
    default:
        # code...
        break;
}
 
imagedestroy($bigImg);
die;
}
    public function index() {
      cookie('url_back',__APPURL__,600);
  // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
	   if($user_key ==0){ 
	        header("location:http://login.bxgogo.com");
	       exit;
	   }
     $user=Db::name('user')->where('uid',$user_key)->field('uid,uname,icon,mobile,sex,mail,qid')->find();
     $enuser=encryt_data(json_encode($user),config('PrivateKeyFilePath'));
     cookie('user_data',$enuser,3600);
       $this->assign('user',$user);//用户s
       $this->assign('order1',Db::name('order')->where(['uid'=>$user_key,'status'=>3])->count());//待付款
       $this->assign('order2',Db::name('order')->where(['uid'=>$user_key,'status'=>1])->count());//待发货
       $this->assign('order3',Db::name('order')->where(['uid'=>$user_key,'status'=>2])->count());//待收货
       $this->assign('order4',Db::name('order')->where(['uid'=>$user_key,'status'=>8])->count());//待评论
       $this->assign('order5',Db::name('order')->where(['uid'=>$user_key,'status'=>6])->count());//退货中
      $this->assign('cur5',5);
      return $this->fetch();
    }
    public function mySet() {
       // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
   $this->assign('aracount',Db::name('delivery')->where(['uid'=>$user_key])->count());//
       $this->assign('user',Db::name('user')->where(['uid'=>$user_key])->find());//用户
      return $this->fetch();
    }


 	public function aboutMe() {
   cookie('url_back',__APPURL__,600);
       // 判断是否登录
        $UserModel = new UserModel;
         $user_key= $UserModel->isLogin();
     if($user_key ==0){ 
          header("location:http://login.bxgogo.com");
         exit;
     }
     if(request()->isAjax()){
       $param=input('info');
       $paramarr=explode("&",$param);
       foreach($paramarr as $key=>$value){
        $valuearr=explode("=",$value);
        if($valuearr[1]=='男'){
          $valuearr[1]=1;
        }elseif($valuearr[1]=='女'){
          $valuearr[1]=0;
        }
        $paramarr[$key]=$valuearr[1];
       }
       $result=Db::name('user')->where('uid',$user_key)->update(['uname'=>$paramarr[0],'sex'=>$paramarr[1],'mail'=>$paramarr[2]]);
       if($result){
        return json(['flag'=>1,'msg'=>'修改成功！']);
       }else{
        return json(['flag'=>0,'msg'=>'修改失败，请重试！']);
       }
     }
         $this->assign('user',Db::name('user')->where(['uid'=>$user_key])->find());//用户
        return $this->fetch();
      }
      //上传头像
public function upload_tou(){
	$base64_img = trim(input('image'));
					$up_dir ='./uploads/touxiang/'; //目录路径

					if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)) {
						$type = $result[2];
							$new_file = $up_dir  . date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8) .'.'. $type;//文件的名字
							if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))) {
								$img_path = str_replace('../../..', '', $new_file);
								$src = ltrim($img_path, '.');
								$msg['img'] = $src;
								$msg['result'] = 'ok';
							} else {
								$msg['result'] = 'no';
								$msg['str'] = '图片上传失败';
							}
					} else {
						$msg['result'] = 'no';
						$msg['str'] = '文件错误';
					}
					$UserModel = new UserModel;
         			$user_key= $UserModel->isLogin();
         			$data['icon']=$src;
					Db::name('user')->where("uid=".$user_key)->update($data);
					return json($msg);

}

//上传身份证
public function upload_sf(){
  $base64_img = trim(input('image'));
  $id=input('id');
          $up_dir ='./uploads/shenfen/'; //目录路径

          if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)) {
            $type = $result[2];
              $new_file = $up_dir  . date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8) .'.'. $type;//文件的名字
              if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))) {
                $img_path = str_replace('../../..', '', $new_file);
                $src = ltrim($img_path, '.');
                $msg['img'] = $src;
                $msg['result'] = 'ok';
              } else {
                $msg['result'] = 'no';
                $msg['str'] = '图片上传失败';
              }
          } else {
            $msg['result'] = 'no';
            $msg['str'] = '文件错误';
          }
              $UserModel = new UserModel;
              $user_key= $UserModel->isLogin();
              if($id==0){
                $data['id_cardf']=$src;
              }elseif($id==1){
                $data['id_cardz']=$src;
              }
          Db::name('user')->where("uid=".$user_key)->update($data);
          return json($msg);

}

    // 我的订单
    public function myOrder() {
       cookie('url_back',__APPURL__,600);
      // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
    	$typeid=input('typeid');
      
    	$this->assign('typeid',$typeid);
      return $this->fetch();
    }
    public function ajax_order() {
      cookie('url_back',__APPURL__,600);
           // 判断是否登录
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
      $page=input('page');
      $index=input('index');
      switch ($index){
      case 1://待付款
          $listRs =Db::name('order')->alias('a')->join('seller b','a.seller_id=b.seller_id')->where(['a.uid'=>$user_key,'a.status'=>3])->field('a.payprice,a.oid,a.goods_id,a.status,a.otime,a.sname as pname,a.price,a.pic,a.spec_key_name,a.num,a.pricetotal,a.yunprice,b.seller_id,b.logo,b.sname')->page($page,10)->order('a.otime DESC')->select();
      break;
      case 2://待发货
          $listRs =Db::name('order')->alias('a')->join('seller b','a.seller_id=b.seller_id')->where(['a.uid'=>$user_key,'a.status'=>1])->field('a.payprice,a.oid,a.goods_id,a.status,a.otime,a.sname as pname,a.price,a.pic,a.spec_key_name,a.num,a.pricetotal,a.yunprice,b.seller_id,b.logo,b.sname')->page($page,10)->order('a.otime DESC')->select();
      break;
      case 3://待收货
          $listRs =Db::name('order')->alias('a')->join('seller b','a.seller_id=b.seller_id')->where(['a.uid'=>$user_key,'a.status'=>2])->field('a.payprice,a.oid,a.goods_id,a.status,a.otime,a.sname as pname,a.price,a.pic,a.spec_key_name,a.num,a.pricetotal,a.yunprice,b.seller_id,b.logo,b.sname')->page($page,10)->order('a.otime DESC')->select();
      break;
      case 4://待评价
          $listRs =Db::name('order')->alias('a')->join('seller b','a.seller_id=b.seller_id')->where(['a.uid'=>$user_key,'a.status'=>8])->field('a.payprice,a.oid,a.goods_id,a.status,a.otime,a.sname as pname,a.price,a.pic,a.spec_key_name,a.num,a.pricetotal,a.yunprice,b.seller_id,b.logo,b.sname')->page($page,10)->order('a.otime DESC')->select();
      break;
      case 5://退货中
          $listRs =Db::name('order')->alias('a')->join('seller b','a.seller_id=b.seller_id')->where(['a.uid'=>$user_key,'a.status'=>6])->field('a.payprice,a.oid,a.goods_id,a.status,a.otime,a.sname as pname,a.price,a.pic,a.spec_key_name,a.num,a.pricetotal,a.yunprice,b.seller_id,b.logo,b.sname')->page($page,10)->order('a.otime DESC')->select();
      break;
      default:
          $listRs =Db::name('order')->alias('a')->join('seller b','a.seller_id=b.seller_id')->where('a.uid',$user_key)->field('a.payprice,a.oid,a.goods_id,a.status,a.otime,a.sname as pname,a.price,a.pic,a.spec_key_name,a.num,a.pricetotal,a.yunprice,b.seller_id,b.logo,b.sname')->page($page,10)->order('a.otime DESC')->select();
    }
    if($listRs){  
        foreach ($listRs as $key => $value){ 
              $shoplist[$value['oid']][] = array(
                 'oid'=>$value['oid'],//
                 'seller_id'=>$value['seller_id'],//
                 'goods_id'=>$value['goods_id'],//
                 'status'=> $value['status'],// 
                 'otime'=> $value['otime'],// 
                 'pname' => $value['pname'],//   
                 'price' => $value['price'],//
                 'pic'=>$value['pic'],//
                 'spec_key_name' => $value['spec_key_name'],// 
                 'num' => $value['num'],//   
                 'pricetotal' => $value['pricetotal'],//   
                 'seller_name' =>$value['sname'] ,//
                 'logo' =>$value['logo'] ,//
                 'yunprice'=> $value['yunprice'],
				 'payprice'=> $value['payprice']
                );
        }
        // dump($shoplist);
        // die;
        $this->assign('shoplist',$shoplist);
      return $this->fetch();
      }
       
    }
    // 确认收货
 public function confirmReceipt(){
      $oid=input("orderoid");
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
      $Confirm =Db::name('order')->where(['uid'=>$user_key,'oid'=>$oid])->select();
      $user=Db::name('user')->where(['uid'=>$user_key])->find();
           if(0){
              foreach($Confirm as $value){
                  if(!empty($user["recommend"])){
                    $qid=Db::name('user')->where('uid',$user['recommend'])->value('qid');
                    if($qid==7){
                        $goods_ti=$value['price']*0.01;
                        $t_updata=Db::name('bankrollnd')->where(['uid'=>$user['recommend']])->setInc('total',$goods_ti);
                        $txtotal=DB::name('bankrollnd')->where('uid',$user['recommend'])->value('total');
                        Db::name('capital_detailed')->insert(['uid'=>$user['recommend'],'time'=>time(),'type'=>'推荐'.$user_key.'消费返现','money'=>$goods_ti,'typeid'=>1,'total'=>$txtotal]);
                    }
                  }
                  //$data = time();
                  $user['total']=$value['draw'];
                  $user['profit']=$value['total'];
                  $user['day_profit']=$value['total']*0.0005;

                                  $userquery=Db::query("UPDATE b_bankrollnd SET money = (money+$user[total]) where uid = $user_key");
                                  //$userquery=Db::query("UPDATE b_bankrollnd SET money = (money+$user[total]+$user[profit]) where uid = $user_key");
                                  //$userquery=Db::query("UPDATE b_bankrollnd SET fundtime = $data where uid = $user_key");
                                  $userquery1=Db::query("UPDATE b_bankrollnd SET profit = (profit+$user[profit]) where uid = $user_key");
                                  $userquery2=Db::query("UPDATE b_bankrollnd SET day_profit = (day_profit+$user[day_profit]) where uid = $user_key");
                                  $user['total']=$value['sellertotal'];
                                  $user['profit']=$value['shoptotal'];
                                  $user['day_profit']=$value['shoptotal']*0.0005;

                                  $userquery=Db::query("UPDATE b_bankrollnd SET total = (total+$user[total]) where uid = $value[seller_id]");
                                  $userquery1=Db::query("UPDATE b_bankrollnd SET profit = (profit+$user[profit]) where uid = $value[seller_id]");
                                  $userquery2=Db::query("UPDATE b_bankrollnd SET day_profit = (day_profit+$user[day_profit]) where uid = $value[seller_id]");
                                  // $userquery=Db::query("UPDATE b_bankrollnd SET money =0 where uid = $value[seller_id]");
                                  //$userquery=Db::query("UPDATE b_bankrollnd SET fundtime = $data where uid = $value[seller_id]"); 	  
              }
         }
      $Confirm =Db::name('order')->where(['oid'=>$oid])->update(["status" =>8]);
      if($Confirm){
      	
        return json(['flag'=>1,'msg'=>'确认收货成功']);
      }else{
        return json(['flag'=>0,'msg'=>'确认收货失败']);
      }
    }
	
	
 //退货
    public function returns(){
    	cookie('url_back',__APPURL__,600);
	    // 判断是否登录
	    $UserModel = new UserModel;
	    $user_key= $UserModel->isLogin();
	    if($user_key ==0){ 
	        header("location:http://login.bxgogo.com");
	        exit;
	    }
        	//$return=Db::name('order')->alias('a')->join('seller b','a.seller_id=b.seller_id')->where(['a.uid'=>$user_key])->where("status in(6,7)")->field('a.oid,a.goods_id,a.status,a.otime,a.sname as pname,a.pic,a.spec_key_name,a.num,a.price,a.yunprice,b.seller_id,b.logo,b.sname')->order('a.otime DESC')->select();
			$return=Db::name('order')->alias('a')->join('seller b','a.seller_id=b.seller_id')->where(['a.uid'=>$user_key])->where("status",6)->field('a.oid,a.goods_id,a.status,a.otime,a.sname as pname,a.pic,a.spec_key_name,a.num,a.price,a.yunprice,b.seller_id,b.logo,b.sname')->order('a.otime DESC')->select();
            if($return){
            	foreach ($return as $key => $value){ 
		            $returnlist[$value['oid']][] = array(
		                'logo'=>$value['logo'],//
		                'seller_name'=>$value['sname'],//
		                'goods_pic'=> $value['pic'],// 
		                'goods_name'=> $value['pname'],// 
		                //'spec_key_name' => $value['spec_key_name'],//   
		                'goods_num' => $value['num'],//
		                'status'=>$value['status'],//
		                'price'=>$value['price']*$value['num']+$value['yunprice'],
		            );
		        }
				$this->assign('returnlist',$returnlist);
            }else{
            	$this->assign('returnlist',array());
            }
        	return $this->fetch();
    }
    
    //申请退货
    public function returnGoods(){
    	 cookie('url_back',__APPURL__,600);
       // 判断是否登录
       $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
        if(request()->isAjax()){
        	$oid=input("oid");
	      $data=[
	         'status'=>6
	        ];
	      $return=Db::name('order')->where(['oid'=>$oid])->update($data);
	      if($return){
	          return json(['flag'=>1,'msg'=>'申请成功！']);
	        }else{
	          return json(['flag'=>0,'msg'=>'申请失败！']);
	        }
        }
	      
    }
	
	
	
	
       // 订单发表评价
    public function evaluate_order(){

      cookie('url_back',__APPURL__,600);
       // 判断是否登录
       $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
       
       if(request()->isAjax()){
          $title = input('title');
          $title=explode(',',$title);
          $evaluate=input('evaluate');
          $evaluate=explode(',',$evaluate);
          $anonymous=input('anonymous');
          $anonymous=explode(',',$anonymous);
          $describe=input('describe');
          $logistics=input('logistics');
          $serve=input('serve');
          $file= json_decode(input('goodsimg'));
          $new= new \Upload\Upload();
          foreach ($file as $key => $value) {
            foreach ($value as  $val) {
               $a[]=$new->images('1',"./images/goodsimg/$key/",$val);
            }
          }
          $img='';
          if(!empty($a))
          {
            foreach ($a as  $value) {
               $dataimg[]=$value['img'];
            }
           $img=implode(',',$dataimg);
         }
          //获取用户信息
          $power=Db::name("user")->where('uid',$user_key)->find();  
          // 获取下单时间
          $oid=input('oid');
          $otime=Db::name('order')->where('oid',$oid)->select();
          foreach ($title as $key => $value) {
            $data[$key]=[
              'goods_id'=>$otime[$key]['goods_id'], //商品Id
              'seller_id'=>$otime[$key]['seller_id'],//商家id
              'uid'=>$user_key,            //用户id
              'power'=>$power['qid'],     //用户权限
              'ico'=>$power['icon'],      //用户头像
              'x_time'=>$otime[$key]['otime'],  //用户下单时间
              'otime'=>time(),          //评论时间
              'title'=>$value,         //评论内容
              'pic'=>$otime[$key]['pic'],   //商品图片
              'star'=>$evaluate[$key]+1, // 好评 中评 差评
              'describe_star'=>$describe,//描述星级
              'logistics_star'=>$logistics,//物流星级
              'serve_star'=>$serve,//服务星级,
              'img'=>$img,
              'isanonymous'=>$anonymous[$key] //是否匿名
            ];
          }
          $result=Db::name("comment")->insertAll($data);
          if($result){
              $describe=Db::name('comment')->where('seller_id',$otime[0]['seller_id'])->avg('describe_star');
              $logistics=Db::name('comment')->where('seller_id',$otime[0]['seller_id'])->avg('logistics_star');
              $serve=Db::name('comment')->where('seller_id',$otime[0]['seller_id'])->avg('serve_star');
              Db::name('seller')->where('seller_id',$otime[0]['seller_id'])->update(['describe'=>$describe,'logistics'=>$logistics,'serve'=>$serve]);
              DB::name('order')->where('oid',$oid)->setField('status',9);
              return json(['flag'=>1,'msg'=>'感谢您的评价！']);
           }else{
              return json(['flag'=>0,'msg'=>"评价提交失败，请重新提交！"]);
           }
       }else{
            $otime=Db::name('order')->where('oid',input('oid'))->select();
            $oid=input('oid');
            $this->assign('picsrc',$otime);
            $this->assign('oid',$oid);
            return $this->fetch();
      } 







      



    }
    // end发表评价

    public function myorderdetails() {
       cookie('url_back',__APPURL__,600);
         // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
   $oid=input('oid');
      $listRs =Db::name('order')->alias('a')->join('seller b','a.seller_id=b.seller_id')->where(['a.uid'=>$user_key,'a.oid'=>$oid])->field('a.*,b.seller_id,b.logo,b.sname as pname')->select();
       if($listRs){  
        foreach ($listRs as $key => $value){ 
              $shoplist[$value['seller_id']][] = array(
                 'oid'=>$value['oid'],//
                 'goods_id'=>$value['goods_id'],//
                 'surname'=>$value['surname'],//
                 'mobile'=>$value['mobile'],//
                 'area'=>$value['area'],//
                 'status'=> $value['status'],// 
                 'otime'=> $value['otime'],// 
                 'pname' => $value['sname'],//   
                 'price' => $value['price'],//
                 'pic'=>$value['pic'],//
                 'spec_key_name' => $value['spec_key_name'],// 
                 'num' => $value['num'],//   
                 'pricetotal' => $value['pricetotal'],//   
                'total' =>$value['total'] ,//
                'draw' =>$value['draw'] ,//
                 'yunprice' =>$value['yunprice'] ,//
                'trade_status' =>$value['trade_status'] ,//
                'trade_no' =>$value['trade_no'] ,//
                'payfangshi' =>$value['paytey'] ,//
                'wtime' =>$value['wtime'] ,//
               'user_note' =>$value['user_note'] ,//
               'seller_name' =>$value['pname'] ,//
               'logo' =>$value['logo'] ,//
			   'payprice' =>$value['payprice'] ,//
                );
        }
        $this->assign('shoplist',$shoplist);
      return $this->fetch();
      }
    }

    public function identifyq() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function identify_info() {
      cookie('url_back',__APPURL__,600);
      // 判断是否登录
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
      if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
      }
      if(request()->isAjax()){
        $name=input('name');
        $id_card=input('id_card');
        $result=DB::name('user')->where('uid',$user_key)->update(['realname'=>$name,'id_card'=>$id_card]);
        if($result){
           return json(['flag'=>1,'msg'=>'认证完成！']);
        }else{
           return json(['flag'=>0,'msg'=>'认证未通过，请重试！']);
        }
      }else{
        $user=Db::name('user')->where('uid',$user_key)->field('realname,id_card,id_cardz,id_cardf')->find();
        $this->assign('user',$user);
        return $this->fetch();
      } 
    }
    public function identify_s() {
      cookie('url_back',__APPURL__,600);
      // 判断是否登录
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
      if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
      }
      $real=Db::name('user')->where('uid',$user_key)->field('realname,id_card')->find();
      $this->assign('real',$real);
      return $this->fetch();
    }

    public function shippingAddress() {
       cookie('url_back',__APPURL__,600);
            // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
    }
      $this->assign('delivery',Db::name('delivery')->where(['uid'=>$user_key])->select());//
      return $this->fetch();
    }
    // 删除地址
     public function deleteAddress(){
       cookie('url_back',__APPURL__,600);
          $id=input('addressid');  
           $delete =Db::name('delivery')->where(['id'=>$id])->delete();
           if($delete!=0){
                echo 1;
           }else{
                 echo 0;
           }
    }
    // 编辑地址
    public function updateAddress(){
       $id=input("addressid");
       $data=[
          "surname"=>input('add_sname'),
          "province"=>input('add_province'),
          "city"=>input('add_city'),
          "county"=>input('add_area'),
          "detailed"=>input('add_detail'),
          "mobile"=>input('add_stel')
       ];
       $update=Db::name('delivery');
       $updateAddress =$update->where("id",$id)->update($data);
      if($updateAddress){
         return json(['flag' =>1,'msg' => '保存成功']);
      }else{
        return json(['flag' =>0,'msg' => '资料未做修改！']);
      }

    }
    public function newaddress(){
         cookie('url_back',__APPURL__,600);
         $province=input('add_province');
         $city=input('add_city');
         $area=input('add_area');
         $surname=input('add_sname');
         $detailed=input('add_detail');
         $mobile=input('add_stel');
         $type=input('$type');
               // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
	   if($user_key ==0){ 
	        header("location:http://login.bxgogo.com");
	       exit;
	   }
      if(request()->isAjax()) {
        if(!empty($province) && !empty($city) && !empty($area)){//
             $sdate=Db::name('delivery')->insertGetId(['uid'=>$user_key,'surname'=>$surname,'province' =>$province,'city'=>$city,'county'=>$area,'detailed'=>$detailed,'mobile'=>$mobile]);
          return json(['flag' =>1,'msg' => '保存成功']);
        }else{
          return json(['flag' =>0,'msg' =>'信息不能为空']);
        }
      }else{
         return $this->fetch();
       }
    }

    public function selcity(){
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function securityCenter() {
       cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function setMobile() {
       cookie('url_back',__APPURL__,600);
              // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
       $this->assign('user',Db::name('user')->where(['uid'=>$user_key])->field('uid,mobile')->find());//用户
      return $this->fetch();
    }
    public function changeMobile() {
      cookie('url_back',__APPURL__,600);
               // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
      if (request()->isAjax()) {
        $mobile=input('mesMobile');
        $code=input('mesCode');
        if(!empty($mobile) && !empty($code)){//
            if(cookie('basecode')==$code){
                 Db::name('user')->where(['uid'=>$user_key])->update(['mobile' => $mobile]);
                 return json(['flag' =>1,'msg' => '更换成功']);
            }
        }else{
          return json(['flag' =>0,'msg' => '手机或验证码不能为空']);
        }
      }else{
         return $this->fetch();
       }
    }
    public function quorder() {
               // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
      if (request()->isAjax()) {
        $orderoid=input('orderoid');
        if(!empty($orderoid)){
           Db::name('order')->where(['oid'=>$orderoid])->update(['status' => 4]);
          return json(['flag' =>1,'msg' => '取消成功']);
        }else{
          return json(['flag' =>0,'msg' => '订单号不能为空']);
        }
      }
    }
     public function delorder() {
               // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
      if (request()->isAjax()) {
        $orderoid=input('orderoid');
        if(!empty($orderoid)){
           Db::name('order')->where(['oid'=>$orderoid])->delete();
          return json(['flag' =>1,'msg' => '删除成功']);
        }else{
          return json(['flag' =>0,'msg' => '订单号不能为空']);
        }
      }
    }




    public function resetPay() {
       cookie('url_back',__APPURL__,600);
      
                    // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
       $this->assign('mobile',$user_key);//用户
      return $this->fetch();
    }
    public function norememberpay() {
        cookie('url_back',__APPURL__,600);
                   // 判断是否登录
           $UserModel = new UserModel;
           $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
       $this->assign('mobile',$user_key);
       return $this->fetch();
       
     }     
        
    public function getcodepay(){
         $UserModel = new UserModel;
          $user_key= $UserModel->isLogin();
        if($user_key ==0){ 
          header("location:http://login.bxgogo.com");
         exit;
        }
      if($this->CheckURL()){
            $this->assign('mobile',$user_key);//用户
            $code=baserand();//获取随机数
            cookie('base_code',$code,600);
            $obj = & load_alipay('Aliyunsend');
            $result = $obj->send($user_key,['code'=>$code,'product'=>'百信购'],'SMS_44440686');
             if ($result !== true){
                return json(['flag' =>0,'msg' => $obj->errMsg]);
             }else{
                return json(['flag' =>1,'msg' => "短信下发成功"]);
             }
          }else{
            return '非法提交数据';
          }
      $this->assign('mobile',$user_key);
       return $this->fetch(); 
    }

       
    public function noRememberPayNext() {
      cookie('url_back',__APPURL__,600);
         $code=input('code');
        $mscode=cookie('base_code');
        if (request()->isAjax()) {
            if($code != $mscode){
                  return json(['flag' =>0,'msg' => '验证码输入错误!']);
            }else{
                  return json(['flag' =>1,'msg' => '验证通过']);
            }
        }else{
          return '非法提交数据';
        }
    }
    public function rememberPay() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
     // 修改支付密码记得原密码
      public function paypassnext() {
           // 判断是否登录
          $UserModel = new UserModel;
           $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
       $user_key=(string)$user_key;
         if (request()->isAjax() ) {
          $pass=input('pass');
          $newpass=input('newpass');
          $repass=input('repass');
            if(!empty($pass) && !empty($newpass) && !empty($repass)){
              $password =encryt_data(json_encode(['uid'=>$user_key,'pass'=>$pass]),config('PrivateKeyFilePath')); //密码
              $addpassword =encryt_data(json_encode(['uid'=>$user_key,'pass'=>$repass]),config('PrivateKeyFilePath')); //新密码
              $user=Db::name('user')->where('uid',$user_key)->find();
              if($user){
                  if($user['paypass']!=$password){
                    return json(['flag' =>0,'msg' => '原密码输入错误']);
                  }
                 if($user['paypass']!=$addpassword){
                    Db::name('user')->where(['uid'=>$user_key])->update(['paypass' =>$addpassword]);
                    return json(['flag' =>1,'msg' => '设置成功']);
                  }else{
                    return json(['flag' =>0,'msg' => '新密码不能和旧密码相同']);
                  }                
              }else{
                return json(['flag' =>0,'msg' => '账号不存在！']);
              }
            }else{
              return json(['flag' =>0,'msg' => '新密码和旧密码都不能为空！']);
            }
          }
    }
    public function passwordSet() {
       cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    // 重置密码
    public function resetPass() {
      cookie('url_back',__APPURL__,600);
               // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
    $this->assign('mobile',$user_key);//用户
      return $this->fetch();
    }
    public function noRememberPass() {
       cookie('url_back',__APPURL__,600);
                   // 判断是否登录
          $UserModel = new UserModel;
           $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
       $this->assign('mobile',$user_key);
       return $this->fetch();
    }
    public function norememberpassnext() {
        cookie('url_back',__APPURL__,600);
        $code=input('code');
        $mscode=cookie('base_code');
        if (request()->isAjax()) {
            if($code != $mscode){
                  return json(['flag' =>0,'msg' => '验证码输入错误!']);
            }else{
                  return json(['flag' =>1,'msg' => '验证通过']);
            }
        }else{
          return '非法提交数据';
        }
    }
 
     public function CheckURL(){
      $servername=input('server.SERVER_NAME');
      $sub_from=input('server.HTTP_REFERER');
      $sub_len=strlen($servername); 
      $checkfrom=substr($sub_from,7,$sub_len); 
      if($checkfrom!=$servername){
        return false;
      }else{
        return true;
      }
    }
    // 根据手机验证码修改手机
    public function rememberPass() {
        cookie('url_back',__APPURL__,600);
                   // 判断是否登录
          $UserModel = new UserModel;
           $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       } 
      return $this->fetch();
    }
    // 修改密码记得原密码
      public function addpassnext() {
           // 判断是否登录
          $UserModel = new UserModel;
           $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
       $user_key=(string)$user_key;
         if (request()->isAjax() ) {
          $pass=input('pass');
          $newpass=input('newpass');
          $repass=input('repass');
            if(!empty($pass) && !empty($newpass) && !empty($repass)){
              $password =encryt_data(json_encode(['uid'=>$user_key,'pass'=>$pass]),config('PrivateKeyFilePath')); //密码
              $addpassword =encryt_data(json_encode(['uid'=>$user_key,'pass'=>$repass]),config('PrivateKeyFilePath')); //新密码
              $user=Db::name('user')->where('uid',$user_key)->find();
              if($user){
                  if($user['pass']!=$password){
                    return json(['flag' =>0,'msg' => '原密码输入错误']);
                  }
                 if($user['pass']!=$addpassword){
                    Db::name('user')->where(['uid'=>$user_key])->update(['pass' =>$addpassword]);
                    return json(['flag' =>1,'msg' => '设置成功']);
                  }else{
                    return json(['flag' =>0,'msg' => '新密码不能和旧密码相同']);
                  }                
              }else{
                return json(['flag' =>0,'msg' => '账号不存在！']);
              }
            }else{
              return json(['flag' =>0,'msg' => '新密码和旧密码都不能为空！']);
            }
          }
    }
    public function myPrivacy() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function privacy() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function aboutBx() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
     public function feedback() {
      cookie('url_back',__APPURL__,600);
      // 判断是否登录
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
      if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
        exit;
      }
      $feedback=input('dataTxt');
      if(!empty($feedback)){
        $uid=Db::name('aboutbx')->where('uid',$user_key)->find();
        if($uid){
          return json(['flag' =>0,'msg' => '您已提交过反馈!']);
        }else{
          $result=Db::name('aboutbx')->insert(['feedback'=>$feedback,'uid'=>$user_key,'time'=>time()]);
          if($result){
            return json(['flag' =>1,'msg' => '感谢您的反馈!']);
          }else{
            return json(['flag' =>0,'msg' => '反馈失败，请重试!']);
          }
        }
      }
      return $this->fetch();
    }
    public function goScore() {
      cookie('url_back',__APPURL__,600);
       // 判断是否登录
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
      if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
        exit;
      }
      $serve=input('serve');
      $visit=input('visit');
      if(!empty($serve)&&!empty($visit)){
        $uid=Db::name('goscore')->where('uid',$user_key)->find();
        if($uid){
          return json(['flag' =>0,'msg' => '您已对此版平台评分!']);
        }else{
          $result=Db::name('goscore')->insert(['serve'=>$serve,'speed'=>$visit,'uid'=>$user_key,'time'=>time()]);
          if($result){
            return json(['flag' =>1,'msg' => '提交成功，非常感谢您的评分！']);
          }else{
            return json(['flag' =>0,'msg' => '提交失败，请重试！']);
          }
        }  
      }
      return $this->fetch();
    }
    public function copyrightInfo() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function moreTool() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function footprint() {
      cookie('url_back',__APPURL__,600);
      $UserModel = new UserModel;
        $user_key= $UserModel->isLogin();
        if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
          exit;
        }
       if(request()->isAjax()){
         $goodsid=input('goodsid');
         if($goodsid){
            $footprint=Db::name('footprint')->where(['uid'=>$user_key,'goods_id'=>$goodsid])->delete();
         }else{
            $footprint=Db::name('footprint')->where(['uid'=>$user_key])->delete();
         }
         if($footprint){
            return json(['flag' =>1,'msg' => '删除成功!']);
         }else{
            return json(['flag' =>0,'msg' => '删除失败!']);
         }
       }
       $cfootprint=Db::name('footprint')->where(['uid'=>$user_key])->order('time desc')->select();
       foreach($cfootprint as $value){
       	   $goods[]=Db::name('goods')->where(['goods_id'=>$value['goods_id']])->find();
       }
       if(isset($goods)){
       	  $this->assign('goods',$goods); 
       }else{
       	 $this->assign('goods',array()); 
       }      
      return $this->fetch();
    }
    public function focushop() {
        cookie('url_back',__APPURL__,600);
        // 判断是否登录
        $UserModel = new UserModel;
        $user_key= $UserModel->isLogin();
        if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
          exit;
        }
         $follow_seller=Db::name('m_follow')->alias('a')->join('seller b','a.seller_id=b.seller_id')->where('a.uid',$user_key)->field('a.*,b.city,b.logo')->select();
         if($follow_seller){
          foreach ($follow_seller as $key => $value) {
            $follow_seller[$key]['count']=Db::name('m_follow')->where('seller_id',$value['seller_id'])->count();
          }
         }
         if(request()->isAjax()){
           $seller_id=input('goodsid');
           if($seller_id){
              $focushop=Db::name('m_follow')->where(['uid'=>$user_key,'seller_id'=>$seller_id])->delete();
           }else{
              $focushop=Db::name('m_follow')->where(['uid'=>$user_key])->delete();
           }
           if($focushop){
              return json(['flag' =>1,'msg' => '删除成功!']);
           }else{
              return json(['flag' =>0,'msg' => '删除失败!']);
           }
        }
        if(isset($follow_seller)){
          $this->assign('follow', $follow_seller);
        }else{
          $this->assign('follow',array()); 
        }   
        return $this->fetch();
    }
    public function focusware() {
      cookie('url_back',__APPURL__,600);
       $UserModel = new UserModel;
        $user_key= $UserModel->isLogin();
        if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
          exit;
        }

      $foculist=Db::name('follow')->alias('f')->join('seller s','f.seller_id=s.seller_id')->where('f.uid',$user_key)->field('f.*,s.sname as seller_name,s.mobile,s.xprice,s.city,s.logo')->select();

      if($foculist){  
        foreach ($foculist as $key => $value){ 
          $list[$value['seller_id']][] = array(
            'goods_id'=>$value['goods_id'],//
            'uid'=>$value['uid'],
            'sname'=>$value['sname'],//
            'mobile'=>$value['mobile'],// 
            'price' => $value['price'],//
            'pic'=>$value['pic'],//
            'seller_id'=>$value['seller_id'],
            'xprice'=>$value['xprice'],
            'seller_name' =>$value['seller_name'] ,//
            'logo' =>$value['logo'] ,//
            'city'=>$value['city'],
            'logo'=>$value['logo'],
            'follow_count'=>Db::name('follow')->where('goods_id',$value['goods_id'])->count(),
          );
        }
       $this->assign('list',$list);
       return $this->fetch();
      }else{
      	$this->assign('list','');
      	return $this->fetch();
      }
       
    }
    public function mygift() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function userSurvey() {
      cookie('url_back',__APPURL__,600);
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
      if($user_key ==0){ 
      header("location:http://login.bxgogo.com");
        exit;
      }
      $pram=input('data');
      if(!empty($pram)){
        $prams=explode('&',$pram);
        foreach ($prams as $key => $value) {
          $value=mb_substr($value,7);
          $prams[$key]=$value;
        }
        $data=[
          'uid'=>$user_key,
          'time'=>time(),
          'reason'=>$prams[0],
          'first_time'=>$prams[1],
          'otime'=>$prams[2],
          'sex'=>$prams[3],
          'age'=>(int)$prams[4],
          'occupation'=>$prams[5]
      ];
      $uid=Db::name('usersurvey')->where('uid',$user_key)->find();
        if($uid){
         return json(['flag' =>0,'msg' => '您已提交过问卷!']);
        }else{
          $result=Db::name('usersurvey')->insert($data);
          if($result){
            return json(['flag' =>1,'msg' => '提交成功!']);
          }else{
            return json(['flag' =>0,'msg' => '提交失败!']);
          }
        }
      }
      return $this->fetch();

    }
    public function cooperation() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function friend() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function userstatement(){
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function useragreement(){
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function mobilepay(){
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function enter() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function callCenter() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function agreement() {
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function myevaluate(){
      cookie('url_back',__APPURL__,600);
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
      if($user_key ==0){ 
      header("location:http://login.bxgogo.com");
      exit;
      }
       $myevaluate=Db::name('comment')->alias('a')->join('order b','a.goods_id=b.goods_id')->join('user c','a.uid=c.uid')->order('b.oid desc')->where('a.uid',$user_key)->distinct(true)->field('a.*,b.sname,b.price,c.uname')->select();

      // if(count($myevaluate)>=2){
      //    array_pop($myevaluate);
      // }
      // 
      foreach ($myevaluate as $key=> $value) {
            if(strstr($value['img'],",")){
                $myevaluate[$key]["img"]=explode(',',$value['img']);
            }
      }

      $this->assign('myevaluate',$myevaluate);
      return $this->fetch();
    }
	 //删除评论
    public function delevaluate(){
      cookie('url_back',__APPURL__,600);
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
      if($user_key ==0){ 
      header("location:http://login.bxgogo.com");
      exit;
      }
      $id=input('id');
      if(!empty($id)){
        $result=Db::name('comment')->where(['uid'=>$user_key,'id'=>$id])->delete();
        if($result){
           return json(['flag'=>1,'msg'=>'删除成功！']);
        }else{
          return json(['flag'=>0,'msg'=>'删除失败，请重试！']);
        }
      }else{
        return json(['flag'=>0,'msg'=>'删除失败，请重试！']);
      }
    }

       //评论详情
    public function evaluatedetail(){
      cookie('url_back',__APPURL__,600);
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
      if($user_key ==0){ 
      header("location:http://login.bxgogo.com");
      exit;
      }
      $id=input('id');
      $evaluatedetail=Db::name('comment')->alias('a')->join('order b','a.goods_id=b.goods_id')->join('user c','a.uid=c.uid')->where(['a.uid'=>$user_key,'a.id'=>$id])->field('a.*,b.sname,b.price,b.spec_key_name,c.uname')->select();
      $this->assign('evaluatedetail',$evaluatedetail);
      $allcomment=Db::name('comment')->alias('a')->join('user b','a.uid=b.uid')->where('a.uid',$user_key)->field('a.*,b.uname')->select();
      $this->assign('allcomment',$allcomment);
      return $this->fetch();
    }
   
    public function insurance(){
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function moreservice(){
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }
    public function vipcard(){
      cookie('url_back',__APPURL__,600);
      return $this->fetch();
    }

    // 账户余额
   public function myBalance() {
      cookie('url_back',__APPURL__,600);
// 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
   if($user_key ==0){ 
        header("location:http://login.bxgogo.com");
       exit;
   }
      $total=Db::name('bankrollnd')->where(['uid'=>$user_key])->find();
//    dump($total['total']);
//    die;
      $shuju=round($total['total'],2);
       $this->assign('bankrollnd',$shuju);//用户
      return $this->fetch();
    }

    // 我的钱包
    public function myWallet() {
      cookie('url_back',__APPURL__,600);
      // 判断是否登录
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
	   if($user_key ==0){ 
			header("location:http://login.bxgogo.com");
		   exit;
	   }
       $signin_key =decrypt_data(cookie('user_data'),config('PublicKeyFilePath')); //解密用户数据
       $signin_key =json_decode($signin_key,true); //
       $this->assign('user_list',$signin_key);//
       $dr=Db::name('bankrollnd')->where(['uid'=>$user_key])->find();
       $data=array('user_key'=>$user_key,'total'=>$dr['total']+$dr['is_profit']+$dr['money'],'profit'=>$dr['profit'],'day_profit'=>$dr['day_profit'],'is_profit'=>$dr['is_profit'],'money'=>$dr['money']);
       $this->assign('user',$data);//
       $userico=Db::name('user')->where('uid',$user_key)->value('icon');
       $this->assign('userico',$userico);
	   $redmoney = $dr['red_money'];
	   $this->assign('redmoney',$redmoney);
      return $this->fetch();
    }
    public function myStatement(){
    	//查询账户明细信息
    	$UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
	   if($user_key ==0){ 
	        header("location:http://login.bxgogo.com");
	       exit;
	   }
     $type=input('type',0);
      $this->assign('type',$type);
      return $this->fetch();
    }

    public function ajax_mx(){
      //查询账户明细信息
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
     if($user_key ==0){ 
          header("location:http://login.bxgogo.com");
         exit;
     }
     
     $type=input('type',0);
   if($type ==1){
     $p=input('pageid');
        $ming=Db::name('capital_detailed')->where(['uid'=>$user_key,'typeid'=>4])->order('time desc')->page($p,10)->select();
         if($ming){

          $this->assign('ming',$ming);
          $this->assign('type',1);
          return $this->fetch();
        }else{
         return 0;
        }
   }else if($type ==2){
     $arr=array();
      $p=input('pageid');
   	 $ming=Db::table('tuiguan_user')->where(['u_id'=>$user_key])->order('time desc')->page($p,10)->select();
         if($ming){
           foreach($ming as $value)
           {
             $tui = Db::name('user')->where(['uid'=> $value['tui_id']])->field('tuijian_q')->find();
             $tuiq=1;
			if(!empty($tui))
            {
               $tuiq = $tui['tuijian_q'];
            }
             $value['type']='推荐用户:'.$value['tui_id'];
             $value['total']=0;
               $value['typeid']=0;
             $value['money']=($tuiq!=1?'已验证':'<font color="#999">未验证</font>');
             $arr[]=$value;
           }
          $this->assign('ming',$arr);
          $this->assign('type',2);
          return $this->fetch();
        }else{
         return 0;
        }
   }else{
    $p=input('pageid');
     $typeid=input('index')+1;
          if(!empty($p)&&!empty($typeid)){
        if($typeid==1){
         $ming=Db::name('capital_detailed')->where(['uid'=>$user_key])->where('typeid != 4')->order('time desc')->page($p,10)->select(); 
       }elseif($typeid==2){
         $ming=Db::name('capital_detailed')->where(['uid'=>$user_key,'typeid'=>2])->order('time desc')->page($p,10)->select();
        
       }elseif($typeid==3){
         $ming=Db::name('capital_detailed')->where(['uid'=>$user_key,'typeid'=>1])->order('time desc')->page($p,10)->select();
       }
        if($ming){
          $this->assign('ming',$ming);
		  $this->assign('type',0);
          return $this->fetch();
        }else{
         return 0;
        } 
     }else{
       return 0;
     }
   }
    }
   public function myPay() {

      // 判断是否登录
       $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
      $price=input('price');
      $type=input('type');
      if(!empty($price)){
         $OrderSn = baseOrderSn();//订单号
         if($type==0){
          Db::name('recharge')->insert(['oid'=>$OrderSn,'uid'=>$user_key,'status'=>2,'otime'=>time(),'typeid'=>$type,'payprice'=>$price,'sname'=>'账户充值']);
            $paydate['out_trade_no']  =$OrderSn;
            $paydate['subject']       ='账户充值';//订单名称
            $paydate['body']          ='百信购订单';//描述
            $paydate['total_fee']     =$price;//价格
            $paydate['urltype']       =$type;//类型
        }elseif($type==1){
            Db::name('recharge')->insert(['oid'=>$OrderSn,'uid'=>$user_key,'status'=>2,'otime'=>time(),'typeid'=>$type,'payprice'=>$price,'sname'=>'短信充值']);
            $paydate['out_trade_no']  =$OrderSn;
            $paydate['subject']       ='短信充值';//订单名称
            $paydate['body']          ='百信购订单';//描述
            $paydate['total_fee']     =$price;//价格
            $paydate['urltype']       =$type;//类型
        } 
      $sign = sign(getSignContent($paydate),strtoupper( 'RSA' ),config('PrivateKeyFilePath'));
      cookie('sign',$sign,3600);
      setcookie("sign", $sign, time() + 3600, "/", "bxgogo.com");

        header("location:http://pay.bxgogo.com/index/index/index/?" . http_build_query($paydate));
        exit;
      }else{
        cookie('url_back',__APPURL__,600);
        return $this->fetch();
      }
    }
    public function myKiting(){
        cookie('url_back',__APPURL__,600);
        $UserModel = new UserModel;
        $user_key= $UserModel->isLogin();
        if($user_key ==0){ 
          header("location:http://login.bxgogo.com");
          exit;
        }
        $crname = input("crname");
        $crid = input('crid');
        if(!$crname){
          $data=Db::name('fund_mode')->where(['uid'=>$user_key])->find();
          $this->assign('shoplist',$data);
        }else{
           $data=Db::name('fund_mode')->where(['uid'=>$user_key])->where(['crname'=>$crname])->where(['crid'=>$crid])->find();
           $this->assign('shoplist',$data);
        }
        
        $balance=Db::name('bankrollnd')->where(['uid'=>$user_key])->find();
        $this->assign('balance',$balance);
        return $this->fetch();
    }
	//发起提现
	public function ajaxmykiting() {
        $UserModel = new UserModel;
        $user_key= $UserModel->isLogin();
        $id = input('id');
        $price=input('price');
        if(request()->isAjax()) {
          $databankr=Db::name('bankrollnd')->where('uid',$user_key)->find();
          if($databankr['total']<$price){
               return json(['flag' =>0,'msg'=>'可用资金不足']);   
          }
          if($price<=0){
              return json(['flag' =>0,'msg'=>'请输入正数']);  
          }
          $data=Db::name('fund_mode')->where('id',$id)->find();
          if($data){
            Db::name('usercredit')->insert(['price'=>$price,'otime'=>time(),'uid'=>$user_key,'fid'=>$id]);
             $oldtotal=Db::name('bankrollnd')->where('uid',$user_key)->value('total');
             $shuju=[
                'time'=>time(),
                'uid'=>$user_key,
                'type'=>"账户提现",
                'money'=>$price,
                'typeid'=>2,
                'total'=>$oldtotal-$price, 
             ];
             Db::name('capital_detailed')->insert($shuju);
       Db::name('bankrollnd')->where('uid',$user_key)->setField('total',$oldtotal-$price);
          $order= Db::name('order')->where('uid',$user_key)->where('status',3)->update(['fid'=>null,'okprice'=>null,'bprice'=>null]);
            return json(['flag' =>1,'msg'=>'提现发起成功']);   //已支付
          }else{
            return json(['flag' =>0,'msg'=>'提现发起失败']);   //已支付
          }
        }
     }

    //转账
    public function transfer(){
    	cookie('url_back',__APPURL__,600);
        $UserModel = new UserModel;
        $user_key= $UserModel->isLogin();
        if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
		if(request()->isAjax()){
			$tel=input('uid');
			if($tel==$user_key){
				return json(['flag'=>0,'msg'=>'与当前用户账号相同，请检查！']);
			}else{
				$user=Db::name('user')->where('uid',$tel)->value('uname');
				if($user){
					return json(['flag'=>1,'msg'=>$user]);
				}else{
					$seller=Db::name('seller')->where('seller_id',$tel)->value('sname');
					if($seller){
						return json(['flag'=>1,'msg'=>$seller]);
					}else{
						return json(['flag'=>0,'msg'=>'转账用户不存在，请检查转账账号']);
					}
				}
			}	
		}
    	return $this->fetch();
    }
	public function transinfo(){
		cookie('url_back',__APPURL__,600);
        $UserModel = new UserModel;
        $user_key= $UserModel->isLogin();
        if($user_key ==0){ 
           header("location:http://login.bxgogo.com");
           exit;
        }
		$tel=input('uid');
		//$user=mb_convert_encoding(input('user'),'UTF-8','GBK');
		$user=input('user');
		$this->assign('user',$user);
		$this->assign('total',DB::name('bankrollnd')->where('uid',$user_key)->value('total'));
		$this->assign('mobile',$tel);
		return $this->fetch();
	}
	public function transok(){
		cookie('url_back',__APPURL__,600);
        $UserModel = new UserModel;
        $user_key= $UserModel->isLogin();
        if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
        }
		//$user=mb_convert_encoding(input('user'),'UTF-8','GBK');
		$user=input('user');
		$tel=input('tel');
		$money=input('money');
		$uicon=Db::name('user')->where('uid',$user_key)->value('icon');
		$bicon=Db::name('user')->where('uid',$tel)->value('icon');
				$this->assign('user',$user); 
				$this->assign('tel',$tel);
				$this->assign('money',$money);
				if($uicon){
					$this->assign('uicon',$uicon);
				}else{
					$uicon=Db::name('seller')->where('seller_id',$user_key)->value('logo');
					$this->assign('uicon',$uicon);
				}
                if($bicon){
					$this->assign('bicon',$bicon);
				}else{
					$uicon=Db::name('seller')->where('seller_id',$tel)->value('logo');
					$this->assign('bicon',$bicon);
				}
			return $this->fetch();
	}
	public function istransok(){
		cookie('url_back',__APPURL__,600);
        $UserModel = new UserModel;
        $user_key= $UserModel->isLogin();
        if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
        }
		$user=input('user');
		$tel=input('tel');
		$money=input('money');
		$zuser=Db::name('user')->where('uid',$user_key)->value('uname');
		//开始事物
			Db::startTrans();
			try{
			    $oldtotal=Db::name('bankrollnd')->where(['uid'=>$user_key])->value('total');
				$boldtotal=Db::name('bankrollnd')->where(['uid'=>$tel])->value('total');
			    Db::name('bankrollnd')->where(['uid'=>$user_key])->setField('total',$oldtotal-$money);
				Db::name('capital_detailed')->insert(['time'=>time(),'uid'=>$user_key,'type'=>"向 ".$user." 转账",'money'=>$money,'typeid'=>2,'total'=>$oldtotal-$money]);
				Db::name('bankrollnd')->where(['uid'=>$tel])->setField('total',$boldtotal+$money);
				Db::name('capital_detailed')->insert(['time'=>time(),'uid'=>$tel,'type'=>"收钱-收到 ".$zuser." 的转账",'money'=>$money,'typeid'=>1,'total'=>$boldtotal+$money]);
			    // 提交事务
			    Db::commit();
				Db::name('bankrollnd')->where(['uid'=>$user_key])->setField('total',$oldtotal);
				Db::name('bankrollnd')->where(['uid'=>$tel])->setField('total',$boldtotal);
				return json(['flag'=>1]); 
			} catch (\Exception $e) {
			    // 回滚事务
			    Db::rollback();
			    return json(['flag'=>0,'msg'=>"转账失败,请重试！"]);  
			}
	}
	
    public function selkitway(){
      cookie('url_back',__APPURL__,600);
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
      $data=Db::name('fund_mode')->where(['uid'=>$user_key])->field('crid,crname,cname,crtype')->select();
      if($data){
         foreach ($data as $key => $value){ 
          $list[$key]['crid'] = $value['crid'];  
          $list[$key]['crname']= $value['crname']; 
          $list[$key]['cname']= $value['cname'];
          $list[$key]['crtype']= $value['crtype'];
        }
      }

      if(request()->isAjax()){
        $crid = input('crid');
        $crname = input('crname');
		$thisid = Db::name('fund_mode')->where(['uid'=>$user_key,'crname'=>$crname,'crid'=>$crid])->value('id');
	    if(Db::name('usercredit')->where(['fid'=>$thisid,'status'=>0])->find())
		{
			return json(['flag'=>0,'msg'=>'存在未处理的提现记录']);
		}else{
			$del= Db::name('fund_mode')->where(['id'=>$thisid])->delete();
		}
		
       // $del = Db::name('fund_mode')->where(['uid'=>$user_key,'crname'=>$crname,'crid'=>$crid])->delete();
        if($del){
          return json(['flag'=>1,'msg'=>'删除成功']);
        }else{
          return json(['flag'=>0,'msg'=>'删除失败']);
        }
      }
      if(isset($list)){
        $this->assign('data',$list);
      }else{
        $this->assign('data',array());
      }
       
       return $this->fetch();
    }

    public function addkitway(){
         cookie('url_back',__APPURL__,600);
          // 判断是否登录
        $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
         if($user_key ==0){ 
              header("location:http://login.bxgogo.com");
             exit;
           }
         return $this->fetch();
    }

    // 添加支付方式
    public function checkkitway(){
      // $type=input('type');
      $crtype=input('type');
      $number=input('data');
           // 如果选中为支付宝
      $result = Db::name('fund_mode')->where('crtype',$crtype)->where('crid',$number)->find();
      if($result){
        return json(['flag'=>1,'msg'=>'该提现方式已存在！']);
      }else{
        return json(['flag'=>0,'msg'=>'该提现方式不存在，可以添加！']);
      }
    }
    
   public function checkbank(){
     if(request()->isAjax()){
        $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
           $crtype=input('type');
            $name=input('name');
            $number=input('data');
            // $price=input('price');
            $code=input('code');
            $crname=input('crname');
            

          $data=[
                'uid'=>$user_key,
                'crid'=>$number,
                'cname'=>$name,
                'crname'=>$crname,
                'crtype'=>$crtype,
          ];

          if($code == cookie('base_code')){
            $record=Db::name('fund_mode')->insert($data);
            if($record){
              return json(['flag'=>1,'msg'=>'添加成功']);
            }else{
              return json(['flag'=>0,'msg'=>'添加失败']);
            }
          }else{
            return json(['flag'=>2,'msg'=>'验证码输入错误']);
          }
       
        }else{
             cookie('url_back',__APPURL__,600);
             $UserModel = new UserModel;
             $user_key= $UserModel->isLogin();
               if($user_key ==0){ 
                    header("location:http://login.bxgogo.com");
                   exit;
                 }
            $type=input('type');
            $data=input('data');
            $crname=input('crname');
            $this->assign('data',$data);
            $this->assign('type',$type);
            $this->assign('crname',$crname);
            $this->assign("mobile",$user_key);
            return $this->fetch();
        }
    }
    
   public function checkcode(){
               $UserModel = new UserModel;
               $user_key= $UserModel->isLogin();
           if($user_key ==0){ 
                header("location:http://login.bxgogo.com");
               exit;
           }
           if($this->CheckURL()){
               $this->assign('mobile',$user_key);//用户
               $code=baserand();//获取随机数
               cookie('base_code',$code,600);
                $obj = & load_alipay('Aliyunsend');
              $result = $obj->send($user_key,['code'=>$code,'product'=>'百信购'],'SMS_44440686');
              if($result){
                  return json(['flag'=>1,'msg'=>'验证码已发到您的手机，请注意查收！']);
              }else{
                return json(['flag'=>0,'msg'=>'验证码发送失败，请重新获取！']);
              }
           }else{
            return '非法提交数据';
           }
   }
    
    public function vipcenter() {
      cookie('url_back',__APPURL__,600);
       $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }

       $vipstatus = Db::name('user')->where('uid',$user_key)->find();

       $this->assign('user',$vipstatus);

      return $this->fetch();
    }
    public function vipupgrade() {
      cookie('url_back',__APPURL__,600);
      $type=input('type');
      $oid= baseOrderSn();
      $this->assign('oid',$oid);
      $this->assign('type',$type);
      return $this->fetch();

    }
    // 会员升级充值
    public function recharge(){
      $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
       if($user_key ==0){ 
            header("location:http://login.bxgogo.com");
           exit;
       }
           $data=[
                "oid"=>input('oid'),
                "uid"=>$user_key,
                "status"=>2,
                "payprice"=>input('payprice'),
                "sname"=>"会员升级"
           ];
            $listRs =Db::name('Recharge')->insert($data);
            if($listRs){
                    return json(['flag'=>1,'msg'=>'下单成功']);
            }else{
                    return json(['flag'=>0,'msg'=>'下单失败']);
            }
    }

    public function vipprivilege() {
      cookie('url_back',__APPURL__,600);
      $UserModel = new UserModel;
      $user_key= $UserModel->isLogin();
      $vip = Db::name('user')->where('uid',$user_key)->find();
      $this->assign('user_qid',$vip['qid']);
      $this->assign('user',$vip);
      return $this->fetch();
    }
    public function privilegedetails() {
      cookie('url_back',__APPURL__,600);
      $privid=input('privid');
      $this->assign('privid',$privid);
      return $this->fetch();
    }
    
    
    //扫码注册
    public function user_vip(){
		$user_key=input('user',0);
		$user=Db::name('user')->where('uid',$user_key)->find()['code'];
    $str="";
		// setcookie("user_commend", $user_key, time() + 3600, "/", "bxgogo.com");
		if(!empty($user)){
			   $str='/index/index/tuijian/'.$user;
    

		} header("location:http://login.bxgogo.com"."$str"); 
      die;
		
    }
    public function recommend(){
       $UserModel = new UserModel;
       $user_key= $UserModel->isLogin();
	$user=Db::name('user')->where('uid',$user_key)->find();
       $money=Db::name('user')->where('recommend',$user_key)->where('qid',7)->count()*5;
      $num=Db::name('user')->where('recommend',$user_key)->count();
  $this->assign('type',1);
      if($user['tuijian_q']>1)
      {
      	$this->assign('type',2);
        $sumnum=Db::table('tuiguan_user')->where('u_id',$user_key)->where('time >'.strtotime(date('Y-m-d')))->count();
         $benzhou=Db::table('tuiguan_user')->where('u_id',$user_key)->where('time >'.strtotime(date('Y-m-d')))->select();
         $num = 0;
			foreach($benzhou as $value)
            {
              $tui = Db::name('user')->where(['uid'=> $value['tui_id']])->field('tuijian_q')->find();
              if(!empty($tui))
              {
                if($tui['tuijian_q']>1){
                   $num++;
                }
              }
            }
         $money= $num * 3;
        if($num>=5)
        {
           $money= $num * 5;
        }
        $num =  $sumnum.'/'.$num;
      }
       $code=Db::name('user')->where('uid',$user_key)->find()['code'];
      
       $this->assign('code',$code);
       $this->assign('money',$money);
       $this->assign('num',$num);
	   return $this->fetch();    	
    }
// 代金券开始
public function coupon(){
  // 查询当前用户的代金券
  $UserModel = new UserModel;
  $user_key= $UserModel->isLogin();
  $time=date('Y-m-d',time());
  $status=0;
  $coupon=Db::name('coupon')->where('user_id',$user_key)->where("d_time",">=","$time")->select();
  foreach ($coupon as $key => $value) {
    $coupon[$key]['seller_id']=Db::name('seller')->where('seller_id',$value['seller_id'])->find()['sname'];
    $coupon[$key]['logo']=Db::name('seller')->where('seller_id',$value['seller_id'])->find()['logo'];
  }
  $this->assign('coupon',$coupon);
  return $this->fetch();
}
// 代金券结束


//兑换页面生成
public function duihuan(){
    $UserModel = new UserModel;
    $user_key= $UserModel->isLogin();
	cookie('url_back',__APPURL__,600);
    if($user_key ==0){ 
		header("location:http://login.bxgogo.com");
		exit;
    }
	
	$user=Db::name('user')->where('uid',$user_key)->find();
	$count=Db::query("SELECT count(uid) count FROM `b_user`  where recommend = $user_key and is_duihuan is null")['0']['count'];
	$this->assign('count',$count);
	$this->assign('user',$user);
	return $this->fetch();
}

//检查是否满足兑换资格
public function ajaxduihuan(){
	$UserModel = new UserModel;
	$user_key= $UserModel->isLogin();
	if($user_key ==0){ 
		header("location:http://login.bxgogo.com");
		exit;
	}
	$count=Db::query("SELECT count(uid) count FROM `b_user`  where recommend = $user_key and is_duihuan is null")['0']['count'];
	$click=input('click');
	switch ($click) {
		case 1: //大健康体检
			if($count>=10)
			{
				$duihuan = $this->duihuanck(10,'美年大健康体检券');
			}
			return $this->fetch('duihuanerror');
		break;
		case 2: //电影票
			if($count>=20)
			{
				$duihuan = $this->duihuanck(20,'北国先天下电影票');
			}else if($count>=10){
				header("location:http://m.bxgogo.com/index/user/duihuanmypay/price/10/deduction/10?remarks=10元兑北国先天下电影票");
				exit;
			}
			return $this->fetch('duihuanerror');
			break;
			case 3: //绿溪谷夜场
				if($count>=20)
				{
					$duihuan = $this->duihuanck(20,'绿溪谷夜场票(周边县区户口)');
				}else if($count>=10){
					header("location:http://m.bxgogo.com/index/user/duihuanmypay/price/20/deduction/10?remarks=20元兑换绿溪谷夜场票");
					exit;
				}
				return $this->fetch('duihuanerror');
				
			break;
			case 4: //绿溪谷通票
				if($count>=40)
				{
					
					$duihuan = $this->duihuanck(40,'绿溪谷通票');
				}else if($count>=10){
					header("location:http://m.bxgogo.com/index/user/duihuanmypay/price/50/deduction/10?remarks=50元兑换绿溪谷通票");
					exit;
				}
				return $this->fetch('duihuanerror');
				break;
		}
	return;
}
//免费兑换验证与完成兑换
public function duihuanck($deduction,$remarks){
		$UserModel = new UserModel;
		$user_key= $UserModel->isLogin();
		$oid = baseOrderSn();
		Db::name('prize')->insert(['oid'=>$oid,'uid'=>$user_key,'status'=>2,'wtime'=>time(),'payprice'=>0,'sname'=>'奖品兑换',"deduction"=>$deduction,"remarks"=>$remarks]);
		$alluser = Db::query("SELECT uid FROM `b_user` where is_duihuan is  null and recommend = $user_key order by ztime asc limit 0,$deduction;");
		if(count($alluser) != $deduction)
		{
			return 1;
		}
		$str='';
		foreach($alluser as $value)
		{
			$str .= $value['uid'].',';
		}
		$userdata['is_duihuan']=1;
		if(Db::name('user')->where('uid','in',$str)->update($userdata))
		{
			//兑换成功 跳转成功界面显示
			header("location:http://m.bxgogo.com/index/user/duihuanok/oid/$oid");
			exit;
		}else{
			//兑换失败
			return 0;
		}
}
//兑换成功界面显示
public function duihuanok(){
	$oid = input('oid');
	$UserModel = new UserModel;
	$user_key= $UserModel->isLogin();
	$duihuan = Db::name('prize')->where('oid', $oid)->where('uid', $user_key)->find();
	$this->assign('order',$duihuan);
	return $this->fetch();
}
public function duihuanerror(){
	return $this->fetch();
}
//兑换补充付款
public function duihuanmypay() {
	$UserModel = new UserModel;
	$user_key= $UserModel->isLogin();
	if($user_key ==0){ 
		header("location:http://login.bxgogo.com");
		exit;
	}
	$price=input('price');
	$deduction=input('deduction');
	$remarks=input('remarks');
	if(!empty($price) || !empty($deduction) || !empty($remarks)){
		$OrderSn = baseOrderSn();//订单号
		Db::name('prize')->insert(['oid'=>$OrderSn,'uid'=>$user_key,'status'=>1,'payprice'=>$price,'sname'=>'奖品兑换',"deduction"=>$deduction,"remarks"=>$remarks]);
		$paydate['out_trade_no']  =$OrderSn;
		$paydate['subject']       ='奖品兑换';//订单名称
		$paydate['body']          ='百信购订单';//兑换说明
		$paydate['total_fee']     =$price;//需要补充的价格
		$paydate['urltype']       =5;//兑换奖品标识
		$sign = sign(getSignContent($paydate),strtoupper( 'RSA' ),config('PrivateKeyFilePath'));
		cookie('sign',$sign,3600);
		setcookie("sign", $sign, time() + 3600, "/", "bxgogo.com");
		header("location:http://pay.bxgogo.com/index/index/index/?" . http_build_query($paydate));
        exit;
	}else{
		cookie('url_back',__APPURL__,600);
		return $this->fetch('duihuanerror');
	}
}
//群发
// public function ceshi(){
//   $mobile=array(
//       "15533292282",
//       "18331114869",
//       "18331153267",
//       "13398627280",
//       "15933530193",
//       "15176282851",
//       "18395708245",
//       "17731215575",
//       "13832256970",
//       "13931391638",
//       "13653325678",
//       "15933439854",
//       "18630283287",
//       "15175359503",
//       "15831265373",
//       "15128169781",
//       "13290681396",
//       "17503253535",
//       "15132275943",
//       "18903127100",
//       "15081265532",
//       "13930242439"
//     );
//   $user_moblie=Db::name('user')->select();
//   foreach ($user_moblie as $key => $value) {
//     if(!in_array($value['uid'], $mobile)){
//      $obj = & load_alipay('Aliyunsend');
//      $a =$obj->send("$value[uid]",['user'=>""],'SMS_82000056 ');
//     }
    
//   }
// }


public function dianhua(){
  $user_moblie=Db::name('dianhua')->select();
  foreach ($user_moblie as $key => $value) {
     $obj = & load_alipay('Aliyunsend');
     $a =$obj->send("$value[uid]",['user'=>""],'SMS_82000056 ');
     if($a){
     	Db::name('dianhua')->where('uid',$value['uid'])->update(['status'=>'1']);
     }
  }
}



}

