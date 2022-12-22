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
	if (empty($_POST['reservation_seq'])) {
		throw new exception('값이 입력되지 않았습니다.');
	}	

	/* DB 연결 시작 */
	$CDBConn = new DB;
	$Conn = $CDBConn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	/* 변수 초기화 */
	$nBeforeReservationSeq = $_POST['reservation_seq'];
	$dtSelectDay = date('Y-m-d H:i:s', strtotime('+30 minutes'));

	# 변경될 티켓 정보 조회 쿼리
	$qryBeforeTicket = "
		SELECT route, start_day, bus_num, bus_move_info_seq
		  FROM reservation
		 WHERE seq = " . $nBeforeReservationSeq . "
	";
	$rstBeforeTicket = mysqli_query($Conn, $qryBeforeTicket);
	if ($rstBeforeTicket == false) {
		throw new exception('변경될 티켓 정보 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstBeforeTicket) < 1) {
		throw new exception('변경될 티켓 정보 조회 오류');
	}
	
	$rgBeforeTicket = mysqli_fetch_assoc($rstBeforeTicket);
	if ($rgBeforeTicket == false) {
		throw new exception('변경 티켓 배열 오류');
	}

	# 변경될 버스 출발지역 조회
	$qryBeforeLocation = "
		SELECT bus_leave_location
		  FROM bus_move_info
		 WHERE seq = " . $rgBeforeTicket['bus_move_info_seq'] . "
	";
	$rstBeforeLocation = mysqli_query($Conn, $qryBeforeLocation);
	if ($rstBeforeLocation == false) {
		throw new exception('변경할 티켓 경로 조회 쿼리 오류');
	}
	
	if (mysqli_num_rows($rstBeforeLocation) < 1) {
		throw new exception('변경할 티켓 경로 조회 오류');
	}
	
	$rgBeforeLocation = mysqli_fetch_assoc($rstBeforeLocation);
	if ($rgBeforeLocation == false) {
		throw new exception('변경할 티켓 경로 배열 오류');
	}

	# 변경할 버스 조회
	$qryChangeBus = "  
		SELECT seq, bus_num, bus_class, seat_cnt, people_cnt, move_time, price, bus_leave_location, bus_arrive_location, leave_day, arrive_time
		  FROM bus_move_info
		 WHERE bus_leave_location =  '" . $rgBeforeLocation['bus_leave_location'] . "'
		   AND bus_arrive_location = '" . $rgBeforeTicket['route'] . "'
		   AND TIMESTAMPDIFF(MINUTE, '" . $dtSelectDay . "', leave_day) > 0
		  ORDER BY leave_day ASC
	"; 

	$rstChangeBus = mysqli_query($Conn, $qryChangeBus);
	if ($rstChangeBus == false) {
		throw new exception('버스 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstChangeBus) < 1) {
		throw new exception('버스 조회 오류');
	}
	
	$rgArray = array();
	while ($rgChangeBus = mysqli_fetch_assoc($rstChangeBus)) {
		if ($rgChangeBus == false) {
			throw new exception('버스 조회 배열 오류');
		}
		$rgArray[] = $rgChangeBus;
	}

	$nCount = count($rgArray);

} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}

	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../bus/BusChangeForm.php';
	fnAlert($strAlert,$strLocation);
}
?>

<html>
	<body align = 'center'>
		<form action = 'BusChangeSeatForm.php' method = 'POST'>
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
						if ($rgArray[$i] == 'incheon') {
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
							<td align = 'center'><?=$rgArray[$i]['leave_day']?></td>
							<td align = 'center'><?=$rgArray[$i]['arrive_time']?></td>
							<td align = 'center'><input type = 'radio' name = 'bus_move_info_seq' value = "<?=$rgArray[$i]['seq']?>"/></td>
						</tr>
						<?
					}
					?>
				</table>
			</p>
			<input type = 'hidden' name = 'before_reservation_seq' value = '<?=$nBeforeReservationSeq?>'>
			<input type = 'submit' value = '선택'>
		</form>
	</body>
</html>