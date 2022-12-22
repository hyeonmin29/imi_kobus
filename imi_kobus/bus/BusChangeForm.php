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

	/* 변수 초기화 */
	$strId = '';
	$strUserNumColumn = array(
		'user_num',
		'user_name'
	);

	# 회원번호 조회
	$rgUserInfo = $CUserClass -> fnUserInfo(implode(",", $strUserNumColumn),$strId);
	if($rgUserInfo == false) {
		throw new exception('회원 번호 조회 오류');
	}

	# 회원 정보 클래스에 입력
	$rgSetUserInfo = $CUserMile->fnSetUserInfo($rgUserInfo['user_num'], $rgUserInfo['user_name']);
	if ($rgSetUserInfo == false) {
		throw new exception('회원 번호 대입 오류');
	}

	# 예매된 버스 정보 조회
	$qrySelectBus = "
		SELECT seq, trade_code, bus_num, bus_move_info_seq, bus_class, price, seat_num, route, start_day, buy_day
		  FROM reservation
		 WHERE user_num = " . $rgUserInfo['user_num'] . "
		   AND status = 'y'
		  ORDER BY buy_day DESC
	";
	$rstSelectBus = mysqli_query($Conn, $qrySelectBus);
	if ($rstSelectBus == false) {
		throw new exception('예매 버스 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstSelectBus) < 1) {
		echo '예매된 버스 정보가 없습니다.';		
	}

	$rgArray = array();
	while ($rgSelectBus = mysqli_fetch_assoc($rstSelectBus)) {
		if ($rgSelectBus == false) {
			throw new exception('배열 오류');
		}
		$rgArray[] = $rgSelectBus;
	}

	$nCount = count($rgArray);
	$rgBusLeaveLocation = array();

	for ($i = 0; $i < $nCount; $i++) {
		$qryStartLocation = "
			SELECT bus_leave_location
			  FROM bus_move_info
			 WHERE seq = " . $rgArray[$i]['bus_move_info_seq'] . "
		";
		$rstStartLocation = mysqli_query($Conn, $qryStartLocation);
		if ($rstStartLocation == false) {
			throw new exception('출발지역 조회 쿼리 오류');
		}

		if (mysqli_num_rows($rstStartLocation) < 1) {
			throw new exception('출발 지역 조회 오류');
		}
		$rgStartLocation = mysqli_fetch_assoc($rstStartLocation);
		if ($rgStartLocation == false) {
			throw new exception('배열 오류');
		}
		$rgBusLeaveLocation[] = $rgStartLocation['bus_leave_location'];
	}

} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}

	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../user/MainPage.php';
	fnAlert($strAlert,$strLocation);
}
?>
<html>
	<body align = 'center'>
		<h2>예매 버스 변경</h2>
		<p>예매 변경은 출발 30분 전 버스만 가능합니다.</p>
		<p>
			변경 수수료<br>
			30분후 ~ 1시간전 : 50% <br> 
			1시간후 ~ 2시간전 : 30% <br>
			2시간후 ~	무료
		</p>
		<form action = '../bus/BusChange.php' method = 'POST'>
			<p>
				<table border=1 align = 'center'>
					<tr><th>번호</th> <th>거래번호</th> <th>버스 번호</th> <th>버스 등급</th> <th>좌석번호</th> <th>금액</th> <th>출발지</th> <th>도착지</th> <th>출발일시</th> <th>구매일시</th> <th>선택</th></tr>

					<?php
						for($i=0; $i<$nCount; $i++){

							$strDisabled = '';
							$strBusClass = '일반';
							$strLeaveLocation = '전주';
							$strArriveLocation = '전주';

							# 30분 전은 예매 변경 체크박스 선택 불가능
							if(strtotime(date('Y-m-d H:i:s', strtotime('+30 minutes'))) > strtotime($rgArray[$i]['start_day'])) {
								$strDisabled = 'disabled';
							}

							if($rgArray[$i]['bus_class'] == 'p') {
								$strBusClass = '프리미엄';
							}

							if($rgArray[$i]['bus_class'] == 'h') {
								$strBusClass = '우등';
							}

							/* 출발 경로 */
							if ($rgBusLeaveLocation[$i] == 'seoul') {
								$strLeaveLocation = '서울';
							}
							if ($rgBusLeaveLocation[$i] == 'incheon') {
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
							<td align = center><?= $i+1 ?></td>
							<td align = center><?= $rgArray[$i]['trade_code']?></td>
							<td align = center><?= $rgArray[$i]['bus_num']?></td>
							<td align = center><?= $strBusClass?></td>
							<td align = center><?= $rgArray[$i]['seat_num']?></td>
							<td align = center><?= number_format($rgArray[$i]['price'])?></td>
							<td align = center><?= $strLeaveLocation?></td>
							<td align = center><?= $strArriveLocation?></td>
							<td align = center><?= $rgArray[$i]['start_day']?></td>
							<td align = center><?= $rgArray[$i]['buy_day']?></td>
							<td align = center><input type = 'radio' name = 'reservation_seq' value = '<?=$rgArray[$i]['seq']?>' <?= $strDisabled?>></td>
							</tr>
							<?php
						}
					?>
				</table>
			</p>
			<input type = 'submit' value = '예매 변경'>
			<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
			<input type = 'button' value = '로그아웃' onclick = "window.location= '../user/LogOut.php'">
		</form>
	</body>
</html>


