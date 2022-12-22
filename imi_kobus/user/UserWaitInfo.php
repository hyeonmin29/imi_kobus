<?
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
session_start();
include('../function/DBConn.php');
include('../function/UserFunction.php');
try{
	/* 세션 값 검사 */               
	if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
		throw new exception('로그인이 필요합니다.');
	}

	/* 포스트 값 검사 */               
	foreach ($_POST as $key=>$value) {
		if (!isset($_POST[$key]) || empty($_POST[$key])) {
			throw new exception('아이디 및 비밀번호를 확인하세요');
		}	
	}
	
	/* 변수 초기화 */
	$strId = '';				# 클래스에서 세션값을 사용하기 위한 공백값 입력
	$strUserNumColumn = array(
		'user_num'
	);

	/* DB 연결 */
	$CDBconn = new DB;
	$Conn = $CDBconn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	$CUserClass = new UserClass($Conn);

	#회원번호 조회
	$rgUserInfo = $CUserClass -> fnUserInfo($strUserNumColumn[0], $strId);
	if($rgUserInfo == false) {
		throw new exception('회원 번호 조회 오류');
	}
	
	# 예약 내역 조회 쿼리
	$qryWaitSelect = "
		SELECT seq, trade_code, bus_num, bus_class, price, route, start_day, buy_day, status
		  FROM reservation
		 WHERE user_num = " . $rgUserInfo['user_num'] . "
		   AND seat_num = 0
	";
	$rstWaitSelect = mysqli_query($Conn, $qryWaitSelect);
	if ($rstWaitSelect == false) {
		throw new exception('예약 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstWaitSelect) < 1) {
		echo '예약 내역이 없습니다.';
	}
	
	$rgArray = array();
	while ($rgWaitSelect = mysqli_fetch_array($rstWaitSelect)){
		if ($rgWaitSelect == false) {
			throw new exception('예약 배열 오류');
		}
		$rgArray[] = $rgWaitSelect;
		$rgSeq[] =		 $rgWaitSelect['seq'];
		$rgTradeCode[] = $rgWaitSelect['trade_code']; 
		$rgBusNum[] =	 $rgWaitSelect['bus_num']; 
		$rgBusClass[] =  $rgWaitSelect['bus_class']; 
		$rgPrice[] =	 $rgWaitSelect['price']; 
		$rgRoute[] =	 $rgWaitSelect['route']; 
		$rgStartDay[] =  $rgWaitSelect['start_day']; 
		$rgBuyDay[] =	 $rgWaitSelect['buy_day']; 
	}

	$nCount = count($rgArray);
	
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
	<h3>예약 확인 및 예약 취소 폼</h3>
	<body align = center>
		<form action = '../bus/BusWaitCancellOk.php' method = 'POST'>
			<table border=1 align = 'center'>
				<tr><th>번호</th> <th>거래번호</th> <th>버스번호</th> <th>예매 상태</th> <th>버스 등급</th> <th>금액</th> <th>도착지</th> <th>출발 일시</th> <th>예약 일시</th><th>선택</th></tr>
				<?php
				for ($i = 0; $i < $nCount; $i++) {
					$strDisabled = '';
					$strClass = '일반';
					$strRoute = '전주';
					$strColor = 'blue';
					$strStatus = '예약';
					$dtDay = date('Y-m-d H:i:s', strtotime('+10 minutes'));

					# 예약이 취소 되었거나 현재 시간보다 10분 전에는 예약이 자동 취소
					if ($rgArray[$i]['status'] == 'n' || strtotime($dtDay) > strtotime($rgStartDay[$i])) {
						$strStatus = '예약취소';
						$strDisabled = 'disabled';
						$strColor = 'red';
					}
					
					if ($rgArray[$i]['bus_class'] == 'p') {
						$strClass = '프리미엄';
					}

					if ($rgArray[$i]['bus_class'] == 'h') {
						$strClass = '우등';
					}

					if($rgArray[$i]['route'] == 'seoul') {
						$strRoute = '서울';
					}
					
					if($rgArray[$i]['route'] == 'incheon') {
						$strRoute = '인천';
					}
					?>
					<tr>
						<td align = center><?=$i + 1?></td>
						<td align = center><?=$rgTradeCode[$i]?></td>
						<td align = center><?=$rgBusNum[$i]?></td>
						<td align = center style = 'color:<?=$strColor?>'><?=$strStatus?></td>
						<td align = center><?=$strClass?></td>
						<td align = center><?=number_format($rgPrice[$i])?></td>
						<td align = center><?=$strRoute?></td>
						<td align = center><?=$rgStartDay[$i]?></td>
						<td align = center><?=$rgBuyDay[$i]?></td>
						<td align = center><input type = 'checkbox' name = 'seq[]' value = '<?=$rgSeq[$i]?>' <?=$strDisabled?>></td>
					</tr>
					<?
				}
				?> 
			</table>
			<p>
				<input type = 'submit' value = '예약 취소'>
				<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
			</p>
		</form>
	</body>
</html>