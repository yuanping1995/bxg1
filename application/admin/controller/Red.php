<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
class Red extends Base
{
	public function index(){
		$conf = Db::name('redconfig')->find();
		$style = scandir('./application/red/view/red/');
		unset($style[0],$style[1]);
		$sty = array('purple','green','blue','red');
		$def_dir = './application/red/view/';
		foreach($style as $value)
		{
		
			if(file_exists($def_dir.'red/'.$value.'/logo.jpg'))
			{
				$data['img']= ltrim($def_dir.'red/'.$value.'/logo.jpg','.');
			}else{
				$data['img']= ltrim($def_dir.'logo.jpg','.');
			}
			
			$data['dir']= $value;
			$data['is']= 0;
			if($conf['style'] == $value)
			{
				$data['is']= 1;
			}
			$data['color'] = $sty[array_rand($sty)];
			$staleinfo[]=$data;
		}

		$this->assign('style',$staleinfo);
		$tongji = Db::name('red_user')->limit(7)->group('end')->order('time desc')->select();
		$datez =array('周日','周一','周二','周三','周四','周五','周六');
		$tongjiRed = array();
		foreach($tongji as $value)
		{
			$tjdata['mony']=sprintf("%.3f",Db::name('red_user')->where('end',$value['end'])->sum('num'));
			$tjdata['date']=$datez[date("w",$value['time'])];
			$tjdata['click']=Db::name('red_user')->where('end',$value['end'])->sum('click') / 1000;
			$tjdata['num']=Db::name('red_user')->where('end',$value['end'])->group('uid')->count();
			$tongjiRed[]= $tjdata;
		}

		$user = Db::name('red_user')->alias('a')->field('b.icon,b.uname,b.mobile,a.num,a.click,max(a.dijibo) bo')->join('user b','a.uid = b.uid')->where('end',$tongji['0']['end'])->limit(6)->order('num desc,time desc')->group('a.uid')->select();
		$usermin = Db::name('red_user')->where('end',$tongji['0']['end'])->avg('num');

		$this->assign('user',$user);
		$this->assign('usermin',$usermin);
		$this->assign('tongjiRed',array_reverse($tongjiRed));
		return $this->fetch();
	}
	public function SetStyle()
	{
		$style=input('style');
		 $spec = Db::name("redconfig")->where('id',1)->update(['style'=>$style]);
	}
}
