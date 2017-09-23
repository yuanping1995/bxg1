<?php
namespace app\admin\controller;
use think\Db;
use think\Reinputuest;
class Index extends Base
{
    public function index()
    {
//		return $this->fetch('404');
//		echo 1;
//		die;
//         $brand = Db::name('cha_brand')->select();
//	     $this->assign('brand',$brand);
		return $this->fetch();

	}
	public function login(){

		if(empty(cookie('admin_login')))
		{
			if(request()->isPost()){
				
				$username=input('admin');
				$pass=input('password');
				$user=	Db::name('admin_user')->where(['adminpass'=>$pass,'adminuser'=>$username])->find();
				if($user)
				{
					
					cookie('admin_login',$user['adminuser'],time()+3600);
					header("Location:http://m.bxgogo.com/bxgog");
					exit();
				}else{
					
					header("Location:http://127.0.0.1/index.php/admin/index/login");
					exit();
				}
			}else{
				
				return $this->fetch();
			}
		}else{
			
			header("Location:http://127.0.0.1/index.php//bxgog");
			exit();
		}
	
			

		
	}
	public function logintui(){
		cookie('admin_login','');
		cookie('admin_login',null);
		header("Location:http://m.bxgogo.com/admin/index/login");
				exit();
	}
	public function Questionnaire(){
        $spec = Db::name("questionnaire")->select();
//        dump($spec);
        $this->assign("spec",$spec);
		return $this->fetch();
	}
	public function selectQuestionnaire(){
        $spec = Db::name("questionnaire")->select();
//        dump($spec);
        return  $spec;
    }
	public function reg(){
		if(empty(cookie('admin_login')))
		{
			if(request()->isPost()){
				
				$username=input('admin');
				$pass=input('password');
				$name=input('name');
				$data = ['adminpass'=>$pass,'adminuser'=>$username,'name'=>$name];
				$user=	Db::name('admin_user')->insert($data);
				if($user)
				{
					cookie('admin_login',$data['adminuser'],time()+3600);
					header("Location:http://m.bxgogo.com/bxgog");
					exit();
				}else{
					
					header("Location:http://m.bxgogo.com/admin/index/login");
					exit();
				}
			}else{
				
				return $this->fetch();
			}
		}else{
			
			header("Location:http://m.bxgogo.com/bxgog");
			exit();
		}
	

	}
	function tuijiantime(){
		//员工推荐 是否在指定时间内
		$file_path = "tuijian.txt";
		if(file_exists($file_path)){
			$str = file_get_contents($file_path);
			$str = str_replace("\r\n","-时间间隔符-",$str);
			$arr = explode('-时间间隔符-',$str);
			foreach($arr as $value)
			{
				$time = explode('_',$value);
				if(time() > strtotime($time[0]) && time() < strtotime($time[1]))
				{
					return true;
				}
			}
			return false;
		}
	}
  public function setjianzhi(){
  	 $set = intval(input('zhou'));
     $zdb = Db::name('user')->where('uid',$set)->update(['tuijian_q'=>2]);
  }
	public function setjiangli(){
     $set = intval(input('zhou'));
     $zdb = Db::table('tuiguan_user')->where('u_id',$set)->order('time asc')->find();
      if($zdb)
      {
        Db::table('tuiguan_user')->where($zdb)->update(['status'=>1]);
			$fanqian = Db::table('tuiguan_user')->where('tui_id',$set)->find()['u_id'];
			Db::query("UPDATE b_bankrollnd SET is_profit = is_profit+5 WHERE uid =  $fanqian");
         	$txtotal=DB::name('bankrollnd')->where('uid',$fanqian)->sum('total+is_profit+money')+5;
        	Db::name('capital_detailed')->insert(['uid'=>$fanqian,'time'=>time(),'type'=>'推广结算','money'=>5,'typeid'=>1,'total'=>$txtotal]);
      }
      		$data['zhou']=input('zhou');
			$data['uid']=input('jiesuan');
			$data['jiesuan']='已结算';
          	$data['money']=5;
          	$data['time']=time();
			Db::name('staffwages')->insert($data);
    }
	public function tuijianselect(){
      $arr=array();
      $all = Db::table('tuiguan_user')->order('time desc')->select();
  
      foreach($all as $u)
      {
        			$user_tui = Db::name('user')->where('uid',$u['u_id'])->find();
          			$t_q = (empty($wo = Db::name('user')->where(['uid'=>$u['tui_id']])->find())?1:$wo['tuijian_q']);
        			if($t_q<=1)
                    {
                      $t_q = '未生效';
                    }else{
                      $t_q = '已生效';
                    }
       				$u['num'] = '推广兼职:'.$u['tui_id'];//.'->'.$u['tui_id'];
                  	$u['youxiao'] =$u['tui_id'];
					$u['qid'] = $user_tui['tuijian_q'];
					$u['mobile'] = $u['u_id'];
					$u['uname'] = $user_tui['uname'];
					$u['icon'] = $user_tui['icon'];
					$u['jine'] = date("Y-m-d H:i:s",$u['time']);
       			 	$u['jiesuan']=$t_q;
					$u['zhou'] = $u['tui_id'];
        $data[] = $u;
      }
      if(empty($data))
      {
        $data[0]['num'] = 0;
          			$data[0]['youxiao'] =0;
					$data[0]['qid'] = '';
					$data[0]['mobile'] ='';
					$data[0]['uname'] = '暂无';
					$data[0]['icon'] = '';
					$data[0]['jine'] = '';
					$data[0]['jiesuan']='';
					$data[0]['zhou']=0;
      }

	$this->assign('suoyou',$data);
    unset($data);
      
      
      $arr=array();
      $all = Db::table('tuiguan_user')->order('time asc')->select();
      foreach($all as $aval)
      {
        $zdb = Db::table('tuiguan_user')->where('u_id',$aval['tui_id'])->order('time asc')->select();
        $count = count($zdb);
        if($count >= 1)
        {
         	if($zdb['0']['status'] == 0){
              $zdb['0']['shangji'] = $aval['u_id'];
            $arr[]=$zdb['0'];
            }
        }
      }
   
      foreach($arr as $u)
      {
        $user_tui = Db::name('user')->where('uid',$u['shangji'])->find();
       				$data[$u['u_id']]['num'] = $u['u_id'].'开始推广';//.'->'.$u['tui_id'];
                  	//$data[$u['u_id']]['youxiao'] =$u['tui_id'];
					$data[$u['u_id']]['qid'] = $user_tui['tuijian_q'];
					$data[$u['u_id']]['mobile'] = $u['shangji'];
					$data[$u['u_id']]['uname'] = $user_tui['uname'];
					$data[$u['u_id']]['icon'] = $user_tui['icon'];
					$data[$u['u_id']]['jine'] = '5元现金';
					$data[$u['u_id']]['jiesuan'] = (empty($wo = Db::name('staffwages')->where(['uid'=>$u['shangji'],'zhou'=>$u['u_id'].'开始推广奖励'])->find())?'未结算':$wo['jiesuan']);
					$data[$u['u_id']]['zhou'] =$u['u_id'].'开始推广奖励';
      }
      if(empty($data))
      {
        $data[0]['num'] = 0;
          			$data[0]['youxiao'] =0;
					$data[0]['qid'] = '';
					$data[0]['mobile'] ='';
					$data[0]['uname'] = '暂无';
					$data[0]['icon'] = '';
					$data[0]['jine'] = '';
					$data[0]['jiesuan']='';
					$data[0]['zhou']=0;
      }
	$this->assign('jiangli',$data);
    unset($data);
		//今天开始时间
		$zhouon = strtotime(date("Y-m-d"));//strtotime(date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))));
		//今天结束时间
		$zhouoff = strtotime(date("Y-m-d")) +86400;//strtotime(date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))));

		//本周推荐排行
		$benzhou = Db::table('tuiguan_user')->where('time','>',$zhouon)->select();
		$file_path = "tuijian.txt";
		$qid=[];
		if(file_exists($file_path)){
			$str = file_get_contents($file_path);
			$str = str_replace("\r\n","-金额-",$str);
			$arr = explode('-金额-',$str);
			foreach($arr as $value)
			{
				$time = explode('_',$value);
				$qid[$time[0]]=$time[1];
			}
		}
		$data=[];

		foreach($benzhou as $value)
		{
 
			if(isset($data[$value['u_id']]['num']))
			{
				$data[$value['u_id']]['num']++;
              	$tui = Db::name('user')->where(['uid'=> $value['tui_id']])->field('tuijian_q')->find();
			if(!empty($tui))
            {
              if($tui['tuijian_q']>1){
             	 $data[$value['u_id']]['youxiao']++;
              }
            }
              
              if($data[$value['u_id']]['youxiao']>=5){
              	$numm = 5;
              }else{
             	 $numm = 3;
              }
				$data[$value['u_id']]['jine'] = $numm * $data[$value['u_id']]['youxiao'];
			}else{
				if($r_usera = Db::name('user')->field('uid,tuijian_q,uname,icon')->where('uid',$value['u_id'])->where('tuijian_q','>',1)->find())
				{
              
                  $tui = Db::name('user')->where(['uid'=> $value['tui_id']])->field('tuijian_q')->find();
             $tuiq=0;
			if(!empty($tui))
            {
                $tuiq = $tui['tuijian_q']>1?1:0;
            }
					$data[$value['u_id']]['num'] = 1;
                  	$data[$value['u_id']]['youxiao'] =$tuiq;
					$data[$value['u_id']]['qid'] = $r_usera['tuijian_q'];
					$data[$value['u_id']]['mobile'] = $value['u_id'];
					$data[$value['u_id']]['uname'] = $r_usera['uname'];
					$data[$value['u_id']]['icon'] = $r_usera['icon'];
					$data[$value['u_id']]['jine'] = $tuiq==0?0:3;
					$data[$value['u_id']]['jiesuan'] = (empty($wo = Db::name('staffwages')->where(['uid'=>$value['u_id'],'zhou'=>$zhouon.'_'.$zhouoff])->find())?'未结算':$wo['jiesuan']);
					$data[$value['u_id']]['zhou'] = $zhouon.'_'.$zhouoff;
				}else{
					$data[$value['u_id']]=[];
				}
			}
		}
		$data = array_filter($data);
		if(!empty($data))
		{
					$sort = array(  
			'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
			'field'     => 'num',       //排序字段  
			);  
			$arrSort = array();  
			foreach($data AS $uniqid => $row){  
				foreach($row AS $key=>$value){  
					$arrSort[$key][$uniqid] = $value;  
				}  
			} 
			if($sort['direction']){  
				array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);  
			}
			
		}else{
					$data[0]['num'] = 0;
          			$data[0]['youxiao'] =0;
					$data[0]['qid'] = '';
					$data[0]['mobile'] ='';
					$data[0]['uname'] = '暂无';
					$data[0]['icon'] = '';
					$data[0]['jine'] = '';
					$data[0]['jiesuan']='';
					$data[0]['zhou']=$zhouon.'_'.$zhouoff;
		}
		$this->assign('benzhou',$data);
		return $this->fetch('tuijian/index');
	}

	function tuijianselectgetshang(){
		
		
			
		if(input('zhou','shang')=='shang')
		{
			//昨天开始时间
			$zhouon = strtotime(date("Y-m-d")) -86400;//strtotime(date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"))));
			//昨天结束时间
			$zhouoff = strtotime(date("Y-m-d"));//strtotime(date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"))));
			$benzhou =  Db::table('tuiguan_user')->where('time','>',$zhouon)->where('time','<',$zhouoff)->select();
        
		}else{
		//今天开始时间
		$zhouon =strtotime(date("Y-m-d"));//strtotime(date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"))));
		//今天结束时间
		$zhouoff =  strtotime(date("Y-m-d")) + 86400;//strtotime(date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"))));
		
		$benzhou =  Db::table('tuiguan_user')->where('time','>',$zhouon)->select();
		
		}

		$file_path = "tuijian.txt";
		$qid=[];
		if(file_exists($file_path)){
			$str = file_get_contents($file_path);
			$str = str_replace("\r\n","-金额-",$str);
			$arr = explode('-金额-',$str);
			foreach($arr as $value)
			{
				$time = explode('_',$value);
				$qid[$time[0]]=$time[1];
			}
		}
		$data=[];

		foreach($benzhou as $value)
		{
 
			if(isset($data[$value['u_id']]['num']))
			{
				$data[$value['u_id']]['num']++;
              	$tui = Db::name('user')->where(['uid'=> $value['tui_id']])->field('tuijian_q')->find();
			if(!empty($tui))
            {
              if($tui['tuijian_q']>1){
             	 $data[$value['u_id']]['youxiao']++;
              }
            }
              
              if($data[$value['u_id']]['youxiao']>=5){
              	$numm = 5;
              }else{
             	 $numm = 3;
              }
				$data[$value['u_id']]['jine'] = $numm * $data[$value['u_id']]['youxiao'];
			}else{
				if($r_usera = Db::name('user')->field('uid,tuijian_q,uname,icon')->where('uid',$value['u_id'])->where('tuijian_q','>',1)->find())
				{
              
                  $tui = Db::name('user')->where(['uid'=> $value['tui_id']])->field('tuijian_q')->find();
             $tuiq=0;
			if(!empty($tui))
            {
                $tuiq = $tui['tuijian_q']>1?1:0;
            }
					$data[$value['u_id']]['num'] = 1;
                  	$data[$value['u_id']]['youxiao'] =$tuiq;
					$data[$value['u_id']]['qid'] = $r_usera['tuijian_q'];
					$data[$value['u_id']]['mobile'] = $value['u_id'];
					$data[$value['u_id']]['uname'] = $r_usera['uname'];
					$data[$value['u_id']]['icon'] = $r_usera['icon'];
					$data[$value['u_id']]['jine'] = $tuiq==0?0:3;
					$data[$value['u_id']]['jiesuan'] = (empty($wo = Db::name('staffwages')->where(['uid'=>$value['u_id'],'zhou'=>$zhouon.'_'.$zhouoff])->find())?'未结算':$wo['jiesuan']);
					$data[$value['u_id']]['zhou'] = $zhouon.'_'.$zhouoff;
				}else{
					$data[$value['u_id']]=[];
				}
			}
		}
		$data = array_filter($data);
		if(!empty($data))
		{
					$sort = array(  
			'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
			'field'     => 'num',       //排序字段  
			);  
			$arrSort = array();  
			foreach($data AS $uniqid => $row){  
				foreach($row AS $key=>$value){  
					$arrSort[$key][$uniqid] = $value;  
				}  
			} 
			if($sort['direction']){  
				array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);  
			}
			
		}else{
					$data[0]['num'] = 0;
          			$data[0]['youxiao'] =0;
					$data[0]['qid'] = '';
					$data[0]['mobile'] ='';
					$data[0]['uname'] = '暂无';
					$data[0]['icon'] = '';
					$data[0]['jine'] = '';
					$data[0]['jiesuan']='';
					$data[0]['zhou']=$zhouon.'_'.$zhouoff;
		}
		return json($data);
	}
	function setgongzi(){
		if(!Db::name('staffwages')->where('uid',input('jiesuan'))->where('zhou',input('zhou'))->find())
		{
        	$yonghu = input('jiesuan');
          $arr = explode('_',input('zhou'));
          $benzhou =  Db::table('tuiguan_user')->where('u_id',input('jiesuan'))->where('time','>',$arr[0])->where('time','<',$arr[1])->select();
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
          $numm= $num * 3;
          if($num >= 5)
          {
          	$numm= $num * 5;
          }
          // $txtotal=DB::name('bankrollnd')->where('uid',$yonghu)->sum('total+is_profit+money')+$numm;
        	//Db::name('capital_detailed')->insert(['uid'=>$yonghu,'time'=>time(),'type'=>date('Y-m-d',$arr[0]).'推广结算','money'=>$numm,'typeid'=>1,'total'=>$txtotal]);
        	//Db::query("UPDATE b_bankrollnd SET is_profit = is_profit+$numm WHERE uid =  $yonghu");
			$data['zhou']=input('zhou');
			$data['uid']=input('jiesuan');
			$data['jiesuan']='已结算';
          	$data['money']=$numm;
          	$data['time']=time();
			Db::name('staffwages')->insert($data);
		}
	}
	public function settuijian_q(){
		$data['tuijian_q'] = input('q',1);
		Db::name('user')->where('uid',input('uid'))->update($data);
		dump(Db::name('user')->getLastsql());
	}
	public function welcome()
    {
    	$date=date('Y-m-d',time());
		$user_sum=DB::name('user')->count();
		$this->assign('user_sum',$user_sum);
		$seller_sum=DB::name('seller')->count();
		$this->assign('seller_sum',$seller_sum);
		$goods_sum=DB::name('goods')->count();
		$this->assign('goods_sum',$goods_sum);
		$count=DB::name('user')->where('ztime',">",strtotime(date('Y-m-d')))->count();
		// $count_day=DB::name('user')->where('ztime',"=",strtotime($date . ' -1 day'))->count();
		
        // 昨日增长人数
		$count_day=0;
		$a=DB::name('user')->select();
		foreach ($a as $key => $value) {
			$ztime=date('Y-m-d',$value['ztime']);
			if($ztime==date('Y-m-d',strtotime("$date -1 day"))){
                  $count_day++;
			}
		}
		$count_vip=DB::name('user')->where('qid',7)->count();
		$this->assign('count_vip',$count_vip);
		$this->assign('count',$count);
		$this->assign('count_day',$count_day);
		
		//昨日上传商家数
		$seller_count=0;
		$a=DB::name('seller')->select();
		foreach ($a as $key => $value) {
			$dtime=date('Y-m-d',$value['dtime']);
			if($dtime==date('Y-m-d',strtotime("$date -1 day"))){
                  $seller_count++;
			}
		}
		$this->assign('seller_count',$seller_count);

		//昨日上传总数
		$goods_count=0;
		$a=DB::name('goods')->select();
		foreach ($a as $key => $value) {
			$dtime=date('Y-m-d',$value['dtime']);
			if($dtime==date('Y-m-d',strtotime("$date -1 day"))){
                  $goods_count++;
			}
		}
		$this->assign('goods_count',$goods_count);

		// 商家订单排行榜
		$dos=array();
		$order=DB::name('order')->where('seller_id','neq','0')->where('status','8')->whereor('status','9')->whereor('status','2')->group('seller_id')->select();
		foreach ($order as $key => $value) {
			$order[$key]['sname']=DB::name('seller')->where('seller_id',$value['seller_id'])->find()['sname'];
			$order[$key]['dingdan']=DB::query("SELECT COUNT(*) as tongji FROM b_order WHERE seller_id={$value["seller_id"]} AND `status` in (2,8,9)");

			$dos[$key]=DB::name('order')->where('seller_id',$value['seller_id'])->count();
			$do[$key]=DB::name('order')->where('seller_id',$value['seller_id'])->sum("price*num");
			$order[$key]['zonge']=DB::query("SELECT SUM(price*num) as zonghe FROM b_order WHERE seller_id={$value["seller_id"]} AND `status` in (2,8,9)");
		}
		if(input('id')==2){
			array_multisort($do,SORT_DESC,$order); 
		}else{
			array_multisort($dos,SORT_DESC,$order);  
		}
		
		$this->assign('order',$order);
	// 商家订单排行榜
	
	// 员工订单排行榜
    $date=date('Y-m-d',time());
   	$date=strtotime(date('Y-m-d', strtotime($date . ' -7 day')));
	$yorder=DB::query("SELECT  * FROM b_order WHERE uid in (
           select uid from b_user where is_bxg=1
		) AND `status` in (1,2,8,9)
		AND otime>$date AND seller_id != 0 GROUP BY uid"
		);
	foreach ($yorder as $key => $value) {
		$yorder[$key]['dingdan']=DB::query("SELECT COUNT(*) as tongji FROM b_order WHERE uid={$value["uid"]} AND `status` in (1,2,8,9) AND otime>$date");
		$yorder[$key]['uname']=DB::name('user')->where('uid',$value['uid'])->find()['uname'];
		$yorder[$key]['zonghe']=DB::query("SELECT SUM(price*num) as zonghe FROM b_order WHERE uid={$value["uid"]} AND `status` in (1,2,8,9) AND otime >= $date");
		}
        $this->assign('yorder',$yorder);
		return $this->fetch();
	}

	/*
	 * 获取首页楼层方法
	 */
	public function getMenuindex(){
		$where['status']=1;
    	$data = Db::name('admin_menu')->field('id,title as name,pid,ico as iconfont,url')->where($where) -> order('orderby asc')->select();
		$data = $this->returnData(getTree($data,'sub'),"Menu显示");
		return json($data);
	}
	/*
	 * 
	 * 以下内容为临时内容
	 */
	 public function shopisadd(){
		    $goods_id=input('goods_id');
			$goods_images=input('goods_images');
			$good_data=array(
					   'level' =>input('level'),
//					   'Four_level' => input('goods(8)'),
//					   'Five_level' => input('goods(9)'),
					   'brand_id' => input('goods(11)'),
					   'seller_id' => input('seller_id'),  //商家id
					   'is_on_sale' => input('g15',0),
					   'spec_type' => input('goods_type'),
					   'is_recommend' => input('g16',0),
					   'is_hot' => input('is_hot',0),
					   'is_mgoods' => input('is_mgoods',0),
					   'is_tgoods' => input('is_tgoods',0),
					   'is_new' => input('g17',0),
					   'sname' => input('goods(1)'),
					   'bewrite' => input('goods(2)'),
					   'keyword' => input('goods(18)'),
					   'goods_remark' => input('goods(20)'),
					   'field' => "中国",              //暂时设置空
					   'depict' => input('goods(19)'),
					   'dtime' => time(),
					   'pic' => input('goods_img'),
					   'goods_sn' => input('goods(3)'),
					   'goods_price' =>input('goods(12)'),
					   'store_count' =>input('goods(4)'),
					   'guanzhu'=>action('Index/Xiu/shoprandgv','')
			       );
			      
			$goods_id=Db::name('goods')->insertGetId($good_data); 
			$goods_content=Db::name('goods_content')->insert([ 'goods_content' => input('goods(21)'),'goods_id'=>$goods_id]);
			$type_id=input('goods_type');
			if($goods_img_data=input('')['goods']['img']){
				foreach($goods_img_data as $key => $val){
					Db::name('goods_img')->insert(['goods_id' => $goods_id,'plink' => trim($val)]);// 添加图片		   
			}
						}
		
			if(input('')['item']){
			  // 商品规格价钱处理
              Db::name('spec_goods_price')->where('goods_id','=',$goods_id)->delete(); // 删除原有的价格规格对象       
             foreach(input('')['item'] as $k => $v){
                   // 批量添加数据
                   $v['price'] = trim($v['price']);
                   $v['sku'] = trim($v['sku']);
                   $spec_goods=Db::name('spec_goods_price')->insert(['goods_id'=>$goods_id,'keyid'=>$k,'key_name'=>$v['key_name'],'price'=>$v['price'],'sku'=>$v['sku']]);
                   if($spec_goods){
					   $error =0;
				   }       
             } 
		 }
		 if(input('')['guanlian'])
		 {
			 foreach(input('')['guanlian'] as $k => $v){
                   // 批量添加数据        
				   if(!empty($v))
				   {
					    $spec_goods=Db::name('spec_image')->insert(['goods_id'=>$goods_id,'spec_image_id'=>$k,'src'=>trim(ltrim($v,'src'))]);
				   }     
             }
		 }
			if($error == 0)
			{
				if(request()->isAjax()){
				return $this->returnSta('数据插入成功','reload');
				}else{
					echo "商品添加成功";
				}
			}
	 }
	public function addshop(){
	 	if(request()->isAjax()){
            $goods_id=input('goods_id');
			$goods_images=input('goods_images');
			$good_data=array(
					   'level' =>input('level'),
//					   'Four_level' => input('goods(8)'),
//					   'Five_level' => input('goods(9)'),
					   'brand_id' => input('goods(11)'),
					   'seller_id' => input('seller_id'),  //商家id
					   'is_on_sale' => input('g15',0),
					   'spec_type' => input('goods_type'),
					   'is_recommend' => input('g16',0),
					   'is_hot' => input('is_hot',0),
					   'is_mgoods' => input('is_mgoods',0),
					   'is_tgoods' => input('is_tgoods',0),
					   'is_new' => input('g17',0),
					   'sname' => input('goods(1)'),
					   'bewrite' => input('goods(2)'),
					   'keyword' => input('goods(18)'),
					   'goods_remark' => input('goods(20)'),
					   'field' => "中国",              //暂时设置空
					   'depict' => input('goods(19)'),
					   'dtime' => time(),
					   'pic' => input('goods_img'),
					   'goods_sn' => input('goods(3)'),
					   'goods_price' =>input('goods(12)'),
					   'store_count' =>input('goods(4)'),
					   'guanzhu'=>action('Index/Xiu/randgv','')
			       );
			      
			$goods_id=Db::name('goods')->insertGetId($good_data); 
			$goods_content=Db::name('goods_content')->insert([ 'goods_content' => input('goods(21)'),'goods_id'=>$goods_id]);
			$type_id=input('goods_type');
			if($goods_img_data=input('post.')['goods']['img']){
				foreach($goods_img_data as $key => $val){
					Db::name('goods_img')->insert(['goods_id' => $goods_id,'plink' => trim($val)]);// 添加图片		   
			}
						}
		
			if(input('')['item']){
			  // 商品规格价钱处理
              Db::name('spec_goods_price')->where('goods_id','=',$goods_id)->delete(); // 删除原有的价格规格对象       
             foreach(input('')['item'] as $k => $v){
                   // 批量添加数据
                   $v['price'] = trim($v['price']);
                   $v['sku'] = trim($v['sku']);
                   $spec_goods=Db::name('spec_goods_price')->insert(['goods_id'=>$goods_id,'keyid'=>$k,'key_name'=>$v['key_name'],'price'=>$v['price'],'sku'=>$v['sku']]);
                   if($spec_goods){
					   $error =0;
				   }       
             } 
		 }
			if($error == 0)
			{
				return $this->returnSta('数据插入成功','reload');
			}
		     
	 	}else{
	 		$goods_id =input('goods_id',"");
	 
		   $goodsInfo= Db::name('goods')->where('goods_id','=',$goods_id)->find(); 
           $goodsType = Db::name('cha_level')->where('pid',0)->select(); 
		    $goodsImages = Db::name('goods_img')->where('goods_id','=',$goods_id)->select();
			$channel=Db::name('cha_level')->where('pid',0)->select(); 
			$channel_two=Db::name('cha_level')->where('level',2)->select(); 
			$channel_three=Db::name('cha_level')->where('level',3)->select(); 
			$three = Db::name('cha_four')->select(); 
			$four = Db::name('cha_five')->select();
			foreach($three as $k => $vs){
				$list['three'][$k]['siid'] =$vs['Four_id'] ;
				$list['three'][$k]['pname'] =$vs['pname'] ;
			}
			foreach($four as $k => $vs){
				$list['four'][$k]['wid'] =$vs['Five_id'] ;
				$list['four'][$k]['pname'] =$vs['pname'];
			}
			$this->assign('goodslist',$list);
			$this->assign('channel',$channel);
			$this->assign('channel_two',$channel_two);
			$this->assign('channel_three',$channel_three);
			$this->assign('goodsImages',$goodsImages);
			$this->assign('goodsInfo',$goodsInfo);
			$this->assign('goodsType',$goodsType);
           return $this->fetch();	 	
	 	}
	 }
	 //商品规格
	     public function ajaxGetSpecSelect(){	
//      $goods_id =input('goods_id');	// 获取商品规格图片  
//		$items_id =
//		Db::query("select GROUP_CONCAT(`keyid` SEPARATOR '_') AS items_id from b_spec_goods_price where goods_id = $goods_id");
//die;		 
//      $items_ids = explode('_',$items_id[0]['items_id']);   
        $specList = Db::name('spec')->where('type_id','=',input('spec_type'))->order('type_id','DESC')->select();
	      $str = "<table class='table table-bordered' id='goods_spec_table1'>
		  <tr>
		  <td colspan='2'><b>商品规格:</b></td></tr>"; 
	     foreach($specList as $k => $vo) {  
		    $spec_item=Db::name('spec_item')->where('spec_id','=',$vo['id'])->order('id')->field('id,item')->select();  // 获取规格项 
	         $str .="<tr><td>{$vo['name']}:</td><td>";  
	        foreach($spec_item as $k2 => $vo2) { 
	        	foreach($vo2 as $k =>$v){
	        		 $str .="<button type='button' name='guige' data-spec_id='{$vo["id"]}' data-item_id='{$k}' onclick='good_spec(this);' class='btn ";
	            $str.="btn-default'>"; //if(in_array($k2,$items_ids)){$str.="btn-success'>";}else{};
	          $str .="{$v}</button>";
		      $str .="<input type='hidden' name='name' value={$vo['name']}/>&nbsp;&nbsp;&nbsp;";
	        	}
	        }	         
	       }
	         $str .="</td></tr>";  
	        $str .="</table><div id='goods_spec_table2'> <!--ajax 返回 规格对应的库存--> </div>"; 
		exit($str); 
    }
