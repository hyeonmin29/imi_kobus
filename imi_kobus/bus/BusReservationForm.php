<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
session_start();
include('../function/DBConn.php');
include('../function/MileFunction.php');
include('../function/UserFunction.php');
try {
	/* 세션 값 검사 */               
	if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
		throw new exception('로그인이 필요합니다.');
	}

	/* 포스트 값 검사 */               
	foreach ($_POST as $key=>$value) {
		if (empty($_POST[$key])) {
			throw new exception('값이 입력되지 않았습니다.');
		}	
	}
	
	$dtSelectDay = $_POST['day'] . " " . $_POST['bustime'];
	$dtReserDay = date('Y-m-d H:i:s', strtotime('+10 minutes'));
	$dtSelectCheck = strtotime($dtSelectDay);
	$dtCheck = strtotime($dtReserDay);

	#선택한 당일까지만 보여주기 위한 조건값
	$dtOneDayPlus = date('Y-m-d 00:00:00', strtotime($_POST['day'].'+1 day'));

	# 선택한 날짜, 시간이 현재 시간보다 10분 앞서있어야 버스 예매 가능
	if ($dtSelectCheck < $dtCheck ) {
		throw new exception('10분 후에 출발하는 버스부터 확인 가능합니다');
	}
	
	# 선택경로
	$strSelectRoute = $_POST['leave_location'].$_POST['arrive_location'];
	
	/* DB 연결 시작 */
	$CDBConn = new DB;
	$Conn = $CDBConn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	# 운행경로 조회
	$qryBusRoute = "
		SELECT DISTINCT(CONCAT(leave_location, arrive_location)) AS route
		  FROM bus_price
		 WHERE class = 'n'
	";
	$rstBusRoute = mysqli_query($Conn, $qryBusRoute);
	if ($rstBusRoute == false) {
		throw new exception('루트 조회 쿼리 오류');
	} 

	if (mysqli_num_rows($rstBusRoute) < 1) {
		throw new exception('루트 조회 오류');
	}
	
	$rgRoute = array();
	while ($rgBusRoute = mysqli_fetch_assoc($rstBusRoute)) {
		if ($rgBusRoute == false) {
			throw new exception('경로 배열 오류');
		}
		$rgRoute[] = $rgBusRoute['route'];
	}

	# 선택한 경로가 운행경로와 일치하는게 없으면 예외처리
	if (in_array($strSelectRoute , $rgRoute) == false) {
		throw new exception('운행 경로가 일치하지 않습니다.');
	}


	# 조건 : 출발위치 / 도착위치 / 지정한 시간 이후~ 지정한 날짜 24시까지
	$qrySelectBus = "
		SELECT seq, bus_num, bus_class, seat_cnt, people_cnt, move_time, price, bus_leave_location, bus_arrive_location, leave_day, arrive_time
		  FROM bus_move_info
		 WHERE bus_leave_location = '" . $_POST['leave_location'] . "'
		   AND bus_arrive_location = '" . $_POST['arrive_location'] . "'
		   AND TIMESTAMPDIFF(MINUTE, '" . $dtSelectDay . "', leave_day) > 0
		   AND TIMESTAMPDIFF(MINUTE, '" . $dtOneDayPlus . "', leave_day) < 1
	";
	$rstSelectBus = mysqli_query($Conn, $qrySelectBus);
	if ($rstSelectBus == false) {
		throw new exception('예매 버스 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstSelectBus) < 1) {
		throw new exception('예매 버스 조회 오류');
	}

	$rgArray = array();
	while ($rgSelectBus = mysqli_fetch_assoc($rstSelectBus)) {
		if ($rgSelectBus == false) {
			throw new exception('배열 오류');
		}
		$rgArray[] = $rgSelectBus;
	}

	$nCount = count($rgArray);

} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}

	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../bus/BusSelectForm.php';
	fnAlert($strAlert,$strLocation);
}
?>
<html>
	<body align = 'center'>
		<form action = 'BusReservationSeatForm.php' method = 'POST'>
			<p>
				<table align = 'center' border = 1 cellspacing = "0">
					<tr><th>버스번호</th> <th>버스 등급</th> <th>금액</th> <th>버스출발위치</th> <th>버스도착위치</th> <th>잔여 좌석 수</th> <th>운행시간</th> <th>출발일시</th>  <th>도착일시</th> <th>선택</th></tr>
					<?
					for ($i = 0; $i < $nCount; $i++) {
						$strBusClass = '일반';
						$strLeaveLocation = '전주';
						$strArriveLocation = '전주';

						if($rgArray[$i]['bus_class'] == 'p') {
							$strBusClass = '프리미엄';
							
						}
						if($rgArray[$i]['bus_class'] == 'h') {
							$strBusClass = '우등';
							
						}

						/* 출발 경로 */
						if ($rgArray[$i]['bus_leave_location'] == 'seoul') {
							$strLeaveLocation = '서울';
						}
						if ($rgArray[$i]['bus_leave_location'] == 'incheon') {
							$strLeaveLocation = '인천';
						}

						/* 도착 경로 */
						if ($rgArray[$i]['bus_arrive_location'] == 'seoul') {
							$strArriveLocation = '서울';
						}
						if ($rgArray[$i]['bus_arrive_location'] == 'incheon') {
							$strArriveLocation = '인천';
						}
						?>
						<tr>
							<td align = 'center'><?=$rgArray[$i]['bus_num']?></td>
							<td align = 'center'><?=$strBusClass?></td>
							<td align = 'center'><?=number_format($rgArray[$i]['price'])?></td>
							<td align = 'center'><?=$strLeaveLocation?></td>
							<td align = 'center'><?=$strArriveLocation?></td>
							<td align = 'center'><?=$rgArray[$i]['seat_cnt'] - $rgArray[$i]['people_cnt']?></td>
							<td align = 'center'><?=$rgArray[$i]['move_time']?></td>
							<td align = 'center'><?=substr($rgArray[$i]['leave_day'],0,16)?></td>
							<td align = 'center'><?=substr($rgArray[$i]['arrive_time'],0,16)?></td>
							<td align = 'center'><input type = 'radio' name = 'seq' value = "<?=$rgArray[$i]['seq']?>"/></td>
						</tr>
						<?
					}
					?>
				</table>
			</p>
			<input type = 'submit' value = '선택'>
		</form>
	</body>
</html>