<?php
namespace app\admin\controller;
use think\Db;
use think\Request;
class Questionnaire  extends Base
{
	public function index(){
        $spec = Db::name("questionnaire")->select();
        var_dump($spec);
		//return $this->fetch();
	}
	public function selectQuestionnaire()
	{

		 $spec = Db::name("questionnaire")->select();
		 dump($spec);

	}
}