public function getShopspec(){
	$id = input('id');
	$all = Db::name('spec')->where('type_id='.$id)->select();
	$aa=[];
	$typeid= '';

//数组分组
$result =   array();
foreach($all as $k=>$v){
    $result[$v['name']][]    =   $v;
}
//取出分组好的数据
foreach($result as $key=>$value)
{
	$typeid ='';
	foreach($value as $val)
	{
		$typeid .=$val['id'].',';	
	}
$typeid =rtrim($typeid,',');
$where['spec_id']=array('in',$typeid);
$aa[$key]=Db::name('spec_item')->where($where)->select();	
}

	return json($aa);
	
}
 public function ajaxspecname(){
	 	$name=input('name');
	    $id=input('id');
	    $spec = Db::name("spec")->where('id',$id)->update(['name'=>$name]);
	 	if($spec){
	 		return 1;
	 	}else{
	 		return 0;
	 	}
	 }
	  public function ajaxspecmiaoshu(){
	 	$miaoshu=input('miaoshu');
	    $id=input('id');
	    $spec = Db::name("spec_item")->where('id',$id)->update(['item'=>$miaoshu]);
	 	if($spec){
	 		return 1;
	 	}else{
	 		return 0;
	 	}
	 }
public function ajaxbrand(){
	  $ppname=input('ppname');
	  $selectd=input('selectd');
	 	if(Db::name("cha_brand")->where(['ppname'=>$ppname,'One_level'=>$selectd])->find()){
	 		echo  1;
	 	}else{
	 		echo 2;
	 	}
}
public function ajaxspec(){
	$gname=input('gname');
	$typeid=input('selectd');
	 	if(Db::name("spec")->where(['name'=>$gname,'type_id'=>$typeid])->find()){
	 		echo  1;
	 	}else{
	 		echo 2;
	 	}
}
public function ajaxlevel(){
	$level=input('level');
	 	if(Db::name("cha_level")->where('pname',"=",$level)->find()){
	 		echo  1;
	 	}else{
	 		echo 2;
	 	}
}
/*
* 分类栏目联动
*可三级联动
*/
public function liandong() {
			$where['pid'] = input("id");
			$area = Db::name('cha_level')->where($where)->select();
//			$this -> ajaxReturn($area);
            return $area; 

			//三级联动数据
	}
	public function getLiandongbrand(){
	$id = input('id');
	$where['One_level'] = $id;
	$brand = Db::name('cha_brand')->where($where)->select();
	return $brand;
    }
	 public function addgoods(){
	 }
 
	 public function addlevel(){
	 	return $this->fetch();
	 }
	 public function getLevel(){
	 	
	 	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('cha_level')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('cha_level')->order('id desc')->select();
		$data = $this->returnData($this->catesort($dataone),"商家信息显示",1);
		return json($data);
			
//	 	$p = (empty($p = input('get.p'))?1:$p);
//	 	$cou = Db::name('cha_level')->count();
//	 	$page = $this->pages($cou,$p,20);
//	 	$where['status']=1;
//  	$data = Db::name('cha_level')->limit($page['limit'])->select();
//		$data = $this->returnData($this->catesort($data),'品牌信息显示',$page['pagenum']);
//		return json($data);
	 }
	public function delLevel(){
		$id = (!empty(input("post.checkbox")) ? input("post.checkbox") : input("post.id", 0));
		return json($this->delData('cha_level',$id));
	 }
		public function handleLevel(){
		$data = input('');
//      array_shift($data);
		if(!isset($data['id'])&&!isset($data['pid']))
		{
			$data['pid']=0;
		}
		
		if(isset($data['level']))
		{
			if($data['level']==2||$data['level']==1){
				
					unset($data['pic']);
			}			
			else if($data['level']>=3&&empty($data['pic']))
			{
				//请解开我

				 return json($this->returnSta('三级分类必须上传图片',''));
				 
			}

		}else{
			unset($data['pic']);
		}
		
		if(empty($data['pic']))
		{
			unset($data['pic']);
		}

		if(isset($data['points']))
		{
			if($data['points']==0||$data['points']==5||$data['points']==10)
			{
		      if($data['points']==5){
				  $data=array_merge($data , array('draw' => 2));
			  }elseif($data['points']==10){
				  $data=array_merge($data , array('draw' => 4));
			  }
			}else{
				 return json($this->returnSta('扣点只能是0、5或者10',''));
			}
		}
		
		return json($this->dataHandle('cha_level',$data));
	 }
	 public function spec(){
	 	 $type  =  Db::name('cha_level')->where('pid',0)->select();
		 $this->assign('type',$type);
	 	return $this->fetch();
	 }
	public function liandong2() {
			$where['type_id'] = input("id");
			$area = Db::name('spec')->where($where)->select();
//			$this -> ajaxReturn($area);
            return $area; 

			//三级联动数据
	}
	 public function getSpec(){

	 	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('spec')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('spec')->limit($page['limit'])->order('type_id','desc')->field('id,type_id,name')->select();

//分组
		  foreach($dataone as $key => $value){
		  	 $data[$key]['id'] = $value['id'];
		  	 $data[$key]['type_id'] = $value['type_id'];
			 $data[$key]['name'] = $value['name'];
			 $s=[];
			     $datas= Db::name('spec_item')->where('spec_id',$value['id'])->select();
                   foreach ($datas as $keys => $val){ 
			           $s[$keys] = $val["item"];
			        }
			 $data[$key]['miaoshu'] =$datas;
			
			 $data[$key]['type'] = Db::name('cha_level')->where('id',$value['type_id'])->find();
		  }
		$data = $this->returnData($data,"规格显示",$page['pagenum']);
		return json($data);
	 }
	 public function delSpec(){
	 	$id = (!empty(input("post.checkbox")) ? input("post.checkbox") : input("post.id", 0));
		return json($this->delData('spec_item',$id));
	 }
 public function handleSpec(){
	 	$data = input('');
//	 	array_shift($data);
		$tip=1;
         $items= str_replace(array("\r\n","\r","\n"),"_",$data['item']);
		 $textArr= explode("_",$items);   
		$data1['type_id']=$data["type_id"];
		$data1['name']=$data["name"];
	
		$data1['order']=50;
		$data1['search_index']=0;
if (!isset($data['id'])){
	          $spec = Db::name("spec")->insertGetId($data1);
				foreach($textArr as $val)
				{
					$item['spec_id']=$spec;
					$item['item']=trim($val);
					if($tip!=0)
					{
						if(Db::name("spec_item")->insert($item))
						{
							$tip =1;
						}else{
							$tip =0;
						}
					}
				}
				if(!empty($tip)){
				    return $this->returnSta('数据插入成功','reload');  
		       }else{  
		            return $this->returnSta('数据插入失败','');
		            
		        }
			} else {
				
                $id = Db::name("spec_item")->where('id',$data['id'])->find()['spec_id'];
				foreach($textArr as $key=>$val)
				{
					if($key != 0)
					{
						$item['spec_id']=$id;
						$item['item']=trim($val);
						if($tip!=0)
						{
							if(Db::name("spec_item")->insert($item))
							{
								$tip =1;
							}else{
								$tip =0;
							}
						}
					}
					
				}
                $spec = Db::name("spec")->where('id',$id)->update($data1);
				$item['spec_id']=$id;
				$item['item']=$textArr[0];
				if(is_int($spec_item=Db::name("spec_item")->where('id',$data["id"])->update($item))){
					  return $this->returnSta('数据修改成功','reload');
						}else{  
								return $this->returnSta('数据修改失败','');
						}
		}
			
	 }
	 public function zhuijia(){
	 	$data=input('');
	 	$items= str_replace(array("\r\n","\r","\n"),"_",$data['item']);
		$textArr= explode("_",$items);
		$tip=1;
	    foreach($textArr as $val){
	     	$spec= Db::name("spec_item")->insert(['spec_id'=>$data['gid'],'item'=>trim($val)]);
	     	if($tip !=0){
	     		if($spec){
	     			$tip=1;
	     		}else{
	     			$tip=0;
	     		}
	     	}
	     }
	     if(!empty($tip)){
	 		return $this->returnSta('数据插入成功','reload');  
	 	}else{
	 		return $this->returnSta('数据插入失败','');  
	 	}
	 	
	 }

	public function brand(){
		 $type  =  Db::name('cha_level')->where("pid=0")->select();
		 $this->assign('type',$type);
	 	return $this->fetch();
	 }
	 public function delbrand(){
	 	$id = (!empty(input("post.checkbox")) ? input("post.checkbox") : input("post.id", 1));
		if(Db::name('cha_brand')->delete($id)){
             return $this->returnSta('删除成功','reload');
        }else{  
           return $this->returnSta('删除失败','');
        }
	 }
	public function handleBrand(){
	    $data = input('');
//	  	array_shift($data);
		if (!isset($data['brand_id'])){
				if(Db::name("cha_brand")->insert($data)){
				    return $this->returnSta('数据插入成功','reload');  
		        }else{  
		            return $this->returnSta('数据插入失败','');
		        }
			} else {
//				dump($register->update($data));
		if(is_int(Db::name("cha_brand")->update($data))){
		      return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }
			}
	 }
	 
	 Public function catesort($cate, $html = '&nbsp;&nbsp;&nbsp;', $pid = 0, $level = 0) {
		$arr = array();
		foreach ($cate as $v) {
			if ($v['pid'] == $pid) {
				$v['level'] = $level + 1;
				$v['html'] = str_repeat($html, $level);
				$v['html'] .= "";
				$arr[] = $v;

				$arr = array_merge($arr, self::catesort($cate, $html, $v['id'], $level + 1));

			}
		}
		return $arr;
	}
	
	
