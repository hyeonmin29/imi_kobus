<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/DBConn.php');
include('../function/MileFunction.php');
try {
	/* 포스트 값 검사 */               
	foreach ($_POST as $key=>$value) {
		if (!isset($_POST[$key]) || empty($_POST[$key])) {
			throw new exception('값이 입력되지 않았습니다.');
		}	
	}
	
	if($_POST['user_birth'] > date('Y-m-d')){
		throw new exception('생년월일 확인하세요');
	}

	/* 변수 초기화 */
	$strUserId = $_POST['user_id'];
	$strUserPw = $_POST['user_pw'];
	$strUserName = $_POST['user_name'];
	$nUserPhone = $_POST['user_phone'];
	$strUserBirth = $_POST['user_birth'];
	$strUserEmail = $_POST['user_email'];
	$strAccount = $_POST['user_account'];
	$nAccountNum = $_POST['user_account_num'];
	
	/* DB 연결 */
	$CDBconn = new DB;
	$Conn = $CDBconn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	/* 중복 쿼리 조회 */
	$qrySelect = "
		SELECT user_id 
		  FROM user_info 
		 WHERE user_id = '" .$strUserId. "'
	";
	$rstSelect = mysqli_query($Conn, $qrySelect);
	if ($rstSelect == false) {
		throw new exception('조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstSelect) > 0) {
		throw new exception('중복된 아이디입니다.');
	}

	/* 유저 정보 입력 쿼리 */
	$qryInsert = "
		INSERT INTO user_info SET
			user_id =			'" . $strUserId . "',
			user_pw =			'" . $strUserPw . "',
			user_name =			'" . $strUserName . "',
			user_phone =		" . $nUserPhone . ",
			user_email =		'" . $strUserEmail . "',
			user_birth =		'" . $strUserBirth . "',
			user_account =		'" . $strAccount ."',
			user_account_num =	" . $nAccountNum . ",
			reg_day =			NOW()
	";
	$rstInsert = mysqli_query($Conn,$qryInsert);
	if ($rstInsert == false) {
		throw new exception('정보 입력 오류1');
	}
	
	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('정보 입력 오류2');
	}

	$strAlert = '가입을 축하합니다.';
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