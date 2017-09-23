<?php 
namespace app\mobile\model;

class Distance extends Model{
	/** 执行错误消息及代码 */
	public $errMsg;
	public $errCode;
	/*
	*根据经纬度计算距离
	*u_lat 用户纬度
	*u_lon 用户经度
	*list sql语句
	*/
	public function range($u_lat,$u_lon,$list){
		if(!empty($u_lat) && !empty($u_lon)){
			foreach ($list as $row) {
				$row['km'] = $this->nearby_distance($u_lat, $u_lon, $row['lat'], $row['lon']);
				$row['km'] = round($row['km'], 1);
				$res[] = $row;
			}
			if (!empty($res)) {
				foreach ($res as $user) {
					$ages[] = $user['km'];
				}
				array_multisort($ages, SORT_ASC, $res);
				return $res;
			}else{
				$this->errMsg = '身边没有商家信息';
			    $this->errCode = '1002';
				return false;
			}
		}else{
			$this->errMsg = '当前用户定位错误';
			$this->errCode = '1001';
			return false;
		}
	}
	
	 //计算经纬度两点之间的距离
  public function nearby_distance($lat1, $lon1, $lat2, $lon2) {
    $EARTH_RADIUS = 6378.137;
    $radLat1 = $this->rad($lat1);
    $radLat2 = $this->rad($lat2);
    $a = $radLat1 - $radLat2;
    $b = $this->rad($lon1) - $this->rad($lon2);
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
    $s1 = $s * $EARTH_RADIUS;
    $s2 = round($s1 * 10000) / 10000;
    return $s2;
  }

   private function rad($d) {
	   return $d * 3.1415926535898 / 180.0;
   }
}