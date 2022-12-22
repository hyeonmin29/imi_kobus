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

	/* DB 연결 시작 */
	$CDBConn = new DB;
	$Conn = $CDBConn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	$CUserClass = new UserClass($Conn);
	$CUserMile = new UserMile($Conn);

	$strId = '';
	$strUserNumColumn = array(
		'user_num',
		'user_name'
	);

	#회원번호 조회
	$rgUserInfo = $CUserClass -> fnUserInfo(implode(",", $strUserNumColumn), $strId);
	if($rgUserInfo == false) {
		throw new exception('회원 번호 조회 오류');
	}
	
	# 거래번호로 예매 정보 조회
	$qrySelectReser = "
		SELECT bus_move_info_seq, trade_code, user_num, status, bus_num, bus_class, price, seat_num, route, start_day, buy_day, change_day, account_code
		  FROM reservation
		 WHERE user_num = '" . $rgUserInfo['user_num'] . "'
		  ORDER BY start_day ASC
	";
	$rstSelectReser = mysqli_query($Conn, $qrySelectReser);
	if ($rstSelectReser == false) {
		throw new exception('예매 내역 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstSelectReser) < 1) {
		echo '예매 내역이 없습니다.';
	}
	

	$rgArray = array();
	while ($rgSelectReser = mysqli_fetch_assoc($rstSelectReser)){
		if ($rgSelectReser == false) {
			throw new exception('예매 내역 배열 오류');
		}
		$rgArray[] = $rgSelectReser;
	}

	$nCount = count($rgArray);
	
	$rgLeaveLocation = array();
	for ($i = 0; $i < $nCount; $i++) {
		$qrySelectLeaveLocation = "
			SELECT bus_leave_location
			  FROM bus_move_info
			 WHERE seq = " . $rgArray[$i]['bus_move_info_seq'] . "
		";
		$rstSelectLeaveLocation = mysqli_query($Conn, $qrySelectLeaveLocation);
		if ($rstSelectLeaveLocation == false) {
			throw new exception('버스 출발지 조회 쿼리 오류');
		}
		if (mysqli_num_rows($rstSelectLeaveLocation) < 1) {
			throw new exception('버스 출발지 조회 오류');
		}

		$rgSelectLeaveLocation = mysqli_fetch_assoc($rstSelectLeaveLocation);
		if ($rgSelectLeaveLocation == false) {
			throw new exception('버스 출발지 배열 오류');
		}
		$rgLeaveLocation[] = $rgSelectLeaveLocation['bus_leave_location'];
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
				<th>번호</th>
				<th>거래번호</th>
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
			<?
			for($i = 0; $i < $nCount; $i++) {
				$strClass = '일반';
				$strStatus = '예매 완료';
				$strLeaveLocation = '전주';
				$strArriveLocation = '전주';
				$strColor = 'blue';
							
				# change_day
				$strChangeDay = $rgArray[$i]['change_day'];

				/* 버스 등급 */
				if($rgArray[$i]['bus_class'] === 'h') {
					$strClass = '우등';
				}

				if($rgArray[$i]['bus_class'] === 'p') {
					$strClass = '프리미엄';
				}


				/* 예약 상태 */
				if ($rgArray[$i]['account_code'] == 'rm00t' && $rgArray[$i]['status'] == 'y') {
					$strAccountCode = '예매 완료';
					$strColor = 'blue';
				}

				if ($rgArray[$i]['account_code'] == 'rm00t' && $rgArray[$i]['status'] == 'n') {
					$strAccountCode = '예매 취소';
					$strColor = 'red';
				}

				if ($rgArray[$i]['account_code'] == 'em00t' || $rgArray[$i]['status'] == 'y') {
					$strAccountCode = '예매 완료';
					$strColor = 'blue';
				}

				if ($rgArray[$i]['account_code'] == 'rm00f' ) {
					$strAccountCode = '예매 취소';
					$strColor = 'red';
				}

				if ($rgArray[$i]['account_code'] == 'em00t' || $rgArray[$i]['status'] == 'n') {
					$strAccountCode = '예매 취소';
					$strColor = 'red';
				}

				if ($rgArray[$i]['account_code'] == 'em00f' ) {
					$strAccountCode = '예약 취소';
					$strColor = 'red';
				}

				if ($rgArray[$i]['status'] == 'w') {
					$strAccountCode = '예약 완료';
					$strColor = 'green';
				}

				/* 버스 변경 또는 취소 일시 */
				if($rgArray[$i]['change_day'] == '0000-00-00 00:00:00') {
					$strChangeDay = '-';
				}

				/* 출발 경로 */
				if ($rgLeaveLocation[$i] == 'seoul') {
					$strLeaveLocation = '서울';
				}

				if ($rgLeaveLocation[$i] == 'incheon') {
					$strLeaveLocation = '인천';
				}

				/* 도착 경로 */
				if ($rgArray[$i]['route'] == 'seoul') {
					$strArriveLocation = '서울';
				}

				if ($rgArray[$i]['route'] == 'incheon') {
					$strArriveLocation = '인천';
				}
			
				?>
				<tr>
					<td align = center><?=$i + 1?></td>
					<td align = center><?=$rgArray[$i]['trade_code']?></td>
					<td align = center style = color:<?=$strColor?>><?=$strAccountCode?></td> 
					<td align = center><?=$rgArray[$i]['bus_num']?></td> 
					<td align = center><?=$strClass?></td> 
					<td align = center><?=number_format($rgArray[$i]['price'])?></td> 
					<td align = center><?=$rgArray[$i]['seat_num']?></td> 
					<td align = center><?=$strLeaveLocation?></td> 
					<td align = center><?=$strArriveLocation?></td> 
					<td align = center><?=substr($rgArray[$i]['start_day'],0,16)?></td> 
					<td align = center><?=substr($rgArray[$i]['buy_day'],0,16)?></td> 
					<td align = center><?=$strChangeDay?></td> 

				</tr>
				<?
			}
			?>
		</table>
		<p>
			<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
			<input type = 'button' value = '로그아웃' onclick = "window.location= '../user/LogOut.php'">
		</p>
	</body>
</html>