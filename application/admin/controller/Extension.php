<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
class Extension extends Base
{
    public function index()
    {
		return $this->fetch();
	}
	public function shop()
    {
		return $this->fetch();
	}
	
	/*
	 * 获取首页轮播
	 * 开关？状态？
	 */
	 public function getSlide(){
	 	$slide=Db::name('slide')->order('id desc')->select();
		$data = $this->returnData($slide,"首页轮播图");
		return json($data);
	 }
	 /*
	  * 首页轮播
	 * 添加或者修改方法
	 * 把操作的参数请求给我就好啦
	 */
	 
	 public function slideHandle(){
	 	$data['status'] = 200;
		$data['msg'] ="更新成功";
		$data['url'] = "";
		$data['render'] = true;
		$data['data'] = ["name"=>"paco","url"=>"jqcool.net"];
		echo header("content-type:text/html; charset=utf-8");
		echo json_encode($data);
		
		die;
		 	$data = input('');
	 	unset($data['file']);
		return json($this->dataHandle('slide',$data));
		die;
	 }
	 
	 /*
	  * 首页轮播
	 * 删除的方法
	 */
	 public function dlideDelete(){
	 	$id = (!empty(input("post.checkbox")) ? input("post.checkbox") : input("post.id", 0));
		$this->delData($id);
	 }
}
