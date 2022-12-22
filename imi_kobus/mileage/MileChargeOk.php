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

	/* 포스트 값 검사 */               
	foreach ($_POST as $key=>$value) {
		if (empty($_POST[$key])) {
			throw new exception('값이 입력되지 않았습니다.');
		}	
	}
	
	/* 변수 초기화 */
	$nMileCharge = (int)floor($_POST['mile_charge'] * 0.9);
	$nCommission = (int)floor($_POST['mile_charge'] * 0.1);
	$strAccountCode = $_POST['account_code'];
	$strColumn = 'seq';
	$strTable = 'user_mileage';
	$bCheck= 'charge';		#충전페이지에서 true
	$All = '';
	$strId = '';
	$strTradeType = 'c';
	$strUserNumColumn = array(
		'user_num',
		'user_name'
	);

	$strId = ''; #장부에서 세션 아이디를 사용하기 위해 공백값

	$strTradeType = substr($strAccountCode,0,1);	#거래타입  c=충전 e=예약 r=예매 w=출금
	$strPaymentType = substr($strAccountCode,1,3);	#결제방법	a=계좌 m=마일리지 p=핸드폰

	/* DB 연결 시작 */
	$CDBConn = new DB;
	$Conn = $CDBConn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	$CUserClass = new UserClass($Conn);
	$CUserMile = new UserMile($Conn);

	#회원번호 조회
	$rgUserInfo = $CUserClass -> fnUserInfo(implode(",", $strUserNumColumn), $strId);
	if($rgUserInfo == false) {
		throw new exception('회원 번호 조회 오류');
	}

	$rgSetUserInfo = $CUserMile->fnSetUserInfo($rgUserInfo['user_num'], $rgUserInfo['user_name']);
	if ($rgSetUserInfo == false) {
		throw new exception('회원 정보 입력 실패');
	}

	#거래코드
	$strTradeCode = date('YmdHis') . $_POST['account_code'] . $rgUserInfo['user_num'];

	# 총 금액 확인 -> 공백을 넣으면 모든 값 출력 / 값이 없거나 등록되어있지 않으면 0 출력
	$nAllAmount = $CUserMile -> fnGetUserMile($All);
	if ($nAllAmount === false) {
		throw new exception('총액 조회 실패');
	}

	/* 트랜잭션 시작 */
	$bTrans_Check=$Conn->begin_transaction();
	if($bTrans_Check == false) {
		throw new DBexception('트랜잭션 실패');
	}

	#user_mileage 충전 업데이트
	$rstChangeUpdate = $CUserMile -> fnChargeUpdate($bCheck, $nMileCharge, $strAccountCode);
	if ($rstChangeUpdate == false) {
		throw new DBexception('금액을 확인하세요 = 출금');
	}	

	#Accum_mile, Accum_mile_log 업데이트,인서트
	$rstAccumMile = $CUserMile -> fnAccumMile($bCheck, $strAccountCode, $nMileCharge, $strTradeCode, $nAllAmount, $strTradeType);
	if ($rstAccumMile == false) {
		throw new DBexception('금액을 확인하세요 - 적립');
	}

	#user_mile_change_list 인서트   ###보완
	$rstChangeList = $CUserMile -> fnChangeList($bCheck, $nMileCharge, $strAccountCode, $strTradeCode);
	if ($rstChangeList == false) {
		throw new DBexception('변동 데이터 입력 오류');
	}

	#account_book 인서트
	$rstAccountBook = $CUserMile -> fnAccountBook($bCheck ,$strTradeCode, $strAccountCode, $strPaymentType, $nMileCharge, $nCommission, $strId, $strTradeType);
	if ($rstAccountBook == false) {
		throw new DBexception('장부 데이터 입력 오류');
	}

	#커밋
	$bCommit = $Conn->Commit();
	if($bCommit == false){
		throw new DBexception('트랜잭션 실패');
	}

	$strAlert = '마일리지 충전 완료';
	$strLocation = '../user/MainPage.php';
	fnAlert($strAlert,$strLocation);

} catch(DBexception $e) {
	if ($Conn == true) {
		if ($bTrans_Check == true) {
			$Conn->rollback();
			$Conn->Commit();
			unset($bTrans_Check);
		}
		mysqli_close($Conn);
		unset($Conn);
	}
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../mileage/MileChargeForm.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}

	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../mileage/MileChargeForm.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
}
?>