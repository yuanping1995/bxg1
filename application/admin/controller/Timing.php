<?php
namespace app\admin\controller;
use think\Db;
use think\Request;

class Timing extends \think\Controller {

	/**
	 * 每日返现方法
	 */
	public function Returnedrecord() {
		//if(Request::instance()->isMobile()){ // 是否为手机访问
		//file_put_contents("./runtime/log/red_log.txt",$red_log, FILE_APPEND);
		$tip = '';
		$myfile = @fopen("./cert/log/Returned_record.txt", "r");
		$status = 0;
		if ($myfile) {
			$tip1 = date("Y年m月d") . "日用户返现成功";
			if (is_int(strpos(file_get_contents('./cert/log/Returned_record.txt'), $tip1))) {
				$a = 1;
				//$tip = date("Y-m-d H:i:s")."再次请求返现，报告今日已经返现\r\n";

			} else {
				$status = 1;
			}
		} else {
			$status = 1;
		}

		if ($status == 1) {
			Db::startTrans();
			try {
				$time = time();
				$userquery = Db::query("UPDATE b_bankrollnd SET is_profit = (is_profit+day_profit),profit = (profit-day_profit),fundtime={$time} where profit>=day_profit");
				$tip = date("Y年m月d") . "日用户返现成功\r\n";
			} catch (\Exception $e) {
				Db::rollback();
				$tip = date("Y-m-d H:i:s") . "执行每日返现失败\r\n";
			}
		}

		//}

		file_put_contents("./cert/log/Returned_record.txt", $tip, FILE_APPEND);
	}

	//每日自动 7天 确认收货
	public function Confirmreceipt() {
		$myfile = @fopen("./cert/log/Confirm_receipt.txt", "r");
		$tip = '';
		$status = 0;
		if ($myfile) {
			$tip1 = date("Y年m月d") . "日用户自动确认收货并且返现成功";
			if (is_int(strpos(file_get_contents('./cert/log/Confirm_receipt.txt'), $tip1))) {
				$a = 1;
			} else {
				$status = 1;
			}
		} else {
			$status = 1;
		}
		if ($status == 1) {
			Db::startTrans();
			try {
				$i = 0;
				$ifan = 0;
				$fanxianren = '';
				$order = Db::name('order') -> where("status", 'in', '2,8,9') -> where("otime <= " . strtotime("-7 day")) -> where('is_hebing is null') -> where('is_fan', 0) -> select();
				foreach ($order as $value) {
					$user_key = $value['uid'];
					$user = Db::name('user') -> where(['uid' => $user_key]) -> find();
					$user['total'] = $value['sellertotal']; //商家返现
					$userquery = Db::query("UPDATE b_bankrollnd SET total = (total+$user[total]) where uid = $value[seller_id]");
					if ($value['status'] == 2) {
						$i++;
						$user['total'] = $value['draw'];
						if($user['qid'] == 7)
						{
							$userquery = Db::query("UPDATE b_bankrollnd SET money = (money+$user[total]) where uid = $user_key");
						}
						$userquery = Db::query("UPDATE b_bankrollnd SET money = (money+$user[total]) where uid = $user_key");
						
						$Confirm = Db::name('order') -> where(['id' => $value['id']]) -> update(["status" => 8, 'is_fan' => 1]);
					} else {
						$ifan++;
						
						$txtotal_one = DB::name('bankrollnd') -> where('uid', $value['seller_id']) -> value('total');
						$fanxianren = $fanxianren . $value['seller_id'] . '确认订单' . $value['id'] . '成功余额' . $txtotal_one . '元    ';
						$Confirm = Db::name('order') -> where(['id' => $value['id']]) -> update(['is_fan' => 1]);
					}
				}
				$tip = date("Y年m月d") . "日用户自动确认收货并且返现成功$i,七天自动打款执行$ifan,$fanxianren\r\n";
			} catch (\Exception $e) {
				Db::rollback();
				$tip = date("Y-m-d H:i:s") . "执行自动确认收货失败\r\n";
			}
		}
		//}
		file_put_contents("./cert/log/Confirm_receipt.txt", $tip, FILE_APPEND);
	}

}
