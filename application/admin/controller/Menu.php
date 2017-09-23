<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
class Menu extends Base
{
	public function getMenu(){
		dump(Db::name('admin_menu')->select());
	}
	public function upball(){
				//检测商家是否在资金表
			$allseller = Db::name('seller')->select();
			foreach($allseller as $value)
			{
				$user[] = empty($as =Db::name('bankrollnd')->where('uid',$value['seller_id'])->find())?Db::name('bankrollnd')->insert(['uid'=>$value['seller_id']]):$as;

			}
			dump($user);
			die;
	}
}