//商家管理开始
	  public function seller(){
	  	return $this->fetch();
	  }
	  public function getseller(){
	  	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('seller')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('seller')->limit($page['limit'])->order('id desc')->select();
	 	foreach($dataone as $key =>$value)
	 	{
	 		$dataone[$key]['guanzhu']=Db::name('m_follow')->where(["seller_id"=>$value['seller_id']])->count();
	    }
		$data = $this->returnData($dataone,"商家信息显示",$page['pagenum']);
		return json($data);
	  }
	  public function handleseller(){
	  	$data = input('');
//	  	array_shift($data);
		if(isset($data['seller_id']))
	  	if(isset($data['_']))
		unset($data['_']);
		if(isset($data['file']))
		unset($data['file']);
	
		if (!isset($data['id'])) {
			//设置商家的初始密码
			$pass=encryt_data(json_encode(['uid'=>$data['seller_id'],'pass'=>"bxgogo.com"]),config('PrivateKeyFilePath'));
			$data['pass'] = $pass;
			$data['dtime']=time();
			$seller=Db::name("seller")->insert($data);
               $dataaa=[
        'uid' => $data['seller_id'],
        'uname' => '新手',
        'pass' => $data['pass'],
        'paypass' =>  $data['pass'],
        'qid'=> 1,
        'paypass'=>  $data['pass'],
        'code'=>getRandomString(6),
        'mobile' => $data['seller_id'],
        ];
        Db::name('user')->insert($dataaa);
			$bankrollnd=Db::name("bankrollnd")->where(['uid'=>$data['seller_id']])->select();
				if(empty($bankrollnd)){
					$cha_b=Db::name("bankrollnd")->insert(['uid'=>$data['seller_id']]);
				}
				if($seller){
				   	 return $this->returnSta('数据插入成功','reload');  
		        }else{
				   	   return $this->returnSta('数据插入失败','');
				   }
			} else {
//				dump($register->update($data));
		if(is_int(Db::name("seller")->update($data))) {  
			            return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }
		}			
	  }
	  public function delseller(){
	  $id = (!empty(input("post.checkbox")) ? input("post.checkbox") : input("post.id", 1));
		 if(Db::name('seller')->delete($id)){
             return $this->returnSta('删除成功','reload');
        }else{  
           return $this->returnSta('删除失败','');
        }  
	 }
	 
	 
	 	 public function ajaxseller(){
	 	$seller_id=input('seller_id');
	 	if(Db::name("seller")->where('seller_id',"=",$seller_id)->find()){
	 		echo  1;
	 	}else{
	 		echo 2;
	 	}
	 	
	 }
	 public function ajaxseller1(){
	 	$price=input('price');
	 	if(preg_match('/^\d+(\.\d+)?$/',$price)){
	 		echo  1;
	 	}else{
	 		echo 2;
	 	}
	 }
	 public function brandfind(){
	    $keyword=trim(input('keyword'));
		$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('cha_brand')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('cha_brand')->where("ppname","like","%$keyword%")->limit($page['limit'])->select();
		$data = $this->returnData($dataone,"商家搜索结果信息显示",$page['pagenum']);
		return json($data);
	 	
	 }
	//重置密码
	public function repass(){
		$id=input('id');
		$seller_id=input('seller_id');
		//设置初始密码
		$pass=encryt_data(json_encode(['uid'=>$seller_id,'pass'=>"bxgogo.com"]),config('PrivateKeyFilePath'));
		$ypass=Db::name("seller")->where('id',$id)->field('pass')->find();
	   if(Db::name("seller")->where('id',$id)->update(['pass'=>$pass])){
	   	   return $this->returnSta('重置成功','');
	   }elseif($ypass['pass']==$pass){
	      return $this->returnSta('已经为初始密码','');
	   }
	   else{
	   	   return $this->returnSta('重置失败','');
	   }
	}
	public function find(){
		$keyword=trim(input('keyword'));
		
	 	$dataone = Db::name('seller')->where("seller_id","like","%$keyword%")->whereor("sname","like","%$keyword%")->select();
		$data = $this->returnData($dataone,"商家搜索结果信息显示");
		return json($data);
	}
	public function findspec(){
		$keyword=trim(input('keyword'));
	 	$dataone = Db::name('spec')->alias('a')->join('spec_item b','a.id = b.spec_id')->where("item","like","%$keyword%")->select();


	}
	
	
