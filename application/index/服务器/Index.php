<?php
namespace app\index\controller;
use think\Db;
use think\Request;
use app\index\model\User as UserModel;
class Index extends \think\Controller
{
    /**
     * @首页
     */
	 
	public function setConfig($begin_h,$begin_m,$finish_h,$finish_m,$duration,$interval){
		$config['begin_h']=$begin_h;
		$config['begin_m']=$begin_m;
		$config['finish_h']=$finish_h;
		$config['finish_m']=$finish_m;
		$config['duration']=$duration;
		$config['interval']=$interval;
		return $config;
	}
    public function gettime(){
		 echo time()*1000;
	 }
   public function redconf(){
      if(Db::name('redconfig')->field('status')->where('status=1')->order('id asc')->find())
        {
          
          if($redstatus = Db::name('red')->where('status=1')->where("end>='".date('H:i')."'")->order('start asc')->find())
          {
          $start_time = explode(':',$redstatus['start']);
          $end_time = explode(':',$redstatus['end']);
          $config = $this->setConfig($start_time[0],$start_time[1],$end_time[0],$end_time[1],$redstatus['continued'],$redstatus['interval']);
  
          }else{
          $config = $this->setConfig(0,0,0,0,0,0);
        }
          
        }else{
          $config = $this->setConfig(0,0,0,0,0,0);
        }
      
        return json($config);
   } 

    public function index()
    {
	
				if(Db::name('redconfig')->field('status')->where('status=1')->order('id asc')->find())
				{
					
					if($redstatus = Db::name('red')->where('status=1')->where("end>='".date('H:i')."'")->order('start asc')->find())
					{
					$start_time = explode(':',$redstatus['start']);
					$end_time = explode(':',$redstatus['end']);
					$config = $this->setConfig($start_time[0],$start_time[1],$end_time[0],$end_time[1],$redstatus['continued'],$redstatus['interval']);
	
					}else{
				 	$config = $this->setConfig(0,0,0,0,0,0);
				}
					
				}else{
				 	$config = $this->setConfig(0,0,0,0,0,0);
				}
				
		
			//匹配时间段不能冲突
	$script = &  load_wechat('Script');
	      // 获取JsApi使用签名，通常这里只需要传 $ur l参数
	    $options = $script->getJsSign(__APPURL__);
	    // 处理执行结果
	    if($options===FALSE){
	    	$options='';
	     }else{
	      // 接口成功的处理	
	      $options=$options;
	    }
	    // dump($options);
         $this->assign('goodsappurl',__APPURL__);
	    $this->assign('options',$options);
   //if(Request::instance()->isMobile()){ // 是否为手机访问
     //楼层显示逻辑计算   我需要优化
       $where['status']=1;
        $data=Db::name('floorconfig')->where($where)->order("orderby asc")->select();
        foreach ($data as $key => $value) {
          $adwhere['markid']=$value['id'];
			if($value['ad']==1)
          $value['ad']=Db::name('advertising')->where($where)->where($adwhere)->order("orderby asc")->select();
			else $value['ad']='';
          $adwhere1['floorid']=$value['id'];
          $value['shop']=Db::name('floorcool')->where($where)->where($adwhere1)->select();
		  if(empty($value['shop']))
		  {
			  unset($value);
		  }else
		  {
			  $floor[]=$value;
		  }
          
        }
        
		

		$this->assign('floor',$floor);//楼层广告
        // 百信快报
        $bulle = Db::name('bulle')->alias('a')->join('bulle_type b','a.btype = b.id')->field('bname,tname')->select();
		$this->assign('bulle',$bulle); 
		
		//九宫格导航
        $blocknav=Db::name('blocknav')->limit(8)->where("status=1 and belongto='index'")->limit(8)->select();
		$this->assign('blocknav',$blocknav);
        //幻灯片输出
        
        $slide=Db::name('slide')->limit(7)->where('ismobile = 1')->order('id desc')->select();
		$this->assign('slide',$slide);
        //公告输出
        $message=Db::name('message')->order("time desc")->limit(1)->find();
        $this->assign('message',$message);
		 //热门搜索
		$hot=DB::name('keyword')->where('user',0)->order('hot desc')->limit(9)->select();
		$this->assign('hot',$hot);
	    //秒杀
		$mgoods=DB::name('goods')->where('is_on_sale',1)->where('is_mgoods',1)->limit(3)->select();
		$this->assign('mgoods',$mgoods);
		//特价
		$tgoods=DB::name('goods')->where('is_on_sale',1)->where('is_tgoods',1)->limit(5)->select();
		$this->assign('tgoods',$tgoods);
		//每日上新
		$newgoods=Db::name('goods')->alias('a')->where(['a.is_on_sale'=>1,'a.is_mgoods'=>0,'a.is_tgoods'=>0])->join('cha_level b','a.level = b.id ')->field('a.goods_id,guanzhu,a.sname,a.depict,a.goods_sn,a.pic,a.goods_price,b.points')->order('a.goods_id desc')->limit(6)->select();  
		if ($newgoods){
	       foreach ($newgoods as $key => $value){
	        $newgoodslist[$key]['goods_id'] = $value['goods_id'];
	        $newgoodslist[$key]['sname']= $value['sname']; 
	        $newgoodslist[$key]['depict']= $value['depict'];
	        $newgoodslist[$key]['goods_sn']= $value['goods_sn'];
	        $newgoodslist[$key]['pic']= $value['pic'];
	        $newgoodslist[$key]['goods_price']= $value['goods_price'];
	        $newgoodslist[$key]['acount'] = Db::name('order')->where(['status'=>1,'goods_id'=>$value['goods_id']])->count();
			$newgoodslist[$key]['save']=saveCalculation($value['goods_price'],$value['points'],2);
			$newgoodslist[$key]['sales']= $value['guanzhu'];//(empty($sales=Db::name('goods_sales')->field('num')->where(['goods_id'=>$value['goods_id']])->find()['num'])?0:$sales);
	       }
	       $this->assign('newgoodslist',$newgoodslist);
	    }

        cookie('url_back',__APPURL__,600);
//		网站配置 ？
        $this->assign('instal',Db::name('instal')->find());
      $this->assign('cur1',1);       //底部变换

	  $this->assign("date",time()*1000);
		$this->assign("red",$config);
		if($this->isWeixin() && !empty(cookie('wxpage')))
		{
			$list = $this->ajaxBigGet();
			$this->assign('wx',$list);
			$this->assign('wxpagea',cookie('wxpage'));
		}
        return $this->fetch();
     // }else{
     //  header("location:http://www.bxgogo.com");
      // exit;
    //  }
// 首页搜索

   }


