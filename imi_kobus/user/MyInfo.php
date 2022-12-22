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
	
	$All = '';
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

	$nAllAmount = $CUserMile -> fnGetUserMile($All);
	if ($nAllAmount === false) {
		throw new exception ('금액 조회 오류');
	}

} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../user/MainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
}
?>
<html>
	<body align = 'center'>
		<h2>보유 마일리지</h2>
		<div>
			<p>아이디 : <?=$_SESSION['user_id']?></p>
			<p>이름 :	<?=$rgUserInfo['user_name']?></p>
			<p>보유 마일리지 : <?=number_format($nAllAmount)?></p>
			<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
			<input type = 'button' value = '로그아웃' onclick = "window.location= '../user/LogOut.php'">
			<input type = 'button' value = '증액 및 삭감 내역' onclick = "window.location= '../user/MileChangeLog.php'">
			<input type = 'button' value = '증액 내역' onclick = "window.location= '../user/ChargeLog.php'">
			<input type = 'button' value = '삭감 내역' onclick = "window.location= '../user/WithdrawLog.php'">
		</div>
	</body>
</html>