//商家管理结束

//品牌管理开始
 public function getbrand(){
	  	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('cha_brand')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('cha_brand')->limit($page['limit'])->select();

	 	foreach($dataone as $value)
	 	{
	 		$value['level_id']=Db::name('cha_level')->where(["id"=>$value['One_level']])->find()['id'];
	 		$value['One_level']=Db::name('cha_level')->where(["id"=>$value['One_level']])->find()['pname'];
	    	$arr[]=$value;
	 	}
		$data = $this->returnData($arr,"商家信息显示",$page['pagenum']);
		return json($data);
	 }
//品牌管理结束


//网站配置开始



//网站配置结束



	     /**
     * 动态获取商品规格输入框 根据不同的数据返回不同的输入框
     */    
    public function ajaxGetSpecInput(){     
         $goods_id = input('goods_id',0);
         $str = $this->getSpecInput($goods_id ,input('post.')['spec_arr']);
         exit($str);   
    }

	 
	// --------------
	       /**
     * 获取 规格的 笛卡尔积
     * @param $goods_id 商品 id     
     * @param $spec_arr 笛卡尔积
     * @return string 返回表格字符串
     */
    public function getSpecInput($goods_id, $spec_arr)
    {
      if(empty($spec_arr)){  
	       return false;
	  }
        // 排序
        foreach ($spec_arr as $k => $v)
        {
            $spec_arr_sort[$k] = count($v);
        }
        ksort($spec_arr_sort);        
        foreach ($spec_arr_sort as $key =>$val)
        {
            $spec_arr2[$key] = $spec_arr[$key];
        }
         $clo_name = array_keys($spec_arr2);         
         $spec_arr2 = $this->combineDika($spec_arr2); //  获取 规格的 笛卡尔积                 
                       
         $spec =Db::name('spec')->order('type_id asc')->field('id,name')->select();  // 规格表
		 $txt=[];
		 foreach($spec as $value)
		 {
			 $txt[$value['id']] = $value['name'];
		 }
		 $spec = $txt;
         $specItem = Db::name('spec_item')->order('spec_id asc')->field('id,item,spec_id')->select(); ;//规格项
		 $txt=[];
		 
		 foreach($specItem as $value)
		 {
			 $txt[$value['id']] = $value;
		 }
		 $specItem = $txt;

         $keySpecGoodsPrice =  Db::name('spec_goods_price')->where('goods_id','=',$goods_id)->field('keyid,key_name,price,sku')->select(); //规格项
		 $txt=[];
          foreach($keySpecGoodsPrice as $value)
		 {
			 $txt[$value['keyid']] = $value;
		 }
		 $keySpecGoodsPrice = $txt;     
       $str ='<table id="guigerable" class="layui-table" lay-even="" lay-skin="nob">'; //"<table class='table table-bordered' id='spec_input_tab'>";
       $str .="<thead><tr>";       
       // 显示第一行的数据
		
       foreach ($clo_name as $k => $v) 
       {
		   
           $str .=" <td><b>{$spec[$v]}</b></td>";
       }
	  
        $str .="<td><b>价格</b></td>
                    <td><b>库存</b></td>
                    <td><b>操作</b></td>
             </tr></thead>";
       // 显示第二行开始 
	  $str .="<tbody>";
       foreach ($spec_arr2 as $k => $v) 
       {
            $str .="<tr class='shanchuwo'>";
			$item_key ='';
			foreach($v as $k2 => $v2)
			{
				 $item_key .= $v2.'_';
			}
			$item_key = rtrim($item_key,'_');

            $item_key_name = array();
            foreach($v as $k2 => $v2)
            {
			
                $str .="<td>{$specItem[$v2]['item']}</td>";
                $item_key_name[$v2] = $spec[$specItem[$v2]['spec_id']].':'.$specItem[$v2]['item'];
            }   
            ksort($item_key_name);            
            //$item_key = implode('_', array_keys($item_key_name));
            $item_name = implode(' ', $item_key_name);
	isset($keySpecGoodsPrice[$item_key]['price']) ? false : $keySpecGoodsPrice[$item_key]['price'] = input('jiage',0); // 价格默认为0
      isset($keySpecGoodsPrice[$item_key]['sku']) ? false : $keySpecGoodsPrice[$item_key]['sku'] = input('kucun',0); //库存默认为0
    //isset($keySpecGoodsPrice[$item_key]['price']) ? false : $keySpecGoodsPrice[$item_key]['price'] = 0; // 价格默认为0
     // isset($keySpecGoodsPrice[$item_key]['sku']) ? false : $keySpecGoodsPrice[$item_key]['sku'] = 0; //库存默认为0
            $str .="<td><input autocomplete='off' class='layui-input' jq-error='请输入价格' placeholder='请输入价格' required jq-verify='number' name='item[$item_key][price]' value='{$keySpecGoodsPrice[$item_key]['price']}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /></td>";        
            $str .="<td><input autocomplete='off' class='layui-input' jq-error='请输入库存' placeholder='请输入库存' required jq-verify='number' name='item[$item_key][sku]' value='{$keySpecGoodsPrice[$item_key]['sku']}'  onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' />
                <input type='hidden' name='item[$item_key][key_name]' value='$item_name'/></td>";
            $str .="<td class='aaaaaaaa'>删除</td></tr>";                 
       }
	    $str .= "</tbody></table>";
       return $str;   
    }
	/**
 * 多个数组的笛卡尔积
*
* @param unknown_type $data
*/
public function combineDika() {
	$data = func_get_args();
	$data = current($data);
	$cnt = count($data);
	$result = array();
    $arr1 = array_shift($data);
	foreach($arr1 as $key=>$item) 
	{
		$result[] = array($item);
	}		

	foreach($data as $key=>$item) 
	{                                
		$result = $this->combineArray($result,$item);
	}
	return $result;
}
/**
 * 两个数组的笛卡尔积
 * @param unknown_type $arr1
 * @param unknown_type $arr2
*/
public function combineArray($arr1,$arr2) {		 
	$result = array();
	foreach ($arr1 as $item1) 
	{
		foreach ($arr2 as $item2) 
		{
			$temp = $item1;
			$temp[] = $item2;
			$result[] = $temp;
		}
	}
	return $result;
}



//网站配置开始
public function instal(){
	 return $this->fetch();
}

//网站配置结束 



//管理商品开始
public function goods(){
	$goods_id =input('goods_id',"");
	$goodsType = Db::name('goods_type')->select();
	$goodsInfo= Db::name('goods')->where('goods_id','=',$goods_id)->find();
	$this->assign('goodsInfo',$goodsInfo); 
	$this->assign('goodsType',$goodsType); 
	return $this->fetch();
	  
	  
}
public function handlegoods(){
	 	$data = input('');
//	 	array_shift($data);
	 	$goodsid=$data["id"];
	 	array_shift($data);
	 	
	  	$update=Db::name("goods")->where(['goods_id'=>$goodsid])->update($data);
	  	if($update){
	  		 return $this->returnSta('数据修改成功','reload');
	  	}else{
	  		return $this->returnSta('数据修改成功','');
	  	}
}

