<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
class Base extends \think\Controller
{
	function _initialize(){
		$request = Request::instance()->action();
		$arra=['login','logintui','reg'];
		if(!in_array($request,$arra))
		{
			if(empty(cookie('admin_login')))
			{
				header("Location:http://127.0.0.1/index.php/admin/index/login");
				exit();
			}
		}
	}
	/*
	 * 默认数据的添加修改
	 * $tablename 表名
	 * $data 数据
	 * $pk 主键名
	 */
	protected function dataHandle($tablename,$data,$pk='id'){
		if(isset($data['_']))
		unset($data['_']);
		if(isset($data['file']))
		unset($data['file']);
		if(empty($tablename))
		{
			return "请给出正确的表名";
		}	
		$register =Db::name("$tablename");
		if (!isset($data[$pk])) {
				if($register->insert($data)){
				    return $this->returnSta('数据插入成功','reload');  
		        }else{  
		            return $this->returnSta('数据插入失败','');
		        }
			}else{
				
		if(is_int($register->update($data))) {  
			            return $this->returnSta('数据修改成功','reload');
			    }else{  
			            return $this->returnSta('数据修改失败','');
			    }
			}
	}
	/*
	 * 默认数据删除方法
	 * $tablename 表名
	 * $id 主键
	 */
	protected function delData($tablename,$id){
		if(empty($tablename))
		{
			return $this->returnSta('请给出正确的表名','');
		}
	   if(empty($id))
	   {
	       return $this->returnSta('请保证主键不为空','');;
	   }
//	   $where['id'] = array('in', $id);
        if(Db::name("$tablename")->delete($id)){
             return $this->returnSta('删除成功','reload');
        }else{  
           return $this->returnSta('删除失败','');
        }  
	}
	/*
	 * 		分页计算
	 * $count	 总页数
	 * $offset	 第几页
	 * $limit	 每页多少条
	 * return array pagenum总页数  offset第几页  limit数据段
	 */
	 
	 protected function pages($count,$offset,$limit){
	 	$page['pagenum']=ceil($count/$limit);
		$offset=($offset>$page['pagenum']?$page['pagenum']:$offset);
		$page['offset']=$offset>$page['pagenum']?$page['pagenum']:$offset;
		$page['limit']=($offset-1)*$limit.','.$limit;
		return $page;
	 }
/*
 * 返回数据查询结果 
 * $data 返回的数据
 */
 

public function returnData($data,$title="数据查询",$page=1){
	$msg=[];
	$msg['status'] =200;
	$msg['data']['list'] = $data;
	$msg['data']['title']=$title;
	$msg['pages'] = $page;
	
	return $msg;
}

public function returnSta($tip='操作成功',$url=''){
	$data['status'] = 200;
	$data['msg'] =$tip;
	$data['url'] = $url;
	$data['data'] = ["name"=>"百信购","url"=>"m.bxgogo.com"];
	return $data;
}
	
}
