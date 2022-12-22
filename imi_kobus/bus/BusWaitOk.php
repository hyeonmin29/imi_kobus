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
	
	if (empty($_POST['bus_move_info_seq'])) {
		throw new exception('값이 없습니다.');
	}

	/* DB 연결 시작 */
	$CDBConn = new DB;
	$Conn = $CDBConn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}
	
	/* 변수 초기화 */
	$nBusMoveInfoSeq = $_POST['bus_move_info_seq'];
	$strAccountCode = 'em00t';
	$strTradeType = 'e';
	$strAllAmount = '';			# 클래스에서 총액을 가져오기 위한 공백값
	$bCheck = 'Wait';
	$strUserNumColumn = array(
		'user_num',
		'user_name'
	);
	$strPaymentType = 'm';
	$strId = '';			#장부에서 세션ID값을 ID로 사용하기 위한 공백값

	$CUserClass = new UserClass($Conn);
	$CUserMile = new UserMile($Conn);
		
	# 회원번호 조회
	$rgUserInfo = $CUserClass -> fnUserInfo(implode(",", $strUserNumColumn), $strId);
	if($rgUserInfo == false) {
		throw new exception('회원 번호 조회 오류');
	}

	# 회원 정보 클래스에 입력
	$rgSetUserInfo = $CUserMile->fnSetUserInfo($rgUserInfo['user_num'], $rgUserInfo['user_name']);
	if ($rgSetUserInfo == false) {
		throw new exception('회원 번호 대입 오류');
	}

	# 총액 확인
	$nAllAmount = $CUserMile -> fnGetUserMile($strAllAmount);
	if ($nAllAmount === false) {
		throw new exception ('예약 시 금액 확인 필요');
	}
	
	# 거래코드
	$strTradeCode = date('YmdHis') . $strAccountCode . $rgUserInfo['user_num'];

	# 시퀀스 버스 정보 조회 쿼리
	$qryBusMoveInfo = "
		SELECT bus_num, bus_class, price, bus_arrive_location, leave_day
		  FROM bus_move_info
		 WHERE seq = " . $nBusMoveInfoSeq . "
	";
	$rstBusMoveInfo = mysqli_query($Conn, $qryBusMoveInfo);
	if ($rstBusMoveInfo == false) {
		throw new exception('버스 운행 정보 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstBusMoveInfo) < 1) {
		throw new exception('버스 운행 정보 조회 오류');
	}

	$rgBusMoveInfo = mysqli_fetch_assoc($rstBusMoveInfo);
	if ($rgBusMoveInfo == false) {
		throw new exception('버스 운행 정보 배열 오류');
	}
	
	$nCommission = $rgBusMoveInfo['price'] * 0.05;
	$nPrice = $rgBusMoveInfo['price'] + $nCommission;
	
	/* 예약 전 필요한 돈보다 적으면 예약 불가*/
	if ($nAllAmount < $nPrice) {
		throw new exception('예약하기 전 금액을 확인하세요');
	}
	
	# 예약 예매 정보 인서트 쿼리
	$qryInsertReserWait = "
		INSERT INTO reservation SET
			trade_code =		'" . $strTradeCode . "',
			user_num =			" . $rgUserInfo['user_num'] . ",
			bus_move_info_seq = " . $nBusMoveInfoSeq . ",
			status =			'w',
			account_code =		'" . $strAccountCode . "',
			bus_num =			" . $rgBusMoveInfo['bus_num'] . ",
			bus_class =			'" . $rgBusMoveInfo['bus_class'] . "',
			price =				" . $rgBusMoveInfo['price'] . ",
			seat_num =			'',
			route =				'" . $rgBusMoveInfo['bus_arrive_location'] . "',
			trade_type =		'" . $strTradeType . "',
			start_day =			'" . $rgBusMoveInfo['leave_day'] . "',
			buy_day =			NOW()
	";
	$rstInsertReserWait = mysqli_query($Conn, $qryInsertReserWait);
	if ($rstInsertReserWait == false) {
		throw new exception('예약 예매 인서트 쿼리 오류');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new exception('예약 예매 인서트 오류');
	}

	$strAlert= '예매 완료';
	$strLocation = '../bus/BusSelectForm.php';
	/* 에러발생 함수 */
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
	$strLocation = '../bus/BusSelectForm.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);

} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}

	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../bus/BusSelectForm.php';
	fnAlert($strAlert,$strLocation);
}
?>