public function getgoods(){
    	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('goods')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('goods')->limit($page['limit'])->order('goods_id','desc')->select();
	 	  foreach($dataone as $key => $value){
          	$dataone[$key]['level']=Db::name('cha_level')->where('id',$value['level'])->find()['pname'];
        }
       
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}
public function ismangeshop(){

	            $goods_id=input('goods_id');
			$goods_images=input('goods_images');
			$good_data=array(
					   'level' =>input('level'),
					   'brand_id' => input('goods(11)'),
					   'seller_id' => input('seller_id'),  //商家id
					   'is_on_sale' => input('g15',0),
					   'spec_type' => input('goods_type'),
					   'is_recommend' => input('g16',0),
					   'is_hot' => input('is_hot',0),
					   'is_new' => input('g17',0),
					   'sname' => input('goods(1)'),
					   'bewrite' => input('goods(2)'),
					   'keyword' => input('goods(18)'),
					   'goods_remark' => input('goods(20)'),
					   'field' => "中国",              //暂时设置空
					   'depict' => input('goods(19)'),
					   'dtime' => time(),
					   'pic' => input('goods_img'),
					   'goods_sn' => input('goods(3)'),
					   'goods_price' =>input('goods(12)'),
					   'store_count' =>input('goods(4)'),
			       );
			     $goods=Db::name('goods')->where(["goods_id"=>$goods_id])->update($good_data);
				$data=[
				   'key_name'=>input("key_name"),
				   'price'=>input("price"),
				   "sku"=>input('sku'),
				];
			$goods_content=Db::name('goods_content')->where(['goods_id'=>$goods_id])->update([ 'goods_content' => input('goods(21)')]);
			//$goods_img=Db::name('goods_img')->where(['goods_id'=>$goods_id])->update(['plink' => input('goods_img')]);
			Db::name('goods_img')->where(['goods_id'=>$goods_id])->delete();
				if($goods_img_data=input('')['goods']['img']){
					foreach($goods_img_data as $key => $val){
						Db::name('goods_img')->insert(['goods_id' => $goods_id,'plink' => trim($val)]);// 添加图片		   
				}
			}
                  //$spec=Db::name('spec_goods_price')->where(["goods_id"=>$goods_id])->update($data);
				  Db::name('spec_goods_price')->where(['goods_id'=>$goods_id])->delete();
					foreach(input('')['item'] as $k => $v){
					   // 批量添加数据
					   $v['price'] = trim($v['price']);
					   $v['sku'] = trim($v['sku']);
					   $spec_goods=Db::name('spec_goods_price')->insert(['goods_id'=>$goods_id,'keyid'=>$k,'key_name'=>$v['key_name'],'price'=>$v['price'],'sku'=>$v['sku']]);
					   if($spec_goods){
						   $error =0;
					   }       
				 }
			if(input('')['guanlian'])
				 {
					 Db::name('spec_image')->where(['goods_id'=>$goods_id])->delete();
					 foreach(input('')['guanlian'] as $k => $v){
						   // 批量添加数据
						   if(!empty($v))
							{
								$spec_goods=Db::name('spec_image')->insert(['goods_id'=>$goods_id,'spec_image_id'=>$k,'src'=>trim(ltrim($v,'src'))]);       
							}
					 }
				 }
			     if($goods){
					 if(request()->isAjax()){
			     	 return $this->returnSta('数据修改成功','reload');
					 }else{
						 echo '商品修改成功';
					 }
			     }else{
			     	 return $this->returnSta('数据修改失败','');
			     }
			     
	
}
public function mangeshop(){
		 	if(request()->isAjax()){
            $goods_id=input('goods_id');
			$goods_images=input('goods_images');
			$good_data=array(
					   'level' =>input('level'),
					   'brand_id' => input('goods(11)'),
					   'seller_id' => input('seller_id'),  //商家id
					   'is_on_sale' => input('g15',0),
					   'spec_type' => input('goods_type'),
					   'is_recommend' => input('g16',0),
					   'is_hot' => input('is_hot',0),
					   'is_new' => input('g17',0),
					   'sname' => input('goods(1)'),
					   'bewrite' => input('goods(2)'),
					   'keyword' => input('goods(18)'),
					   'goods_remark' => input('goods(20)'),
					   'field' => "中国",              //暂时设置空
					   'depict' => input('goods(19)'),
					   'dtime' => time(),
					   'pic' => input('goods_img'),
					   'goods_sn' => input('goods(3)'),
					   'goods_price' =>input('goods(12)'),
					   'store_count' =>input('goods(4)'),
					   'is_tgoods'=>input('is_tgoods',0),
					   'is_mgoods'=>input('is_mgoods',0),
			       );
			     $goods=Db::name('goods')->where(["goods_id"=>$goods_id])->update($good_data);
				$data=[
				   'key_name'=>input("key_name"),
				   'price'=>input("price"),
				   "sku"=>input('sku'),
				];
			$goods_content=Db::name('goods_content')->where(['goods_id'=>$goods_id])->update([ 'goods_content' => input('goods(21)')]);
			//$goods_img=Db::name('goods_img')->where(['goods_id'=>$goods_id])->update(['plink' => input('goods_img')]);
			Db::name('goods_img')->where(['goods_id'=>$goods_id])->delete();
				if($goods_img_data=input('post.')['goods']['img']){
					foreach($goods_img_data as $key => $val){
						Db::name('goods_img')->insert(['goods_id' => $goods_id,'plink' => $val]);// 添加图片		   
				}
			}
                  //$spec=Db::name('spec_goods_price')->where(["goods_id"=>$goods_id])->update($data);
				  Db::name('spec_goods_price')->where(['goods_id'=>$goods_id])->delete();
					foreach(input('')['item'] as $k => $v){
					   // 批量添加数据
					   $v['price'] = trim($v['price']);
					   $v['sku'] = trim($v['sku']);
					   $spec_goods=Db::name('spec_goods_price')->insert(['goods_id'=>$goods_id,'keyid'=>$k,'key_name'=>$v['key_name'],'price'=>$v['price'],'sku'=>$v['sku']]);
					   if($spec_goods){
						   $error =0;
					   }       
				 } 
			     if($goods){
			     	 return $this->returnSta('数据修改成功','reload');
			     }else{
			     	 return $this->returnSta('数据修改失败','');
			     }
			     
		     
	 	}else{
	 		$goods_id =input('id',"");
		    $goodsInfo= Db::name('goods')->where('goods_id','=',$goods_id)->find();
            $goodsType = Db::name('cha_level')->where('pid=0')->select();
		    $goodsImages = Db::name('goods_img')->where('goods_id','=',$goods_id)->select();

			
			//显示分类
			
			$channel_three=Db::name('cha_level')->where(["level"=>3])->select();
			$channel_two=Db::name('cha_level')->where(['level'=>2])->select();
			$channel=Db::name('cha_level')->where('pid',0)->select();
			
			
			
			//只获取三级分类的信息
			$three=Db::name('cha_level')->where(["id"=>$goodsInfo['level']])->find();
			
			$two=Db::name('cha_level')->where(['id'=>$three['pid']])->find();
			$one=Db::name('cha_level')->where(['id'=>$two['pid']])->find();

			
			//读取规格关联信息
			
			$keys =  Db::name('spec_goods_price')->where('goods_id',$goods_id)->column("GROUP_CONCAT(`keyid` SEPARATOR '_') ");
			 $item = Db::name('spec_item')->where(['id'=>['in',implode(explode("_",$keys[0]),',')]])->select();
//          获取品牌信息
             $brand=Db::name('cha_brand')->where(['brand_id'=>$goodsInfo['brand_id']])->find();
		$sepc_img =Db::name('spec_image')->where(["goods_id"=>$goods_id])->select();
		
		    $goods_content=Db::name('goods_content')->where(["goods_id"=>$goods_id])->find();
		    $spec=Db::name('spec_goods_price')->where(["goods_id"=>$goods_id])->select();
			$this->assign('item',$item);
		    $this->assign('spec',$spec);
			$this->assign('sepc_img',$sepc_img);
		    $this->assign('brand',$brand);
		    $this->assign('goods_content',$goods_content);
			$this->assign('three',$three);
			$this->assign('channel',$channel);
			$this->assign('channel_two',$channel_two);
			$this->assign('channel_three',$channel_three);
			$this->assign('goodsImages',$goodsImages);
			$this->assign('goodsInfo',$goodsInfo);
			$this->assign('goodsType',$goodsType);
           return $this->fetch();
	 	}
}

public function findgoods(){
	$keyword=trim(input('keyword'));
		$is_on_sale=input('is_on_sale');
  if (isset($is_on_sale)) {
  	$dataone = Db::name('goods')->where('goods_id|sname|seller_id','like',"%{$keyword}%")->where('is_on_sale','0')->select();
  }else{
  	$dataone = Db::name('goods')->where('goods_id|sname|seller_id','like',"%{$keyword}%")->select();
  }
	//$dataone = Db::name('goods')->where(["goods_id"=>$keyword])->where('goods_id|sname|seller_id','like',"%{$keyword}%")->whereor('seller_id',$keyword)->select();
		$data = $this->returnData($dataone,"商家搜索结果信息显示");
		return json($data);
}
public function findlevel(){
	  $keyword=trim(input('keyword'));
	 	$dataone = Db::name('cha_level')->where("pname","like","%$keyword%")->select();
		$data = $this->returnData($dataone,"商家搜索结果信息显示");
		return json($data);
}



//菜单管理开始
  public function menu(){
  	 return $this->fetch();
  }
  public function getmenu(){
    	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('blocknav')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('blocknav')->limit($page['limit'])->order('id','desc')->select();
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}
	  public function handlemenu(){
	  	$data = [
	  	   'title'=>input('title'),
	  	   'url' => input('dizhi'),
	  	   'belongto'=>input('belongto'),
	  	   'ico'=>input('ico')
	  	];
	  	$id=input('id');
		if (!$id){
			$seller=Db::name("blocknav")->insert($data);
				if($seller){
				   	 return $this->returnSta('数据插入成功','reload');  
		        }else{
				   	   return $this->returnSta('数据插入失败','');
				   }
			}elseif(input('p')){
				if(is_int(Db::name("blocknav")->where(['id'=>$id])->update(['status'=>input('status')]))){
					 return $this->returnSta('数据修改成功','reload');
				}else{
					 return $this->returnSta('数据修改失败','');
				} 
			}
			else{
		if(is_int(Db::name("blocknav")->where(['id'=>$id])->update($data))){  
			            return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }
			}
			
	  }
	  
public function findmenu(){
	    $keyword=trim(input('keyword'));
	 	$dataone = Db::name('blocknav')->where("title","like","%$keyword%")->select();
		$data = $this->returnData($dataone,"菜单搜索结果信息显示");
		return json($data);
}

//菜单管理结束 



//用户管理开始
public function user(){
	return $this->fetch();
}
 public function getuser(){
    	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('user')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('user')->limit($page['limit'])->order('logtime desc')->select();
        foreach($dataone as $key => $value){
          		 	if($value["qid"]==1){
          		 		$dataone[$key]['qid']="普通";
          		 	}else{
          		 		$dataone[$key]['qid']="VIP";
          		 	}
          	$dataone[$key]['ordercount']=Db::name('order')->where('uid',$value['uid'])->count('uid');
          	$dataone[$key]['ordertotal']=Db::name('order')->where('uid',$value['uid'])->sum('pricetotal');
          	$dataone[$key]['usertotal']=Db::name('bankrollnd')->where('uid',$value['uid'])->find()['total'];
          	$dataone[$key]['logintime']=date("Y-m-d H:m:s", $value['logtime']);
        }
        
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}
	  public function handleuser(){
	     $data=input('');
//	     array_shift($data);
	     unset($data['file']);
	     if(strtoupper($data['qid'])=="VIP"){
	     	$data['qid']=7;
	     }else{
	     	$data['qid']=1;
	     }
		unset($data['id']);			
		if(is_int(Db::name("user")->where(['uid'=>$data['uid']])->update($data))){  
			            return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }	
	  }
	  public function handleuserchong(){
	     $data=input('');
//	     array_shift($data);
//	     profit
//	    UPDATE b_bankrollnd SET profit = profit+$data['qian'] WHERE uid =  $data['uid']、
    // dump("UPDATE b_bankrollnd SET profit = profit+$data[qian] WHERE uid =  $data[uid]");
    // die;
    Db::query("UPDATE b_bankrollnd SET is_profit = is_profit+$data[qian] WHERE uid =  $data[uid]");
       if(Db::name('capital_detailed')->insert(['uid'=>$data['uid'],'time'=>time(),'type'=>'账户充值','money'=>$data['qian'],'typeid'=>1])){
       		return $this->returnSta('充值成功','reload');
       }else{
       	  return $this->returnSta('充值失败','');
       }
	  }

	public function finduser(){
		$keyword=trim(input('keyword'));
	 	$dataone = Db::name('user')->where("uid",$keyword)->select();
	 	foreach($dataone as $key => $value){
          		 	if($value["qid"]==1){
          		 		$dataone[$key]['qid']="普通";
          		 	}else{
          		 		$dataone[$key]['qid']="VIP";
          		 	}
            $dataone[$key]['ordercount']=Db::name('order')->where('uid',$value['uid'])->count('uid');
          	$dataone[$key]['ordertotal']=Db::name('order')->where('uid',$value['uid'])->sum('pricetotal');
          	$dataone[$key]['usertotal']=Db::name('bankrollnd')->where('uid',$value['uid'])->find()['total'];
          	$dataone[$key]['logintime']=date("Y-m-d H:m:s", $value['logtime']);
        }
		$data = $this->returnData($dataone,"商家搜索结果信息显示");
		return json($data);
	}





 public function ajaxuser(){
	 	$uid=input('uid');
	 	if(Db::name("user")->where('uid',"=",$uid)->find()){
	 		echo  1;
	 	}else{
	 		echo 2;
	 	}
	 	
	 }
	 
//public function repassuser(){
//		$id=input('id');
//		$seller_id=input('seller_id');
//		//设置初始密码
//		$pass=encryt_data(json_encode(['uid'=>$seller_id,'pass'=>"bxgogo.com"]),config('PrivateKeyFilePath'));
//		$ypass=Db::name("seller")->where('id',$id)->field('pass')->find();
//	   if(Db::name("seller")->where('id',$id)->update(['pass'=>$pass])){
//	   	   return $this->returnSta('重置成功','');
//	   }elseif($ypass['pass']==$pass){
//	      return $this->returnSta('已经为初始密码','');
//	   }
//	   else{
//	   	   return $this->returnSta('重置失败','');
//	   }
//	}

