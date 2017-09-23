<?php
namespace app\mobile\controller;
use think\Db;
class Index extends \think\Controller {
	public function index() {
// 		$data['account'] = 1600; //借款金额
// $data['apr'] = 12; //年利率
// $data['period']=365;//借款天数
// $data['repayday']=23;//还款日
// $this->interest($data);

		$script = &load_wechat('Script');
		// 获取JsApi使用签名，通常这里只需要传 $ur l参数
		$options = $script->getJsSign(__APPURL__);
		// 处理执行结果
		if ($options === FALSE) {
			// 接口失败的处理
			echo $script->errMsg;
		} else {
			// 接口成功的处理
			$this->assign('options', $options);
			return $this->fetch();
		}
	}
	/**
	 * 根据用户经纬度对比数据库中数据，按距离排序
	 */
	public function seller_list() {
		$u_lat = '40.017349';
		$u_lon = '116.407143,';
		$list = Db::name('seller')->select(); //读取商家列表
		$DistanceModel = new app\mobile\model\Distance;
		$result = $DistanceModel->range($u_lat, $u_lon, $list);
		// 处理执行结果
		if ($result === FALSE) {
			// 接口失败的处理
			return '错误代码：'.$DistanceModel->errCode.'错误原因：'.$DistanceModel->errMsg;
		} else {
			// 接口成功的处理
		    dump($result);
		}
	}
/**
 * 按天计息，按月付息，到期还本
 * @param $data 传入参数 account 借款总额 period 借款期限（天数） apr 借款利率（年利率）repayday 指定每月还款日，不能超过28，自己体验
 * @return mixed
 */
  public function interest($data){
    if($data['account']==''){
        return false;
    }
    if($data['period']==''){
        return false;
    }
    if($data['apr']==''){
        return false;
    }
    $day_apr = $data['apr'] / 365 / 100; //天利率
    $days = $data['period']; //借款期限
    $interest_all = round($day_apr * $days * $data['account'], 2);
    echo '总利息：' . $interest_all . '<br/>';
    $nowtime = time(); //借款成功时间
    //$nowtime = strtotime('2015-12-30 12:01:58');
    $today = date('d', $nowtime);
    $thismonth = date('m', $nowtime);
    $thisyear = date('Y', $nowtime);
    $nowHis = date('H:i:s', $nowtime);
    $time = strtotime('+' . $days . 'day', $nowtime); //借款到期时间
    echo $daoqi = date('Y-m-d H:i:s', $time) . '<br/>'; //借款到期格式化时间
    $repayday = $data['repayday']; //每月还款日，大于当天则为本月计息，小于则下月计息
    $repayday = str_pad($repayday, 2, 0, STR_PAD_LEFT); //十位0填充
    echo '<hr/>';
    if ($repayday > $today) {
        $days = $repayday - $today;
    } else {
        /*if ($thismonth == 12) { //最后一个月的时候
            $r_month = 1;
            $r_year = $thisyear + 1;
        } else {
            $r_year = $thisyear;
            $r_month = $thismonth + 1;
        }
        $r_month = str_pad($r_month, 2, 0, STR_PAD_LEFT); //10位0填充*/
        //$repayOnetime = strtotime($r_year . '-' . $r_month . '-' . $repayday . ' ' . $nowHis); //第一次还款时间
        $days = date('t', $nowtime) - $today + $repayday; //两个不同月份的日期之间相差的天数，借款成功月份的总天数-借款成功时的号数+下个月的还款日
    }

    $repayOnetime = strtotime('+' . $days . 'day', $nowtime); //第一次还款时间
    echo date('Y-m-d H:i:s', $repayOnetime) . '<br/>';
    $part_interest_all = $_result[0]['interest'] = round($day_apr * $days * $data['account'], 2);
    $_result[0]['capital']=0;
    $_result[0]['repay_time'] = date('Y-m-d H:i:s', $repayOnetime);
    $num = (date("Y", $time) - $thisyear) * 12 + (date("m", $time) - $thismonth); //考虑次数最多的情况
    for ($i = 1; $i <= $num; $i++) {
        $perrepay_time = strtotime('+' . $i . 'month', $repayOnetime);
        if ($perrepay_time < $time) {
            echo date('Y-m-d H:i:s', $perrepay_time) . '<br/>'; //每期还款时间
            $pre_time = strtotime('-1 month', $perrepay_time); //上一次时间还款时间
            $days = ($perrepay_time - $pre_time) / (3600 * 24); //距离上一次还款时间相距的天数
            $_result[$i]['interest'] = round($day_apr * $days * $data['account'], 2);
            $_result[$i]['capital']=0;
            $_result[$i]['repay_time'] = date('Y-m-d H:i:s', $perrepay_time);
            $part_interest_all += $_result[$i]['interest'];
        } elseif ($perrepay_time == $time) { //最后一期的时候
            $_result[$i]['interest'] = $interest_all - $part_interest_all;
            $_result[$i]['capital']=$data['account'];
            $_result[$i]['repay_time'] = date('Y-m-d H:i:s', $time);
            echo $_result[$i]['repay_time'] . '<br/>';
        }
    }

    if (intval(date('d', $time)) != intval($repayday)) {
        //$i = count($_result);
        //$c = $i - 1;
        //$days = ($time - strtotime($_result[$c]['repay_time'])) / (3600 * 24);
        //$_result[$i]['interest'] = round($day_apr * $days * $data['account'], 2); 防止一分钱的问题
        $_result[$i]['interest'] = $interest_all - $part_interest_all;
        $_result[$i]['capital']=$data['account'];
        $_result[$i]['repay_time'] = date('Y-m-d H:i:s', $time);
        echo date('Y-m-d H:i:s', $time);
    }
    dump($_result);
    $interest_all = 0.00;
    foreach ($_result as $v) {
        $interest_all += $v['interest'];
    }
    echo '按分期算得出的利息：' . round($interest_all, 2);
  }
}
