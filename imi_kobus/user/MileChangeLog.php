<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/DBConn.php');
include('../function/MileFunction.php');
include('../function/UserFunction.php');
session_start();
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
	
	$strAllAmount = '';
	$CUserClass = new UserClass($Conn);
	$CUserMile = new UserMile($Conn);
	$strUserNumColumn = array(
		'user_num',
		'user_name'
	);
	$strId = '';

	#회원번호 조회
	$rgUserInfo = $CUserClass -> fnUserInfo(implode(",", $strUserNumColumn), $strId);
	if($rgUserInfo == false) {
		throw new exception('회원 번호 조회 오류');
	}

	$rgSetUserInfo = $CUserMile->fnSetUserInfo($rgUserInfo['user_num'], $rgUserInfo['user_name']);
	if ($rgSetUserInfo == false) {
		throw new exception('회원 번호 대입 오류');
	}

	$nAllAmount = $CUserMile -> fnGetUserMile($strAllAmount);
	if ($nAllAmount === false) {
		throw new exception ('금액 조회 오류 필요');
	}

	$qryChangeLog = "
		SELECT trade_type, before_mile, change_mile, after_mile, reg_day
		  FROM account_book
		 WHERE user_num = " . $rgUserInfo['user_num'] . "
		  ORDER BY reg_day DESC
	";
	$rstChangeLog = mysqli_query($Conn, $qryChangeLog);
	if ($rstChangeLog == false) {
		throw new exception('변동 내역 확인 쿼리 오류');
	}

	if (mysqli_num_rows($rstChangeLog) < 1) {
		echo '변동 내역이 없습니다.';
	}
	
	$rgArray = array();
	while ($rgChangeLog = mysqli_fetch_assoc($rstChangeLog)) {
		if ($rgChangeLog == false) {
			throw new exception('변동 내역 배열 입력 실패');
		}
		$rgArray[] = $rgChangeLog;
	}	

	$nAmountCount = count($rgArray);

} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}
	$strAlert = $e->getMessage();
	$strLocation = '../user/MyInfo.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
}
?>
<html>
	<body align = 'center'>
		<h2>마일리지 변동 내역</h2>

			<table border=1 align = 'center'>
				<tr><th>번호</th> <th>변동 내역</th> <th>변동 전 금액</th> <th>변동 금액</th> <th>변동 후 금액</th> <th>일시</th></tr>
				<p>보유 마일리지 : <?=number_format($nAllAmount)?><p>
				<?php
					for($i=0; $i<$nAmountCount; $i++){
						$strColor = 'blue';
						$strType= '충전';

						if ($rgArray[$i]['trade_type'] == 'w') {
							$strType= '출금';
							$strColor = 'red';
						}
						if ($rgArray[$i]['trade_type'] == 'r') {
							$strType= '예매';
							$strColor = 'red';
						}
						if ($rgArray[$i]['trade_type'] == 'n') {
							$strType= '예매취소';
							$strColor = 'blue';
						}
						if ($rgArray[$i]['trade_type'] == 'f') {
							$strType= '예약취소';
							$strColor = 'blue';
						}

						?>
						<tr>
						<td align = center><?=$i + 1?></td>
						<td align = center style = "color : <?=$strColor?>"><?=$strType?></td>
						<td align = center><?=number_format($rgArray[$i]['before_mile'])?></td>
						<td align = center><?=number_format($rgArray[$i]['change_mile'])?></td>
						<td align = center><?=number_format($rgArray[$i]['after_mile'])?></td>
						<td align = center><?=$rgArray[$i]['reg_day']?></td>
						</tr>
						<?php
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