//用户管理结束


//首页轮播开始
public function slide(){
	return $this->fetch();
}
 public function getslide(){
    	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('slide')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('slide')->limit($page['limit'])->order('id','desc')->select();
        foreach($dataone as $key => $value){
          		 $dataone[$key]['bid']=Db::name('slide_tile')->where('id',$value['bid'])->find()['bname'];
        }        
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}

	  public function handleslide(){
	  	$id=input('id');
	  	$data1=[
	  	    'bname'=>input('bid'),
	  	];
		if (!isset($id)){
			$bid=Db::name("slide_tile")->insertGetId($data1);
				$data=[
				  "bid" => $bid,
				  "hname" =>input("hname"),
				  "bimg" =>input("bimg"), 
				  "blink" =>input('blink'),
				  "imgb" =>input('imgb'),
				];
			    $slide=Db::name("slide")->insert($data);
				if($slide){
				   	 return $this->returnSta('数据插入成功','reload');  
		        }else{
				   	   return $this->returnSta('数据插入失败','');
				   }
			}elseif(input('p')){
				$data=[
				  'ismobile'=>input('ismobile'),
				];
				 $bid_s=Db::name("slide")->where(['id'=>$id])->update($data);
				 if($bid_s){
				 	 return $this->returnSta('数据修改成功','reload');
				 }else{
				 	 return $this->returnSta('数据修改失败','');
				 }
			}
			 else {
			$data1=[
				  	    'bname'=>input('bid'),
				  	];
	       $bid_s=Db::name('slide')->where('id',$id)->find()['bid'];
		   Db::name("slide_tile")->where(['id'=>$bid_s])->update($data1);
		   $data=[
				  "bid" => $bid_s,
				  "hname" =>input("hname"),
				  "bimg" =>input("bimg"), 
				  "blink" =>input('blink'),
				  "imgb" =>input('imgb'),
				];
		if(is_int(Db::name("slide")->where(['id'=>$id])->update($data))) {  
			            return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }
			}
			
	  }
 public function ajaxslide(){
	 	$titlename=input('bid');
	 	if(Db::name("slide_tile")->where('bname',"=",$titlename)->find()){
	 		echo  1;
	 	}else{
	 		echo 2;
	 	}
	 	
	 }
    public function delslide(){
	  $id = (!empty(input("post.checkbox")) ? input("post.checkbox") : input("post.id", 1));
		 if(Db::name('slide')->delete($id)){
             return $this->returnSta('删除成功','reload');
        }else{  
           return $this->returnSta('删除失败','');
        }  
	 }
//首页轮播结束


//订单管理开始
public function order(){
	return $this->fetch();
}
 public function getorder(){
    	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('order')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('order')->limit($page['limit'])->order('id','desc')->where('goods_id','neq','null')->select();
	 	
			
	 	$yuangong=array('18331153267','15933530193','15511219342','17736551196','17331233476',' 13653325678','15933439854','15175359503','15831265373','15128169781','13290681396','15132275943','18903127100','15081265532','17331221420','15075719883');
        foreach($dataone as $key => $value){
              $dataone[$key]['seller_name']= Db::name('seller')->where('seller_id',$value['seller_id'])->find()['sname'];
              if(in_array($value["uid"],$yuangong)){
              	$dataone[$key]['surname']=$value['surname']."(内部)";
              }
          		 if($value['status']==1){
          		 	$dataone[$key]['status']="待发货";
          		 }elseif($value['status']==2){
          		 	$dataone[$key]['status']="待收货";
          		 }elseif($value['status']==3){
          		 	$dataone[$key]['status']="待付款";
          		 }elseif($value['status']==4){
          		 	$dataone[$key]['status']="已取消";
          		 }elseif($value['status']==5){
          		 	$dataone[$key]['status']="已失效";
          		 }elseif($value['status']==6){
          		 	$dataone[$key]['status']="退货中";
          		 }elseif($value['status']==7){
          		 	$dataone[$key]['status']="已退货";
          		 }elseif($value['status']==8){
          		 	$dataone[$key]['status']="待评论";
          		 }elseif($value['status']==9){
          		 	$dataone[$key]['status']="已评论";
          		 }

			$dataone[$key]['otime']=date("Y-m-d H:m:s", $value['otime']);
               }
            $data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		   return json($data);
}


		public function findorder(){
		$keyword=trim(input('keyword'));
	 	$dataone = Db::name('order')->where("oid",$keyword)->whereOr("uid",$keyword)->whereOr("seller_id",$keyword)->select();
	 	 foreach($dataone as $key => $value){
          		 $dataone[$key]['seller_name']= Db::name('seller')->where('seller_id',$value['seller_id'])->find()['sname'];
          		 if($value['status']==1){
          		 	$dataone[$key]['status']="待发货";
          		 }elseif($value['status']==2){
          		 	$dataone[$key]['status']="待收货";
          		 }elseif($value['status']==3){
          		 	$dataone[$key]['status']="待付款";
          		 }elseif($value['status']==4){
          		 	$dataone[$key]['status']="已取消";
          		 }elseif($value['status']==5){
          		 	$dataone[$key]['status']="已失效";
          		 }elseif($value['status']==6){
          		 	$dataone[$key]['status']="退货中";
          		 }elseif($value['status']==7){
          		 	$dataone[$key]['status']="已退货";
          		 }elseif($value['status']==8){
          		 	$dataone[$key]['status']="待评论";
          		 }elseif($value['status']==9){
          		 	$dataone[$key]['status']="已评论";
          		 }

			$dataone[$key]['otime']=date("Y-m-d H:m:s", $value['otime']);
        }   
		$data = $this->returnData($dataone,"商家搜索结果信息显示");
		return json($data);
	}

//订单管理结束


//提现开始
public function tixian(){
	return $this->fetch();
}
public function gettixian(){
	    $p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('usercredit')->count();
	 	$page = $this->pages($cou,$p,20);
	 	
	 	$dataone = Db::name('usercredit')->limit($page['limit'])->order('status asc,otime desc')->select(); 
	 	foreach($dataone as $key=>$val){
	 		$seller=Db::name('seller')->where('seller_id',$val['uid'])->find();
	 		if($seller){
	 			$dataone[$key]['ytype']="<font color='red'>商家</font>";
				$dataone[$key]['yname']=$seller['sname'];
	 			$dataone[$key]['yingde']=$val['price']-$val['price']*0.01;
	 		}else{
				$user=Db::name('user')->where('uid',$val['uid'])->find();
	 			$dataone[$key]['ytype']="会员";
				$dataone[$key]['yname']=$user['uname'];
	 			$dataone[$key]['yingde']=$val['price']-$val['price']*0.05;
	 		}
	 	
	 		$dataone[$key]["fid"]=Db::name('fund_mode')->where('id',$val['fid'])->find()['crname'];
	 		$dataone[$key]['crid']=Db::name('fund_mode')->where('id',$val['fid'])->find()['crid'];
			$dataone[$key]['realname']=Db::name('fund_mode')->where('id',$val['fid'])->find()['cname'];
	 	    
	 		$dataone[$key]["otime"]=date("Y-m-d",$val['otime']);
	 		$dataone[$key]["status"]=$val['status'];
	 	} 
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}
public function ajaxstatus(){
	$status=Db::name('usercredit')->where("id","=",input('id'))->update(['status'=>1]);
	$pid=input('pid');
	if(isset($pid)){
		$status=Db::name('usercredit')->where("id","=",input('id'))->update(['status'=>0]);	
	}
	if($status){
		echo "已处理";
	}else{
		echo "未审核";
	}
}
public function ajaxbxstatus(){
	$status=Db::name('user')->where("uid","=",input('id'))->update(['is_bxg'=>1]);
	$pid=input('pid');
	if(isset($pid)){
	  $status=Db::name('user')->where("uid","=",input('id'))->update(['is_bxg'=>0]);	
	}
	
}

public function findtixian(){
	$keyword=trim(input('keyword'));

	 	$dataone = Db::name('usercredit')->where("uid","=","$keyword")->select();
	 	foreach($dataone as $key=>$val){
	 		$seller=Db::name('seller')->where('seller_id',$val['uid'])->find();
	 		if($seller){
	 			$dataone[$key]['ytype']="<font color='red'>商家</font>";
				$dataone[$key]['yname']=$seller['sname'];
	 			$dataone[$key]['yingde']=$val['price']-$val['price']*0.01;
	 		}else{
				$user=Db::name('user')->where('uid',$val['uid'])->find();
	 			$dataone[$key]['ytype']="会员";
				$dataone[$key]['yname']=$user['uname'];
	 			$dataone[$key]['yingde']=$val['price']-$val['price']*0.05;
	 		}
	 	
	 		$dataone[$key]["fid"]=Db::name('fund_mode')->where('id',$val['fid'])->find()['crname'];
	 		$dataone[$key]['crid']=Db::name('fund_mode')->where('id',$val['fid'])->find()['crid'];
			$dataone[$key]['realname']=Db::name('fund_mode')->where('id',$val['fid'])->find()['cname'];
	 	    
	 		$dataone[$key]["otime"]=date("Y-m-d",$val['otime']);
	 		$dataone[$key]["status"]=$val['status'];
	 	} 
		$data = $this->returnData($dataone,"菜单搜索结果信息显示");
		return json($data);
}
//提现结束


//红包时间管理开始
public function redtime(){
	$selct=Db::name("redconfig")->find();
	$this->assign('red',$selct);
	return $this->fetch();
}
public function getredtime(){
	 $p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('red')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('red')->limit($page['limit'])->order('id','desc')->select();
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}
public function handleredtime(){
		    $data = input('');
	  	    // array_shift($data);
		if (!isset($data['id'])){
				if(Db::name("red")->insert($data)){
				    return $this->returnSta('数据插入成功','reload');  
		        }else{  
		            return $this->returnSta('数据插入失败','');
		        }
			} else {
		if(is_int(Db::name("red")->update($data))){
		      return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }
			}
}
public function findredtime(){
	    $keyword=trim(input('keyword'));
	 	$dataone = Db::name('red')->where("start","=","$keyword")->whereOr('end','=',"$keyword")->select();
		$data = $this->returnData($dataone,"菜单搜索结果信息显示");
		return json($data);
}

public function handleredconfig(){
		 $data = input('');
//	 	array_shift($data);

					$item['status']=$data['status'];
					$item['rule']=$data['rule'];	
					$insert=Db::name("redconfig")->where("id",$data['id'])->update($item);
//					dump(Db::name("redconfig")->getLastSql());
			if($insert){
				    return $this->returnSta('数据修改成功','reload');  
		       }else{  
		            return $this->returnSta('数据修改失敗','');
		            
		       }

}

//红包配置管理结束
public function redmoeny(){
	return $this->fetch();
}
public function getredmoeny(){
	    $p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('redcapital')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('redcapital')->limit($page['limit'])->order('id','desc')->select();
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}
public function handleredmoeny(){
		 $data = input('');
//	  	array_shift($data);
		if (!isset($data['id'])){
				if(Db::name("redcapital")->insert($data)){
				    return $this->returnSta('数据插入成功','reload');  
		        }else{  
		            return $this->returnSta('数据插入失败','');
		        }
			} else {
		if(is_int(Db::name("redcapital")->update($data))){
		      return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }
			}
}
public function findredmoeny(){
	   $keyword=trim(input('keyword'));
	 	$dataone = Db::name('redcapital')->where("seller_id","=","$keyword")->select();
		$data = $this->returnData($dataone,"菜单搜索结果信息显示");
		return json($data);
}
//红包日志开始
public function redlog(){   
	return $this->fetch();
}
public function getredlog(){
	$in = 0;
	    $p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('red_log')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('red_log')->limit($page['limit'])->order('id','desc')->select();
	 $redstatus = Db::name('red')->where('status=1')->where("end>='".date('H:i')."'")->order('start asc')->find();
	 	         $is_date = strtotime(date("Y-m-d H:i:s"));
				$is_start_dtae = strtotime(date("Y-m-d").' '.$redstatus['start'].':00');
				$is_end_dtae = strtotime(date("Y-m-d").' '.$redstatus['end'].':00');
				if($is_date>=$is_start_dtae && $is_date<=$is_end_dtae){
					//在活动时间内
			       $dataone['in']=1;
				}
		$data = $this->returnData($dataone,"红包日志显示",$page['pagenum']);
		return json($data);
}
public function findredlog(){
	    $keyword=trim(input('keyword'));
	 	$dataone = Db::name('red_log')->where("start","=","$keyword")->whereOr("end","=","$keyword")->select();
		$data = $this->returnData($dataone,"菜单搜索结果信息显示");
		return json($data);
}
public function ajaxredlog(){
  $id=Db::name('red_log')->order('id desc')->limit(0,1)->find()["id"];
  $redlog=Db::name("red_log")->where('id',$id)->update(['money'=>input('moeny'),'coupon'=>input('coupon')]);
  if($redlog){
  	echo 1;
  }else{
  	echo 0;
  }
}

