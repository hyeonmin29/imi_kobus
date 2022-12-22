<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/DBConn.php');
session_start();
try {
	/* 세션 값 검사 */               
	if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
		throw new exception('로그인이 필요합니다.');
	}

	/* 포스트 값 검사 */               
	foreach ($_POST as $key=>$value) {
		if (!isset($_POST[$key]) || empty($_POST[$key])) {
			throw new exception('값이 입력되지 않았습니다.');
		}	
	}

	/* 변수 초기화 */
	$strUserPw = $_POST['user_pw'];
	$nUserPhone = $_POST['user_phone'];

	$CDBconn = new DB;
	$Conn = $CDBconn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	/* 회원 정보 조회 */
	$qrySelect = "
		SELECT user_id, user_pw, user_phone
		  FROM user_info 
		 WHERE user_id = '" . $_SESSION['user_id'] . "' 
		  AND user_pw = '" . $strUserPw . "' 
		  AND user_phone =  " . $nUserPhone . " 
	";

	$rstSelect = mysqli_query($Conn, $qrySelect);
	if ($rstSelect == false) {
		throw new exception('조회 오류');
	}

	if (mysqli_num_rows($rstSelect) < 1) {
		throw new exception('회원 조회 오류.');
	}
	
	/* 회원 일부 정보 남기고 업데이트 */
	$qryUpdate = "
		UPDATE user_info SET 
			user_pw =			 '', 
			user_phone =		 0, 
			user_account =		 '', 
			user_account_num =	 0,
			last_login_day =	 NOW(),
			status =			 'y'
		 WHERE user_id = '" . $_SESSION['user_id'] . "'
	";
	$rstUpdate = mysqli_query($Conn, $qryUpdate);
	if ($rstUpdate == false){
		throw new exception('업데이트 쿼리 실패');
	}
	
	if(mysqli_affected_rows($Conn) < 1){
		throw new exception('업데이트 실패');
	}

	session_destroy();
	$strAlert = '탈퇴완료.';
	$strLocation = 'MainPage.php';
	fnAlert($strAlert,$strLocation);

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