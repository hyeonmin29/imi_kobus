<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
class BusClass {

	public function __construct($CConnection){
		$this->db = $CConnection;
	}

	public function fnBusInfo($strLocation) {
		if(empty($strLocation)) {
			return false;
		}
	

		#전주에 있는 버스정보 조회
		$qryBusInfo = "
			SELECT bus_num, bus_class, seat_cnt, bus_location
			  FROM bus_info
			 WHERE bus_location = '" . $strLocation . "'
			  ORDER BY change_day ASC
			  LIMIT 24
		";

		$rstBusInfo = mysqli_query($this->db, $qryBusInfo);
		if ($rstBusInfo == false) {
			return false;
		}

		if (mysqli_num_rows($rstBusInfo) < 1) {
			return false;
		}
		

		$rgBusInfomation = array();
		while($rgBusInfo = mysqli_fetch_assoc($rstBusInfo)){
			if ($rgBusInfo == false) {
				return false;
			}
			$rgBusInfomation[] = $rgBusInfo; 

		}
		return $rgBusInfomation;
	}
	
	public function fnDriverInfo($strLocation) {
		if(empty($strLocation)) {
			return false;
		}

		$qryDriverInfo = "
			SELECT driver_num
			  FROM driver_info
			 WHERE driver_location = '" . $strLocation . "'
			  ORDER BY change_day ASC
			  LIMIT 24
		";
		$rstDriverInfo = mysqli_query($this->db, $qryDriverInfo);
		if ($rstDriverInfo == false) {
			return false;
		}

		if (mysqli_num_rows($rstDriverInfo) < 1) {
			return false;
		}
				
		$rgDriverNum = array();
		while ($rgDriverInfo = mysqli_fetch_assoc($rstDriverInfo)) {
			if ($rgDriverInfo == false) {
				throw new exception('버스기사 배열 오류');
			}
			$rgDriverNum[] = $rgDriverInfo['driver_num'];
		}
		return $rgDriverNum;
	}


	public function fnMoveInfo($strLeaveLocation, $strArriveLocation, $rgLeaveDay) {

		if(empty($strLeaveLocation) || empty($strArriveLocation) || empty($rgLeaveDay)) {
			return false;
		}
		
		# 날짜 배열 cnt
		$nCountLeaveDay = count($rgLeaveDay);	
		
		# 버스 정보 조회
		$rgBusInfo = self::fnBusInfo($strLeaveLocation);
		if ($rgBusInfo == null) {
			return false;
		}

		# 기사 번호 조회
		$rgDriverInfo = self::fnDriverInfo($strLeaveLocation);
		if ($rgDriverInfo == null) {
			return false;
		} 
		
		# 선택된 경로에 날짜가 있는지 조회
		$qryDayCheck = "
			SELECT leave_day 
			  FROM bus_move_info
			 WHERE bus_leave_location = '" . $strLeaveLocation . "'
			   AND bus_arrive_location = '" . $strArriveLocation . "'
		";
		$rstDayCheck = mysqli_query($this->db, $qryDayCheck);
		if ($rstDayCheck == false) {
			return false;
		}

		$rgDayCheck = array();
		while ($rgCheck = mysqli_fetch_assoc($rstDayCheck)) {
			if ($rgCheck == false) {
				return false;
			}

			$rgDayCheck[] = $rgCheck['leave_day'];
		}


		$nMove=0;
		for ($i = 0; $i < $nCountLeaveDay; $i++) {

			# 서울-전주일 때 시간
			$dtMoveTime = '03:00';
			# 전주 - 서울 일 때 가격 및 시간
			$nPrice = 15600;

			# 날짜가 이미 운행정보에 배차되어있으면 실행문 continue....
			if (in_array($rgLeaveDay[$i],$rgDayCheck) == true) {
				continue;
			}		
			
			# 인천- 전주일 때 시간
			if($strLeaveLocation == 'incheon' || $strArriveLocation == 'incheon') {

				$dtMoveTime = '03:30';
				$nPrice = 15900;
			}

			# 우등버스일 때 가격 +5000원
			if($rgBusInfo[$nMove]['bus_class'] === 'h') {
				$nPrice = $nPrice + 5000;
			}
			
			# 프리미엄버스일 때 가격 +10000원
			if($rgBusInfo[$nMove]['bus_class'] === 'p') {
				$nPrice = $nPrice + 10000;
			}

			$dtArriveTime = date("Y-m-d H:i:s", strtotime($rgLeaveDay[$i]. "+3 hour"));

			$qryJeonJuInsert = "
				INSERT INTO bus_move_info SET
					bus_num = " . $rgBusInfo[$nMove]['bus_num'] . ",
					driver_num = " . $rgDriverInfo[$nMove] . ",
					bus_route = '" . $strArriveLocation . "',
					bus_class = '" . $rgBusInfo[$nMove]['bus_class'] . "',
					seat_cnt = " . $rgBusInfo[$nMove]['seat_cnt'] . ",
					move_time = '" . $dtMoveTime . "',
					price = " . $nPrice . ",
					bus_leave_location = '" . $strLeaveLocation . "',
					bus_arrive_location = '" . $strArriveLocation . "',
					leave_day = '" . $rgLeaveDay[$i] . "',
					arrive_time = '" . $dtArriveTime . "'
			";
			$rstJeonJuInsert = mysqli_query($this->db, $qryJeonJuInsert);
			if ($rstJeonJuInsert == false) {
				return false;
			}

			if (mysqli_affected_rows($this->db) < 1) {
				return false;
			}

			$nMove++;
			
			#기사, 버스가 하루에 2번 운행
			if ($nMove == 24) {
				$nMove = 0;
			}
		}
		return true;
	}






}
?>