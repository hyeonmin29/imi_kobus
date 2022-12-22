<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/DBConn.php');
include('../function/MileFunction.php');
include('../function/UserFunction.php');
try {

	/* POST 값 검사 */               
	if (!isset($_POST['trade_code']) || empty($_POST['trade_code'])) {
		throw new exception('값이 입력되지 않았습니다.');
	}	

	$CDBconn = new DB;
	$Conn = $CDBconn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	$strTradeCode = $_POST['trade_code'];
	
	# 거래번호로 예매 정보 조회
	$qrySelectReser = "
		SELECT bus_move_info_seq, trade_code, user_num, status, bus_num, bus_class, price, seat_num, route, start_day, buy_day, change_day, account_code
		  FROM reservation
		 WHERE trade_code = '" . $strTradeCode . "'
	";
	$rstSelectReser = mysqli_query($Conn, $qrySelectReser);
	if ($rstSelectReser == false) {
		throw new exception('예매 내역 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstSelectReser) < 1) {
		throw new exception('거래번호와 일치하는 예매 내역이 없습니다.');
	}

	$rgSelectReser = mysqli_fetch_assoc($rstSelectReser);
	if ($rgSelectReser == false) {
		throw new exception('예매 내역 배열 오류');
	}
	
	# 출발 위치 조회 쿼리
	$qryLeaveLocation = "
		SELECT bus_leave_location
		  FROM bus_move_info
		 WHERE seq = " . $rgSelectReser['bus_move_info_seq'] . "
	";

	$rstLeaveLocation = mysqli_query($Conn, $qryLeaveLocation);
	if ($rstLeaveLocation == false) {
		throw new exception('출발지 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstLeaveLocation) < 1) {
		throw new exception('출발지 조회 오류');
	}

	$rgLeaveLocation = mysqli_fetch_assoc($rstLeaveLocation);
	if ($rgLeaveLocation == false) {
		throw new exception('출발지 배열 오류');
	}

	/* 출발지 */
	if($rgLeaveLocation['bus_leave_location'] == 'jeonju') {
		$strLeaveLocation = '전주';
	}
	if($rgLeaveLocation['bus_leave_location'] == 'seoul') {
		$strLeaveLocation = '서울';
	}
	if($rgLeaveLocation['bus_leave_location'] == 'incheon') {
		$strLeaveLocation = '인천';
	}

	/* 도착지 */
	if($rgSelectReser['route'] == 'jeonju') {
		$strArrive = '전주';
	}
	if($rgSelectReser['route'] == 'seoul') {
		$strArrive = '서울';
	}
	if($rgSelectReser['route'] == 'incheon') {
		$strArrive = '인천';
	}
	
	/* 버스 등급 */
	if($rgSelectReser['bus_class'] == 'n') {
		$strClass = '일반';
	}

	if($rgSelectReser['bus_class'] == 'h') {
		$strClass = '우등';
	}

	if($rgSelectReser['bus_class'] == 'p') {
		$strClass = '프리미엄';
	}
	
	/* 버스 예매 상태 */
	if ($rgSelectReser['account_code'] == 'rm00t' && $rgSelectReser['status'] == 'y') {
		$strStatus = '예매 완료';
	}

	if ($rgSelectReser['account_code'] == 'rm00f' && $rgSelectReser['status'] == 'n') {
		$strStatus = '예매 취소';
	}

	if ($rgSelectReser['account_code'] == 'rm00t' && $rgSelectReser['status'] == 'n') {
		$strStatus = '예매 취소';
	}

	if ($rgSelectReser['status'] == 'w') {
		$strStatus = '예약 완료';
	}

	if ($rgSelectReser['account_code'] == 'em00f' && $rgSelectReser['status'] == 'n') {
		$strStatus = '예약 취소';
	}
	
	/* 버스 변경 또는 취소 일시 */
	if($rgSelectReser['change_day'] == '0000-00-00 00:00:00') {
		$strChangeDay = '-';
	}

	if($rgSelectReser['change_day'] != '0000-00-00 00:00:00') {
		$strChangeDay = $rgSelectReser['change_day'];
	}


} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = 'MainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);

}
?>
<html>
	<h2>예매 내역</h2>
	<body align = center>
		<table align = center border = 1>
			<tr>
				<th>거래번호</th>
				<th>회원 번호</th> 
				<th>예매상태</th> 
				<th>버스번호</th> 
				<th>버스 등급</th> 
				<th>금액</th> 
				<th>좌석번호</th> 
				<th>출발지</th> 
				<th>도착지</th> 
				<th>출발일시</th> 
				<th>구매일시</th> 
				<th>취소 또는 변경일</th> 
			</tr>

			<tr>
				<td align = center><?=$rgSelectReser['trade_code']?></td>
				<td align = center><?=$rgSelectReser['user_num']?></td> 
				<td align = center><?=$strStatus?></td> 
				<td align = center><?=$rgSelectReser['bus_num']?></td> 
				<td align = center><?=$strClass?></td> 
				<td align = center><?=number_format($rgSelectReser['price'])?></td> 
				<td align = center><?=$rgSelectReser['seat_num']?></td> 
				<td align = center><?=$strLeaveLocation?></td> 
				<td align = center><?=$strArrive?></td> 
				<td align = center><?=$rgSelectReser['start_day']?></td> 
				<td align = center><?=$rgSelectReser['buy_day']?></td> 
				<td align = center><?=$strChangeDay?></td> 
			</tr>
		</table>
		<p>
			<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
		</p>
	</body>
</html>