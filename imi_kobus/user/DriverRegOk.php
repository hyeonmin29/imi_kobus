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

	/* 변수 초기화 */
	$strDriverId = $_POST['driver_id'];
	$strDriverPw = $_POST['driver_pw'];
	$strDriverName = $_POST['driver_name'];
	$nDriverPhone = $_POST['driver_phone'];
	$strDriverEmail = $_POST['driver_email'];
	$strDriverLocation = $_POST['driver_location'];

	
	/* DB 연결 */
	$CDBconn = new DB;
	$Conn = $CDBconn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	/* 중복 쿼리 조회 */
	$qrySelect = "
		SELECT driver_id 
		  FROM driver_info 
		 WHERE driver_id = '" .$strDriverId. "'
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
		INSERT INTO driver_info SET
			driver_id =			'" . $strDriverId . "',
			driver_pw =			'" . $strDriverPw . "',
			driver_name =		'" . $strDriverName . "',
			driver_phone =		" . $nDriverPhone . ",
			driver_email =		'" . $strDriverEmail . "',
			driver_location =	'" . $strDriverLocation . "',
			driver_check =		'n',
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