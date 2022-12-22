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
	if (empty($_POST['seq'])) {
		throw new exception('원하는 시간대의 체크박스를 선택해주세요.');
	}	

	$nBusMoveInfoSeq = $_POST['seq'];

	/* DB 연결 시작 */
	$CDBConn = new DB;
	$Conn = $CDBConn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	$CUserClass = new UserClass($Conn);
	$CUserMile = new UserMile($Conn);

	$qrySelectBusMoveInfo = "
		SELECT bus_num, bus_class, seat_cnt, people_cnt, move_time, price, bus_leave_location, bus_arrive_location, leave_day, arrive_time
		  FROM bus_move_info
		 WHERE seq = " . $nBusMoveInfoSeq . "
	";
	$rstSelectBusMoveInfo = mysqli_query($Conn, $qrySelectBusMoveInfo);
	if ($rstSelectBusMoveInfo == false) {
		throw new exception('조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstSelectBusMoveInfo) < 1) {
		throw new exception('조회 오류');
	}

	$rgSelectBus = mysqli_fetch_assoc($rstSelectBusMoveInfo);
	if ($rgSelectBus == false) {
		throw new exception('버스 배열 오류');
	}

	/* 변수 초기화 */
	$rgReserBusNum =			$rgSelectBus['bus_num'];
	$rgReserClass =				$rgSelectBus['bus_class'];
	$rgReserSeatCnt =			$rgSelectBus['seat_cnt'];
	$rgReserPeopleCnt =			$rgSelectBus['people_cnt'];
	$rgReserMoveTime =			$rgSelectBus['move_time'];
	$rgReserPrice =				$rgSelectBus['price'];
	$rgReserBusLeaveLocation =  $rgSelectBus['bus_leave_location'];
	$rgReserBusArriveLocation = $rgSelectBus['bus_arrive_location'];
	$rgReserLeaveDay =			substr($rgSelectBus['leave_day'],0,16);
	$rgReserArriveTime =		substr($rgSelectBus['arrive_time'],0,16);
	
	/* 출발위치 */
	if ($rgReserBusLeaveLocation == 'jeonju') {
		$strrBusLeaveLocation = '전주';
	}
	if ($rgReserBusLeaveLocation == 'incheon') {
		$strrBusLeaveLocation = '인천';
	}
	if ($rgReserBusLeaveLocation == 'seoul') {
		$strrBusLeaveLocation = '서울';
	}
	
	/* 도착위치 */
	if ($rgReserBusArriveLocation == 'jeonju') {
		$strReserBusArriveLocation = '전주';
	}
	if ($rgReserBusArriveLocation == 'incheon') {
		$strReserBusArriveLocation = '인천';
	}
	if ($rgReserBusArriveLocation == 'seoul') {
		$strReserBusArriveLocation = '서울';
	}
		/* 버스 등급 */
	if ($rgSelectBus['bus_class'] == 'n') {
		$strClass = '일반';
	}
	if ($rgSelectBus['bus_class'] == 'p') {
		$strClass = '프리미엄';
	}
	if ($rgSelectBus['bus_class'] == 'h') {
		$strClass = '우등';
	}

	# 예약 정보에 선택된 버스의 좌석번호가 있는지 조회
	$qrySeatNum = "
		SELECT seat_num
		  FROM reservation
		 WHERE bus_move_info_seq = " . $nBusMoveInfoSeq . "
		   AND status = 'y'
	";
	$rstSeatNum = mysqli_query($Conn, $qrySeatNum);
	if ($rstSeatNum == false) {
		throw new exception('좌석 번호 조회 쿼리 오류');
	}

	 $rgSeat = array();
	 while ($rgSeatNum = mysqli_fetch_assoc($rstSeatNum)) {
		 if ($rgSeatNum == false) {
			 throw new exception('좌석 번호 배열 오류');
		 }

		 $rgSeat[] = $rgSeatNum['seat_num'];
	 }

	$nSeatRemain = $rgSelectBus['seat_cnt'] - $rgSelectBus['people_cnt'];

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
		<p>선택 버스 정보</p>
		<table align = 'center' border = 1 cellspacing = "0">
			<tr><th>버스번호</th> <th>버스 등급</th> <th>금액</th> <th>버스출발위치</th> <th>버스도착위치</th> <th>잔여 좌석 수</th> <th>운행시간</th> <th>출발일시</th>  <th>도착일시</th></tr>
				<tr>
					<td align = 'center'><?=$rgSelectBus['bus_num']?></td>
					<td align = 'center'><?=$strClass?></td>
					<td align = 'center'><?=number_format($rgSelectBus['price'])?></td>
					<td align = 'center'><?=$strrBusLeaveLocation?></td>
					<td align = 'center'><?=$strReserBusArriveLocation?></td>
					<td align = 'center'><?=$nSeatRemain?></td>
					<td align = 'center'><?=$rgSelectBus['move_time']?></td>
					<td align = 'center'><?=substr($rgSelectBus['leave_day'],0,16)?></td>
					<td align = 'center'><?=substr($rgSelectBus['arrive_time'],0,16)?></td>
				</tr>
		</table>

		
		<form method = 'POST' action = '../bus/BusReservationOk.php'>		
			<p>
				<table align = 'center' border = 1 cellspacing = "0" width = "300" > 
					<?
					$strLocation = '통로 옆';
					if ($rgSelectBus['bus_class'] !== 'n') {
						$strLocation = '통로';
					}
					?>
					<tr><th>창가</th> <th>통로 옆</th> <th><?=$strLocation?></th> <th>창가</th></tr>
					<?
					$disabled = '';
					$nSeatNum = 1;
					for ($nCol = 1; $nCol<10; $nCol++) {
						?>
						<tr>
						<?
						for ($nRow = 1; $nRow<5; $nRow++) {
							if($rgSelectBus['bus_class'] !== 'n' & $nRow==3 & $nCol<9 ){
									?>
									<td></td>
									<?
							} else {
								$disabled = '';
								# 조회한 좌석과 좌석번호가 일치하면 체크박스 비활성화
								if(in_array($nSeatNum,$rgSeat)){
									$disabled='disabled';
								}
								?>
								<td><input type = 'checkbox' name = 'seat_num[]' width="25%" value = '<?=$nSeatNum?>' <?=$disabled?>> <?=$nSeatNum?> </td>
								<?
								$nSeatNum++;
							}
						}
						?>
						</tr>
						<?
					}
					?>
				</table>	
			</p>
			<input type = 'hidden' name = 'bus_move_info_seq' value = <?=$nBusMoveInfoSeq?>>
			<? 
			if($nSeatRemain < 1) {
				?>
				<input type = 'submit' value = '예매 예약 하기' formaction = "../bus/BusWaitOk.php">
				<?
			} else {
				?>
				<p><input type = 'submit' value = '선택 완료'></p>
				<?
			}
			?> 
		</form>
		<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
	</body>
</html>