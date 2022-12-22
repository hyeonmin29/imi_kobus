<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/DBConn.php');
include('../function/UserFunction.php');
try{
	/* 포스트 값 검사 */               
	foreach ($_POST as $key=>$value) {
		if (!isset($_POST[$key]) || empty($_POST[$key])) {
			throw new exception('아이디 및 비밀번호를 확인하세요');
		}	
	}
	
	/* 변수 초기화 */
	$strUserId = $_POST['user_id'];
	$strUserPw = $_POST['user_pw'];
	$strColumn = array(
		'user_num',
		'user_pw'
	);

	/* DB 연결 */
	$CDBconn = new DB;
	$Conn = $CDBconn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	$CUserClass = new UserClass($Conn);

	/* 유저 정보 가져오는 함수 */
	$rgUserInfo = $CUserClass->fnUserLogin($strUserId, implode(",", $strColumn));
	if ($rgUserInfo == false){
		throw new exception('아이디 오류');
	}
	
	if ($rgUserInfo['user_pw'] !== $strUserPw){
		$bFlag = 'f';

		/* 로그인 리스트 값 등록 */
		$qryList = "
			INSERT INTO login_list SET
				user_num =		" . $rgUserInfo['user_num'] . ",
				success_flag =	'" . $bFlag . "',
				login_day =		NOW()
		";
		$rstList = mysqli_query($Conn, $qryList);
		if ($rstList == false) {
			throw new exception('로그인 리스트 쿼리 오류');
		}

		if (mysqli_affected_rows($Conn) < 1){
			throw new exception('로그인 리스트 데이터 입력 오류');
		}
		throw new exception('로그인 실패 비밀번호 오류');
	} else {
		$bFlag = 't';
		/* 로그인 리스트 값 등록 */
		$qryList = "
			INSERT INTO login_list SET
				user_num =		" . $rgUserInfo['user_num'] . ",
				success_flag =	'" . $bFlag . "',
				login_day =		NOW()
		";
		$rstList = mysqli_query($Conn, $qryList);
		if ($rstList == false) {
			throw new exception('로그인 리스트 쿼리 오류');
		}

		if (mysqli_affected_rows($Conn) < 1){
			throw new exception('로그인 리스트 데이터 입력 오류');
		}

		$qryLastLoginDay = "
			UPDATE user_info SET
			  last_login_day = now()
			 WHERE user_id = '" . $strUserId . "'
		";
		$rstLastLoginDay = mysqli_query($Conn, $qryLastLoginDay);
		if ($rstLastLoginDay == false) {
			throw new exception('마지막 로그인 업데이트 쿼리 오류');
		}

		if (mysqli_affected_rows($Conn) < 1) {
			throw new exception('마지막 로그인 업데이트 오류');
		}
	}

	/* 세션 저장 */
	session_start();
	$_SESSION['user_id'] = $strUserId;

	$strAlert = '로그인 성공';
	$strLocation = 'MainPage.php';
	fnAlert($strAlert,$strLocation);

} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = 'LoginForm.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
}
?>