   //定位处理
  public function latLng(){
    $data['adcode']= input('adcode');//行政区ID，六位数字, 前两位是省，中间是市，后面两位是区，比如深圳市ID为440300
    $data['nation']= input('nation');//国家
    $data['province']= input('province');//省份
    $data['city']= input('city');//城市
    $data['district']= input('district');//地区
    $data['addr']= input('addr');//详细地址
    $data['latLng']= input('lat');//火星坐标(gcj02)，腾讯、Google、高德通用
    $data['Lnglat']= input('lng');
    $data['accuracy']= input('accuracy');//误差范围，以米为单位
	
    cookie('citydata',$data, 3600);
	session('city',$data);
     if(!empty($data['city'])){
       return json(['flag' =>1,'city' =>$data['city']]);
     }else{
       return json(['flag' =>0,'msg' =>'定位失败']);
     }
    }
       //定位
  public function ceshi(){

    $data= input('acc');//
       return json(['flag' =>1,'city' =>$data]);
    }
//猜你喜欢
public function getTwolevel(){
	
}
public function isWeixin() { 
  if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
    return true; 
  } else {
    return false; 
  }
}
public function ajaxBigGet(){
	    $p = cookie('wxpage');
		//查询商品需优化
		$UserModel = new UserModel;
		$user_key= $UserModel->isLogin();
		$data='';
		if($user_key !=0){
			$user = Db::name('footprint')->alias('a')->join('goods b','a.goods_id = b.goods_id ')->field("level")->cache(true,360)->order("id desc")->where("uid=".$user_key)->limit(5)->select();
			if($user)
			{
				$id='';
				foreach($user as $value)
				{
					$id .=$value["level"].',';
				}
				
				$twoid=Db::name('cha_level')->alias('a')->cache(true,360)->join('cha_level b','a.pid = b.id')->join('cha_level c','c.pid = b.id')->where(['a.id'=>['in',$id]])->field('c.id')->select();

				$id='';
				foreach($twoid as $val)
				{
					$id.=$val['id'].',';
				}
				$data=Db::name('goods')->alias('a')->cache(true,360)->where(['a.level'=>['in',$id]])->where(['a.is_on_sale'=>1,'a.is_mgoods'=>0,'a.is_tgoods'=>0])->join('cha_level b','a.level = b.id ')->field('a.goods_id,a.guanzhu,a.sname,a.depict,a.goods_sn,a.pic,a.goods_price,b.points')->order('rand()')->limit($p*50)->select();
			}
		}
			  if(empty($data))
			  {
				    $data=Db::name('goods')->alias('a')->cache(true,360)->where(['a.is_on_sale'=>1,'a.is_mgoods'=>0,'a.is_tgoods'=>0])->join('cha_level b','a.level = b.id ')->field('a.goods_id,a.guanzhu,a.sname,a.depict,a.goods_sn,a.pic,a.goods_price,b.points')->order('goods_id DESC')->limit($p*50)->select();
			        array_splice($data,0,10);
			  }else if(count($data)<($p*50)){
					$sum1 = ($p*50)- count($data);
				    $data1=Db::name('goods')->alias('a')->cache(true,360)->where(['a.is_on_sale'=>1,'a.is_mgoods'=>0,'a.is_tgoods'=>0])->join('cha_level b','a.level = b.id ')->field('a.goods_id,a.guanzhu,a.sname,a.depict,a.goods_sn,a.pic,a.goods_price,b.points')->order('goods_id DESC')->limit($sum1)->select();
					array_splice($data1,0,10);
					$data = array_merge($data,$data1);
			  }
			if ($data){
			   foreach ($data as $key => $value){
				$list[$key]['goods_id'] = $value['goods_id'];
				$list[$key]['sname']= $value['sname']; 
				$list[$key]['depict']= $value['depict'];
				$list[$key]['goods_sn']= $value['goods_sn'];
				$list[$key]['pic']= $value['pic'];
				$list[$key]['goods_price']= $value['goods_price'];
				$list[$key]['acount'] = Db::name('order')->where(['status'=>1,'goods_id'=>$value['goods_id']])->count();
					$list[$key]['save']=saveCalculation($value['goods_price'],$value['points'],2);
					$list[$key]['sales']= $value['guanzhu'];//(empty($sales=Db::name('goods_sales')->field('num')->where(['goods_id'=>$value['goods_id']])->find()['num'])?0:$sales);
			   }
				return $list;
			}else{
			  return 0;
			}
   
}
    public function ajaxget(){
    $p = input('p',1);
	if( $p>4){
		return false;
	}
	if($this->isWeixin())
	{
		cookie('wxpage',$p);
	}
	//查询商品需优化
	$UserModel = new UserModel;
    $user_key= $UserModel->isLogin();
	$data='';
	if($user_key !=0){

		$user = Db::name('footprint')->alias('a')->join('goods b','a.goods_id = b.goods_id ')->field("level")->cache(true,360)->order("id desc")->where("uid=".$user_key)->limit(5)->select();
		if($user)
		{
			$id='';
			foreach($user as $value)
			{
				$id .=$value["level"].',';
			}
			
			$twoid=Db::name('cha_level')->alias('a')->cache(true,360)->join('cha_level b','a.pid = b.id')->join('cha_level c','c.pid = b.id')->where(['a.id'=>['in',$id]])->field('c.id')->select();

			$id='';
			foreach($twoid as $val)
			{
				$id.=$val['id'].',';
			}
			$data=Db::name('goods')->alias('a')->where(['a.level'=>['in',$id]])->cache(true,360)->where(['a.is_on_sale'=>1,'a.is_mgoods'=>0,'a.is_tgoods'=>0])->join('cha_level b','a.level = b.id ')->field('a.goods_id,a.guanzhu,a.sname,a.depict,a.goods_sn,a.pic,a.goods_price,b.points')->order('rand()')->page($p,50)->select();
			
		}
	}

  if(empty($data))
  {
	   $data=Db::name('goods')->alias('a')->cache(true,360)->where(['a.is_on_sale'=>1,'a.is_mgoods'=>0,'a.is_tgoods'=>0])->join('cha_level b','a.level = b.id ')->field('a.goods_id,a.sname,a.depict,a.guanzhu,a.goods_sn,a.pic,a.goods_price,b.points')->order('goods_id DESC')->page($p,50)->select();
       array_splice($data,0,10);
  }

    if ($data){
       foreach ($data as $key => $value){
        $list[$key]['goods_id'] = $value['goods_id'];
        $list[$key]['sname']= $value['sname']; 
        $list[$key]['depict']= $value['depict'];
        $list[$key]['goods_sn']= $value['goods_sn'];
        $list[$key]['pic']= $value['pic'];
        $list[$key]['goods_price']= $value['goods_price'];
        $list[$key]['acount'] = Db::name('order')->where(['status'=>1,'goods_id'=>$value['goods_id']])->count();
		    $list[$key]['save']=  saveCalculation($value['goods_price'],$value['points'],2);
		    $list[$key]['sales']=  $value['guanzhu'];//(empty($sales=Db::name('goods_sales')->field('num')->where(['goods_id'=>$value['goods_id']])->find()['num'])?0:$sales);
       }


          $this->assign('data',$list);

       
          return $this->fetch();
    }else{
      return 0;
    }
    }
   // 首页搜索处理
  public function search(){
  $search_txt = input('search_txt');  //输入的搜索条件
  $search_id =input('search_id');//输入搜索类型
  $search_id = ($search_id==0?2:$search_id);
  if(!empty($search_txt)&&!empty($search_id)){
  	$a=array();
    if($search_id==1){
        $keyname=Db::name('keyword')->where("keywords","like","%$search_txt%")->order('hot desc')->limit(rand(12,20))->select();
		if($keyname){
			foreach ($keyname as $key => $value) {
		        $a['href']=url('index/search/index',array("search_txt"=>$value["keywords"],"search_id"=>1));
		        $a['opt']=$value['keywords'];
             $keywords1 = $value['keywords'];
           
		        $a['num']=$this->seachNum($keywords1);//Db::name('goods')->where('is_on_sale',1)->where('sname|keyword','like',"%{$keywords1}%")->count();
		        if($a){
		          $a['flag']=1;
		        }else{
		          return json(['flag'=>0]);
		        }
              $data[] = $a;
		    }
          $a = $data;
		}

    }elseif($search_id==2){
      $sname=Db::name('seller')->where("sname","like","%$search_txt%")->limit(rand(10,13))->select();
	 	if($sname){
		  	foreach ($sname as $key => $value) {
		        $num=Db::name('goods')->where(['seller_id'=>$value['seller_id'],'is_on_sale'=>1])->count();
		        $a[$key]['href']=url('index/store/index',array("seller_id"=>$value['seller_id']));
		        $a[$key]['opt']=$value['sname'];
		        $a[$key]['num']=$num;
		        if($a){
		         $a[$key]['flag']=2;
		        }else{
		         return json(['flag'=>0]);
		        }
		    }
	  	}
      
    }
      
 
		return json($a);
	}
  }
  public function seachNum($search_txt)
  {
        $keyword=array();
       	$shop=array();
        if(empty($search_txt))
        {
          return 0;
        }
        $search_txt = strtr($search_txt,"+"," ");
          $shopval=[];
          $fenci = explode(' ',$search_txt);
          if(count($fenci) == 1)
          {
            $fenci=getTags($search_txt,true,8);
            if(empty($fenci))
            {
              $fenci[] = $search_txt;
            }
          }
      //组织搜索词
       foreach($fenci as $sosuo)
       {
           $keyword[]="%$sosuo%";
       }
      //LEVEL对比
		$levelId=Db::name('cha_level')->where('pname','in',$fenci)->select();
		$topstr ='';
        $toparray = array();
        foreach($levelId as $v)
        {
           $ls = getThree($v['id']);
           $topstr .= $ls;
           $toparray[] = explode(',',$ls);
        }
      
			$data=Db::name('goods')->alias('shop')->where('is_on_sale',1);
                //所有商品排序
                $levelone = Db::name('goods')->alias('shop')->where('is_on_sale',1)->field('level,count("level") as aa')->where('sname|keyword','like',$keyword,'and')->group('level')->order('aa desc')->select();
               $topone =array();	
                if(!empty($toparray))
                   {
                        foreach($toparray as $val)
                        {//二=>一
                           $topone = array_merge($topone, $val);
                        }
                              unset($toparray);
                         foreach($topone as $value)
                          {
                            $onedata=$data->where('level',$value)->where('sname|keyword','like',$keyword,'and')->select();
                            $shop = array_merge($shop, $onedata);
                          }
                              unset($onedata); 

                    }
                 foreach($levelone as $value)
                        {
                          if(!in_array($value["level"],$topone))
                          {
                             $onedata=$data->where('level',$value["level"])->where('sname|keyword','like',$keyword,'and')->select();
                            $shop = array_merge($shop, $onedata);
                            unset($onedata); 
                          }
                        }
                    foreach($shop as $key=>$value)
                    {
                      //去重
                      $search[$value['goods_id']] = $value;
                    }
    return count($search);
  }
  public function countseller(){
  	// logo为空的
  	$seller=Db::name('seller')->where('logo',"")->select();

  	// img为空的
  	$img=Db::name('seller')->where('img',"")->select();
  	    dump($img);
  	// lat为空的
  	$lat=Db::name('seller')->where("lat >= 100")->select();
  	
  }

}