//快报管理开始
public function bulle(){
	return $this->fetch();
}
public function getbulle(){
	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('bulle')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('bulle')->limit($page['limit'])->order('id','desc')->select(); 
	 	foreach($dataone as $key=>$val){
	 	
	 		$dataone[$key]["btype"]=Db::name('bulle_type')->where('id',$val['btype'])->find()['tname'];
	 	} 
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}

public function handlebulle(){
			 $data = input('');
//	  	array_shift($data);
		if (!isset($data['id'])){
			     $bulle_type=[
			       'tname'=>input('btype'),
			     ];
			    $btype=Db::name("bulle_type")->insertGetId($bulle_type);
			    $bulle=[
			       'bname'=>input('bname'),
			       'btype'=>$btype,
			       'btime'=>time(),
			    ];
				if(Db::name("bulle")->insert($bulle)){
				    return $this->returnSta('数据插入成功','reload');  
		        }else{  
		            return $this->returnSta('数据插入失败','');
		        }
			} else {
				$tid=Db::name("bulle")->where('id',input('id'))->find()['btype'];
				$bulle_type=[
			       'tname'=>input('btype'),
			     ];    
			    $btype=Db::name("bulle_type")->where('id',$tid)->update($bulle_type);
				$bulle=[
			       'bname'=>input('bname'),
			       'btype'=>$tid,
			       'btime'=>time(),
			    ];

		if(is_int(Db::name("bulle")->where('id',input('id'))->update($bulle))){
		      return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }
		}

}
 public function ajaxbulle(){
	 	$titlename=input('bid');
	 	if(Db::name("bulle")->where('bname',"=",$titlename)->find()){
	 		echo  1;
	 	}else{
	 		echo 2;
	 	}
	 	
	 }
	  public function ajaxbulle_type(){
	 	$titlename=input('bid');
	 	if(Db::name("bulle_type")->where('tname',"=",$titlename)->find()){
	 		echo  1;
	 	}else{
	 		echo 2;
	 	}
	 	
	 }
	 
//快报管理结束


//商品类型管理开始
public function goodstype(){
	return $this->fetch();
}
public function getgoodstype(){
	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('goods_type')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('goods_type')->limit($page['limit'])->order('id','desc')->select(); 
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}
public function handlegoodstype(){
			 $data = input('');
//	      	array_shift($data);
		if (!isset($data['id'])){
				if(Db::name("goods_type")->insert($data)){
				    return $this->returnSta('数据插入成功','reload');  
		        }else{  
		            return $this->returnSta('数据插入失败','');
		        }
			} else {
		if(is_int(Db::name("goods_type")->update($data))){
		      return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }
			}
}
public function findgoodstype(){
	    $keyword=trim(input('keyword'));
	 	$dataone = Db::name('goods_type')->where("lname","like","%$keyword%")->select();
		$data = $this->returnData($dataone,"商家搜索结果信息显示");
		return json($data);
}
public function ajaxgoodstype(){
	$titlename=input('bid');
	 	if(Db::name("goods_type")->where('lname',"=",$titlename)->find()){
	 		echo  1;
	 	}else{
	 		echo 2;
	 	}
}

//商品类型管理结束


//广告管理开始
public function floorcool(){
	$data=Db::name('floorconfig')->select();
	$this->assign('data',$data);
	return $this->fetch();
}
public function getfloorcool(){
	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('floorcool')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('floorcool')->limit($page['limit'])->order('id','desc')->select(); 
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}
public function handlefloor(){
	$data=input('');
//   array_shift($data);
     unset($data['file']);
	if (!isset($data['id'])){
				if(Db::name("floorcool")->insert($data)){
				    return $this->returnSta('数据插入成功','reload');  
		        }else{  
		            return $this->returnSta('数据插入失败','');
		        }
			} else {
		if(is_int(Db::name("floorcool")->update($data))){
		      return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }
			}
}
public function findfloor(){
	   $keyword=trim(input('keyword'));
	 	$dataone = Db::name('floorcool')->where("bname","like","%$keyword%")->select();
		$data = $this->returnData($dataone,"商家搜索结果信息显示");
		return json($data);
}

//广告管理结束


//缓存开始
public function huancun(){
$rtim=del_dir(RUNTIME_PATH);
if($rtim){
   echo 1;
}else{
   echo 0;
}
}
//缓存结束
//快速查询商家
public function getsellername(){
	$name = input('')['sellername'];
	$data =Db::name('seller')->field('seller_id,sname')->where("sname","like","%$name%")->select();
	$sname ='';
	if($data){
		 
		 foreach($data as $value)
		 {
			$sname .= $value['sname'].'=>'.$value['seller_id'].',';
		 }
		
	 }
	 if(empty($name))
	 {
		 $sname ='';
	 }
	return json(['str'=>rtrim($sname,',')]);
}
//刷新顶级菜单
public function getlevelshuaxin(){
	$arr = Db::name('cha_level')->where("pid",0)->select();
	return json($arr);
}
//刷新品牌
public function getnewpinpai(){
	$id = input('')['onelevel'];
	$arr = Db::name('cha_brand')->where("One_level",$id)->select();
	return json($arr);
}
//合同编号验证
public function ajaxbianhao(){
	$bh = input('')['bianhaoid'];
	if(Db::name('seller')->where("contract",$bh)->select())
	{
		echo 2;
	}else{
		echo 1;
	}
	
}


//返现管理开始
public function fanxian(){
	return $this->fetch();
}
public function getfanxian(){
	
	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('bankrollnd')->count();
	 	$page = $this->pages($cou,$p,20);
	 	
	 	$dataone = Db::name('bankrollnd')->limit($page['limit'])->order('fundtime','desc')->select(); 
	 	foreach($dataone as $key=>$val){
	 		$seller=Db::name('seller')->where('seller_id',$val['uid'])->find();
	 		if($seller){
	 			$dataone[$key]['ytype']="<font color='red'>商家</font>";
	 		}else{
	 			$dataone[$key]['ytype']="会员";
	 		}
	 		$dataone[$key]["fundtime"]=date("Y-m-d",$val['fundtime']);
	 	} 
		$data = $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
}
public function findfanxian(){
	$keyword=trim(input('keyword'));
	 	$dataone = Db::name('bankrollnd')->where("uid","=","$keyword")->select();
	 	foreach($dataone as $key=>$val){
	 		$seller=Db::name('seller')->where('seller_id',$val['uid'])->find();
	 		if($seller){
	 			$dataone[$key]['ytype']="<font color='red'>商家</font>";
	 		}else{
	 			$dataone[$key]['ytype']="会员";
	 		}
	 		$dataone[$key]["fundtime"]=date("Y-m-d",$val['fundtime']);
	 	} 
		$data = $this->returnData($dataone,"菜单搜索结果信息显示");
		return json($data);
}

