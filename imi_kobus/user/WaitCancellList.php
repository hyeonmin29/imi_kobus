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

	$rgSetUserInfo = $CUserMile->fnSetUserInfo($rgUserInfo['user_num'], $rgUserInfo['user_name']);
	if ($rgSetUserInfo == false) {
		throw new exception('회원 번호 대입 오류');
	}

	$dtCheck = date('Y-m-d H:i:s', strtotime('+7 day'));
	
	# 예약 취소 내역 조회    and TIMESTAMPDIFF(day,'buy_day','" . $dtCheck ."') < 7
	$qryWaitCancellList = "
		SELECT trade_code, account_code, bus_num, bus_class, price, seat_num, route, trade_type, start_day, buy_day, change_day
		  FROM reservation
		 WHERE user_num =	 " . $rgUserInfo['user_num'] . "
		  AND status =		 'n'
		  AND account_code = 'em00f'
		  ORDER BY change_day
	";
	$rstWaitCancellList = mysqli_query($Conn, $qryWaitCancellList);
	if ($rstWaitCancellList == false) {
		throw new exception('예약 예매 내역 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstWaitCancellList) < 1) {
		throw new exception('예약 취소 내역이 없습니다.');
	}
	
	
	$rgArray = array();
	while ($rgWaitCancellList = mysqli_fetch_assoc($rstWaitCancellList)) {
		if ($rgWaitCancellList == false) {
			throw new exception('예약 얘매 배열 오류');
		}
		$rgArray[] = $rgWaitCancellList;
	}


	$nCount = count($rgArray);

} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}
	$strAlert = $e->getMessage();
	$strLocation = '../user/MainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
}
?>
<html>
	<body align = 'center'>
		<h2>예약 취소 내역</h2>
			<table border=1 align = 'center'>
				<tr><th>번호</th> <th>거래번호</th> <th>취소확인</th> <th>버스번호</th> <th>버스 등급</th> <th>금액</th> <th>도착지</th> <th>출발일시</th> <th>구매일시</th> <th>예약취소일시</th> </tr>
				<?php
					for($i = 0; $i < $nCount; $i++){

						$strBusClass = '일반';
						$strArriveLocation = '전주';
						
						if ($rgArray[$i]['account_code'] == 'em00f') {
							$strAccountCode = '예약 취소';
						}

						/* 버스 등급 */
						if($rgArray[$i]['bus_class'] == 'p') {
							$strBusClass = '프리미엄';
							
						}
						if($rgArray[$i]['bus_class'] == 'h') {
							$strBusClass = '우등';
							
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
						<td align = center><?=$strAccountCode?></td>
						<td align = center><?=$rgArray[$i]['bus_num']?></td>
						<td align = center><?=$strBusClass?></td>
						<td align = center><?=number_format($rgArray[$i]['price'])?></td>
						<td align = center><?=$strArriveLocation?></td>
						<td align = center><?=$rgArray[$i]['start_day']?></td>
						<td align = center><?=$rgArray[$i]['buy_day']?></td>
						<td align = center><?=$rgArray[$i]['change_day']?></td>
						</tr>
						<?
					}
				?>
			</table>
		<p>
			<div>
				<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
				<input type = 'button' value = '정보창으로 돌아가기' onclick = "window.location= '../user/MyInfo.php'">
				<input type = 'button' value = '로그아웃' onclick = "window.location= '../user/LogOut.php'">
			</div>
		</p>
	</body>
</html>