//返现管理结束
//红包几率管理开始
public function redodds(){
		return $this->fetch();
	}
	
	public function getredodds(){
		$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('redconfig')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('redconfig')->field('id,gmultiple,mmultiple')->select(); 
		foreach($dataone as $key=>$value){
			$value['ggmultiple']=str_replace(",","<br/>",$value['gmultiple']);
			$value['mmmultiple']=str_replace(",","<br/>",$value['mmultiple']);
			$value['gmultiple']=str_replace(",",'\r\n',$value['gmultiple']);
		    $value['mmultiple']=str_replace(",",'\r\n',$value['mmultiple']);
			$dataone[$key]=$value;
		}
		$data= $this->returnData($dataone,"规格显示",$page['pagenum']);
		return json($data);
	}
	public function handleredodds(){
		$data = input('');
//	  	array_shift($data);
		if (!isset($data['id'])){
			    $data_odds=[
			       'gmultiple'=>str_replace(array("\r\n","\r","\n"),",",$data['gmultiple']),
			       'mmultiple'=>str_replace(array("\r\n","\r","\n"),",",$data['mmultiple'])
			    ];
			    $result=Db::name("redconfig")->where('id',1)->update($data_odds);
				if($result){
				    return $this->returnSta('数据插入成功','reload');  
		        }else{  
		            return $this->returnSta('数据插入失败','');
		        }
			} else {   
				$tid=input('id');
				$data_odds=[
			       'gmultiple'=>str_replace(array("\r\n","\r","\n"),",",$data['gmultiple']),
			       'mmultiple'=>str_replace(array("\r\n","\r","\n"),",",$data['mmultiple'])
			     ];
			    $result=Db::name("redconfig")->where('id',$tid)->update($data_odds);
		        if(is_int($result)){
		            return $this->returnSta('数据修改成功','reload');
			    }else{  
			        return $this->returnSta('数据修改失败','');
			    }
		}
	}
	//红包几率管理结束
	
	//关键字管理开始
    public function keyword(){
    	$level  =  Db::name('cha_level')->where('issork',1)->select();
		$level=getTree($level);
		$this->assign('level',$level);
    	return $this->fetch();
    }
	public function getkeyword(){
		$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('keyword')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('keyword')->limit($page['limit'])->order('id','desc')->select();
		foreach($dataone as $key=>$value){
			$onepname=Db::name('cha_level')->where('id',$value['onelevel'])->value('pname');
			$twopname=Db::name('cha_level')->where('id',$value['twolevel'])->value('pname');
			$threepname=Db::name('cha_level')->where('id',$value['threelevel'])->value('pname');
			$dataone[$key]=array_merge($value , array('onepname' =>$onepname,'twopname'=>$twopname,'threepname'=>$threepname));
		}
		$data = $this->returnData($dataone,"关键字显示",$page['pagenum']);
		return json($data);
	}
	public function handlekeyword(){
		$data = input('');
		if (!isset($data['id'])){
				if(Db::name("keyword")->insert($data)){
				    return $this->returnSta('数据插入成功','reload');  
		        }else{  
		            return $this->returnSta('数据插入失败','');
		        }
			} else {
		if(is_int(Db::name("keyword")->where('id',$data['id'])->update($data))){
		      return $this->returnSta('数据修改成功','reload');
			    }else{  
			        return $this->returnSta('数据修改失败','');
			    }
			}
	}
	public function keywordsfind(){
		$keyword=trim(input('keyword'));
		$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('keyword')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('keyword')->where("keywords","like","%$keyword%")->limit($page['limit'])->order('id','desc')->select();
		foreach($dataone as $key=>$value){
			$onepname=Db::name('cha_level')->where('id',$value['onelevel'])->value('pname');
			$twopname=Db::name('cha_level')->where('id',$value['twolevel'])->value('pname');
			$threepname=Db::name('cha_level')->where('id',$value['threelevel'])->value('pname');
			$dataone[$key]=array_merge($value , array('onepname' =>$onepname,'twopname'=>$twopname,'threepname'=>$threepname));
		}
		$data = $this->returnData($dataone,"关键词搜索结果信息显示",$page['pagenum']);
		return json($data);
	}
	public function delkeyword(){
		$id = (!empty(input("post.checkbox")) ? input("post.checkbox") : input("post.id", 1));
		if(Db::name('keyword')->delete($id)){
             return $this->returnSta('删除成功','reload');
        }else{  
           return $this->returnSta('删除失败','');
        }
	}
    //关键字管理结束

   // 推荐人管理开始
   public function recommend(){
   	$uid=input('uid');
   	$this->assign('aa',$uid);
   	 return $this->fetch();
   }
   public function getrecommend(){
   	$date=date('Y-m-d',time());
   	$date=strtotime(date('Y-m-d', strtotime($date . ' -60 day')));
   	   $dataone=Db::query("SELECT a.uid,count(*) count,a.uname,a.is_duihuan FROM b_user a JOIN b_user b ON a.uid = b.recommend WHERE b.ztime>{$date} GROUP BY
	b.recommend ORDER BY count desc");
   	   $data = $this->returnData($dataone,"关键字显示");
		return json($data);
   }
     private function httpGet($url){
        $curl = curl_init();
        //需要请求的是哪个地址
        curl_setopt($curl,CURLOPT_URL,$url);
        //表示把请求的数据已文件流的方式输出到变量中
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
   public function tuijian(){
   	 $uid=input('uid');
   	 $date=date('Y-m-d',time());

   	$date=strtotime(date('Y-m-d', strtotime($date . ' -60 day')));
   	   $recommend=Db::query("SELECT * FROM b_user a JOIN b_user b ON a.uid = b.recommend WHERE b.ztime>{$date} and b.recommend = $uid");
   	   $tui="";
   	 // $recommend=Db::name("user")->where('recommend',$uid)->select();
   	 foreach ($recommend as $key => $value){
   	 	$result = $this -> httpGet("http://sj.apidata.cn/?mobile=$value[uid]");
   	 	if($value['qid']==7){
   	 		$value['qid']='VIP';
   	 	}else{
   	 		$value['qid']='普通会员';
   	 	}
   	 	$dizhi=json_decode($result,true);
         if($dizhi['message']=="success"){
                  	 	$shen=$dizhi["data"]["province"];

   	 	               $shi=$dizhi["data"]["city"];
         }else{
         	$shen="找不到归属地";
         	$shi="";
         }
       	$ztime=date('Y-m-d',$value['ztime']);
       	 echo "<table width='500' border='1'>";
         if(Db::name("user")->where('recommend',$value['uid'])->find()){
  
         	$tui="<font color='red'>再次推荐</font>";
           echo "<tr>
           		<td>
           		 $value[uid]
                </td>
                <td>
           		 $value[uname]
                </td>
                <td>

           		 $value[qid]

                </td>
                 <td>
           		 $shen $shi
                </td>
                <td>
           		 $tui
                </td>
                <td>
           		 $ztime
                </td>
                </tr>";

         }else{
		$tui="<font>没有再次推荐</font>";
         	
           echo "<tr>
           		<td>
           		 $value[uid]
                </td>
                <td>
           		 $value[uname]
                </td>
                <td>
           		 $value[qid]
                </td>
                 <td>
           		 $shen $shi
                </td>
                <td>
           		 $tui
                </td>
                <td>
           		 $ztime
                </td>
                </tr>";
           	
         }
         echo "</table>";
   	 }
   }
   public function ajaxrestatus(){
	$status=Db::name('user')->where("uid","=",input('id'))->update(['is_duihuan'=>1]);
	dump($status);
	die;
	if($status){
		echo "已兑换";
	}else{
		echo "可兑换";
	}
}


   // 推荐人管理结束
 
// 兑换管理开始
public function duihuan(){
 		return	$this->fetch();
}
public function getduihuan(){
	
	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('prize')->count();
	 	$page = $this->pages($cou,$p,20);
	 	$dataone = Db::name('prize')->limit($page['limit'])->select();
	 	foreach($dataone as $key =>$value)
	 	{
	 		$dataone[$key]['uname']= Db::name('user')->where('uid',$value['uid'])->find()['uname'];
	 		if($value['status']==2){
					$dataone[$key]['status']="已完成";
	 		}else{
	 			$dataone[$key]['status']="未完成";
	 		}
	 		
	    }
		$data = $this->returnData($dataone,"商家信息显示",$page['pagenum']);
		return json($data);
}
public function ajaxduihuan(){
	$uid=input("uid");
	if(Db::name('user')->where('uid',$uid)->update(['is_chuli'=>1])){
		return "成功";
	}else{
		return "失败";
	}
}
public function finduihuan(){
	$keyword=trim(input('keyword'));
	 	$dataone = Db::name('prize')->where("uid","like","$keyword")->select();
	 	foreach($dataone as $key=>$value){
	 		$dataone[$key]['uname']= Db::name('user')->where('uid',$value['uid'])->find()['uname'];
	 		if($value['status']==1){
					$dataone[$key]['status']="已支付";
	 		}else{
	 			$dataone[$key]['status']="未完成";
	 		}
	 	} 
		$data = $this->returnData($dataone,"菜单搜索结果信息显示");
		return json($data);
}
// 兑换管理结束
// 
public function findtuiguang(){
	 	$dataone = Db::name('user')->where('tuijian_q',2)->whereor('tuijian_q',3)->select();
	 	foreach($dataone as $key => $value){
          		 	if($value["qid"]==1){
          		 		$dataone[$key]['qid']="普通";
          		 	}else{
          		 		$dataone[$key]['qid']="VIP";
          		 	}
            $dataone[$key]['ordercount']=Db::name('order')->where('uid',$value['uid'])->count('uid');
          	$dataone[$key]['ordertotal']=Db::name('order')->where('uid',$value['uid'])->sum('pricetotal');
          	$dataone[$key]['usertotal']=Db::name('bankrollnd')->where('uid',$value['uid'])->find()['total'];
          	$dataone[$key]['logintime']=date("Y-m-d H:m:s", $value['logtime']);
        }
		$data = $this->returnData($dataone,"商家搜索结果信息显示");
		return json($data);
 }
 // user查找全部员工
 public function findyuan(){
	 	$dataone = Db::name('user')->where('is_bxg',1)->select();
	 	foreach($dataone as $key => $value){
          		 	if($value["qid"]==1){
          		 		$dataone[$key]['qid']="普通";
          		 	}else{
          		 		$dataone[$key]['qid']="VIP";
          		 	}
            $dataone[$key]['ordercount']=Db::name('order')->where('uid',$value['uid'])->count('uid');
          	$dataone[$key]['ordertotal']=Db::name('order')->where('uid',$value['uid'])->sum('pricetotal');
          	$dataone[$key]['usertotal']=Db::name('bankrollnd')->where('uid',$value['uid'])->find()['total'];
          	$dataone[$key]['logintime']=date("Y-m-d H:m:s", $value['logtime']);
        }
		$data = $this->returnData($dataone,"商家搜索结果信息显示");
		return json($data);

 }



 public function findyuangong(){
 	$dataone=Db::query('select * from b_bankrollnd where uid in (select uid from b_user where is_bxg=1)');
	 	// $dataone = Db::name('bankrollnd')->field('is_bxg')->select();
	 	foreach($dataone as $key=>$val){
	 		$seller=Db::name('seller')->where('seller_id',$val['uid'])->find();
	 		if($seller){
	 			$dataone[$key]['ytype']="<font color='red'>商家</font>";
	 		}else{
	 			$dataone[$key]['ytype']="会员";
	 		}
	 		$dataone[$key]["fundtime"]=date("Y-m-d",$val['fundtime']);
	 	} 
		$data = $this->returnData($dataone,"菜单搜索结果信息显示");
		return json($data);
}

 public function findseller(){
 	$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('bankrollnd')->count();
	 	$page = $this->pages($cou,$p,20);

	 	$dataone = Db::query("select * from b_bankrollnd where uid in (select seller_id from b_seller)");
	 	foreach($dataone as $key=>$val){
	 		$seller=Db::name('seller')->where('seller_id',$val['uid'])->find();
	 		if($seller){
	 			$dataone[$key]['ytype']="<font color='red'>商家($seller[sname])</font>";
	 		}else{
	 			$dataone[$key]['ytype']="会员";
	 		}
	 		$dataone[$key]["fundtime"]=date("Y-m-d",$val['fundtime']);
	 	}
		$data = $this->returnData($dataone,"菜单搜索结果信息显示",$page['pagenum']);
		return json($data);
}
 public function finduserye(){
 		$p = (empty($p = input('get.p'))?1:$p);
	 	$cou = Db::name('bankrollnd')->count();
	 	$page = $this->pages($cou,$p,20);

	 	$dataone = Db::query("select * from b_bankrollnd where uid in (select uid from b_user)");
	 	foreach($dataone as $key=>$val){
	 		$seller=Db::name('seller')->where('seller_id',$val['uid'])->find();
	 		if($seller){
	 			$dataone[$key]['ytype']="<font color='red'>商家($seller[sname])</font>";
	 		}else{
	 			$dataone[$key]['ytype']="会员";
	 		}
	 		$dataone[$key]["fundtime"]=date("Y-m-d",$val['fundtime']);
	 	}
		$data = $this->returnData($dataone,"菜单搜索结果信息显示",$page['pagenum']);
		return json($data);
}
function daoru1(){
return $this->fetch();
}
function daoru(){
			 	if(request()->isAjax()){
            //$goods_id=input('goods_id');
			$goods_images=input('goods_images');
			$good_data=array(
					   'level' =>input('level'),
					   'brand_id' => input('goods(11)'),
					   'seller_id' => input('seller_id'),  //商家id
					   'is_on_sale' => input('g15',0),
					   'spec_type' => input('goods_type'),
					   'is_recommend' => input('g16',0),
					   'is_hot' => input('is_hot',0),
					   'is_new' => input('g17',0),
					   'sname' => input('goods(1)'),
					   'bewrite' => input('goods(2)'),
					   'keyword' => input('goods(18)'),
					   'goods_remark' => input('goods(20)'),
					   'field' => "中国",              //暂时设置空
					   'depict' => input('goods(19)'),
					   'dtime' => time(),
					   'pic' => input('goods_img'),
					   'goods_sn' => input('goods(3)'),
					   'goods_price' =>input('goods(12)'),
					   'store_count' =>input('goods(4)'),
					   'is_tgoods'=>input('is_tgoods',0),
					   'is_mgoods'=>input('is_mgoods',0),
			       );
			     $goods_id=Db::name('goods')->insertGetId($good_data);
				$data=[
				   'key_name'=>input("key_name"),
				   'price'=>input("price"),
				   "sku"=>input('sku'),
				];
				$contentt = [
				'goods_id'=>$goods_id,
				 'goods_content' => input('goods(21)')
				];
			$goods_content=Db::name('goods_content')->insert($contentt);
			//$goods_img=Db::name('goods_img')->where(['goods_id'=>$goods_id])->update(['plink' => input('goods_img')]);
			Db::name('goods_img')->where(['goods_id'=>$goods_id])->delete();
				if($goods_img_data=input('post.')['goods']['img']){
					foreach($goods_img_data as $key => $val){
						Db::name('goods_img')->insert(['goods_id' => $goods_id,'plink' => $val]);// 添加图片		   
				}
			}
                  //$spec=Db::name('spec_goods_price')->where(["goods_id"=>$goods_id])->update($data);
				  Db::name('spec_goods_price')->where(['goods_id'=>$goods_id])->delete();
					foreach(input('')['item'] as $k => $v){
					   // 批量添加数据
					   $v['price'] = trim($v['price']);
					   $v['sku'] = trim($v['sku']);
					   $spec_goods=Db::name('spec_goods_price')->insert(['goods_id'=>$goods_id,'keyid'=>$k,'key_name'=>$v['key_name'],'price'=>$v['price'],'sku'=>$v['sku']]);
					   if($spec_goods){
						   $error =0;
					   }       
				 } 
			     if($goods_id){
			     	 return $this->returnSta('数据导入成功','/admin/index/daoru1.html');
			     }else{
			     	 return $this->returnSta('数据导入失败','');
			     }
			     
		     
	 	}else{
			if(!empty(input('tiao'))){
				$goods_id = Db::name('goods')->where('goods_sn','=',input('tiao'))->find()['goods_id'];
			}else{
				$goods_id =input('id',1);
			}
	 		
		    $goodsInfo= Db::name('goods')->where('goods_id','=',$goods_id)->find();
            $goodsType = Db::name('cha_level')->where('pid=0')->select();
		    $goodsImages = Db::name('goods_img')->where('goods_id','=',$goods_id)->select();

			
			//显示分类
			
			$channel_three=Db::name('cha_level')->where(["level"=>3])->select();
			$channel_two=Db::name('cha_level')->where(['level'=>2])->select();
			$channel=Db::name('cha_level')->where('pid',0)->select();
			
			
			
			//只获取三级分类的信息
			$three=Db::name('cha_level')->where(["id"=>$goodsInfo['level']])->find();
			
			$two=Db::name('cha_level')->where(['id'=>$three['pid']])->find();
			$one=Db::name('cha_level')->where(['id'=>$two['pid']])->find();

			
			//读取规格关联信息
			
			$keys =  Db::name('spec_goods_price')->where('goods_id',$goods_id)->column("GROUP_CONCAT(`keyid` SEPARATOR '_') ");
			 $item = Db::name('spec_item')->where(['id'=>['in',implode(explode("_",$keys[0]),',')]])->select();
//          获取品牌信息
             $brand=Db::name('cha_brand')->where(['brand_id'=>$goodsInfo['brand_id']])->find();
		$sepc_img =Db::name('spec_image')->where(["goods_id"=>$goods_id])->select();
		
		    $goods_content=Db::name('goods_content')->where(["goods_id"=>$goods_id])->find();
		    $spec=Db::name('spec_goods_price')->where(["goods_id"=>$goods_id])->select();
			$this->assign('item',$item);
		    $this->assign('spec',$spec);
			$this->assign('sepc_img',$sepc_img);
		    $this->assign('brand',$brand);
		    $this->assign('goods_content',$goods_content);
			$this->assign('three',$three);
			$this->assign('channel',$channel);
			$this->assign('channel_two',$channel_two);
			$this->assign('channel_three',$channel_three);
			$this->assign('goodsImages',$goodsImages);
			$this->assign('goodsInfo',$goodsInfo);
			$this->assign('goodsType',$goodsType);
           return $this->fetch();
	 	}
}
function yanzhenggoods_sn(){
  if(empty(input('tiaoma')))
     return 0;
  $goods_id = Db::name('goods')->where('goods_sn','=',input('tiaoma'))->find();
 if(empty($goods_id))
 {
   return 0;
 }else{
   return 1;
 }
}

public function tfanxian(){
	$dataone = Db::name('usercredit')->limit($page['limit'])->group('seller_id')->select(); 
	dump($dataone);
	die;
	foreach ($dataone as $key => $val) {
		$dataone[$key]['yingde']=$val['price']-$val['price']*0.05;
	}

}
  
}