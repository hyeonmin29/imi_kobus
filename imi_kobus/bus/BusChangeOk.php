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

	/* DB 연결 시작 */
	$CDBConn = new DB;
	$Conn = $CDBConn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	# 현재시간 + 10분
	$dtWaitCheck = date("Y-m-d H:i:s", strtotime('+30 minutes'));

	/* 변수 초기화 */
	$nBusReserBeforeSeq = $_POST['before_reservation_seq'];	#reservation seq
	$nBusAfterSeq = $_POST['after_bus_move_info_seq'];	#bus_move_info seq
	$nSeatNum = $_POST['seat_num'];
	$strAccountCode = 'rm00f';
	$strTradeType = 'n';
	$bCheck = 'charge';			#취소하면 마일리지 충전
	$strUserNumColumn = array(
		'user_num',
		'user_name'
	);
	$strPaymentType = 'm';
	$strId = '';			#장부에서 세션ID값을 ID로 사용하기 위한 공백값
	$All = '';

	/* 1. 예매 변경 프로세스 - 취소) */
	# 현재 표가 취소된 표인지 조회
	$qrySelectReser = "
		SELECT status, bus_move_info_seq, start_day
		  FROM reservation
		 WHERE seq = " . $nBusReserBeforeSeq . "
	";
	$rstSelectReser = mysqli_query($Conn, $qrySelectReser);
	if ($rstSelectReser == false) {
		throw new exception('예매 정보 조회 쿼리 오류');
	}
	
	if (mysqli_num_rows($rstSelectReser) < 1) {
		throw new exception('예매 정보 조회 오류');
	}
	
	$rgSelectReser = mysqli_fetch_assoc($rstSelectReser);
	if ($rgSelectReser == false) {
		throw new exception('예매 정보 배열 오류');
	}
	
	# 취소된 정보이면 예외처리
	if ($rgSelectReser['status'] == 'n') {
		throw new exception('이미 취소된 예매정보입니다.');
	}

	if (strtotime($rgSelectReser['start_day']) < strtotime($dtWaitCheck)) {
		throw new exception('출발 30분 전 표는 변경 할 수 없습니다.');
	}

	$CUserClass = new UserClass($Conn);
	$CUserMile = new UserMile($Conn);

	# 회원번호 조회
	$rgUserInfo = $CUserClass -> fnUserInfo(implode(",", $strUserNumColumn), $strId);
	if($rgUserInfo == false) {
		throw new exception('회원 번호 조회 오류');
	}
	
	#거래코드
	$strTradeCode = date('YmdHis') . $strAccountCode . $rgUserInfo['user_num'];

	# 회원 정보 클래스에 입력
	$rgSetUserInfo = $CUserMile->fnSetUserInfo($rgUserInfo['user_num'], $rgUserInfo['user_name']);
	if ($rgSetUserInfo == false) {
		throw new exception('회원 번호 대입 오류');
	}

	# 이전 티켓 정보 조회 쿼리
	$qryBeforeSelect = "
		SELECT trade_code, user_num, bus_move_info_seq, status, account_code, bus_num, bus_class, price, seat_num, route, trade_type, start_day, buy_day
		  FROM reservation
		 WHERE seq = " . $nBusReserBeforeSeq . "
	";
	$rstBeforeSelect = mysqli_query($Conn, $qryBeforeSelect);
	if ($rstBeforeSelect == false) {
		throw new exception('이전 티켓 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstBeforeSelect) < 1) {
		throw new exception('이전 티켓 조회 오류');
	}

	$rgBeforeSelect = mysqli_fetch_assoc($rstBeforeSelect);
	if ($rgBeforeSelect == false) {
		throw new exception('이전 티켓 배열 오류');
	}

	/* 트랜잭션 시작 */
	$bTrans_Check = $Conn->begin_transaction();
	if($bTrans_Check == false) {
		throw new DBexception('트랜잭션 실패');
	}
	
	$qryBeforeTicketLock = "
		SELECT trade_code
		  FROM reservation
		 WHERE seq = " . $nBusReserBeforeSeq . "
		  FOR UPDATE
	";
	$rstBeforeTicketLock = mysqli_query($Conn, $qryBeforeTicketLock);
	if ($rstBeforeTicketLock == false) {
		throw new DBexception('취소티켓 락 쿼리 오류');
	}

	if (mysqli_num_rows($rstBeforeTicketLock) < 1) {
		throw new DBexception('취소티켓 락 오류');
	}
	
	$rgBeforeTicketTradeCode = mysqli_fetch_assoc($rstBeforeTicketLock);
	if ($rgBeforeTicketTradeCode == false) {
		throw new DBexception('거래번호 배열 오류');
	}

	# 취소 해야할 티켓 업데이트
	$qryUpdateBeforeTicket = "
		UPDATE reservation SET
			status =		'n',
			account_code =	'" . $strAccountCode . "',
			trade_type =	'" . $strTradeType . "',
			change_day =	NOW()
		WHERE seq =			" . $nBusReserBeforeSeq . "
	";
	$rstUpdateBeforeTicket = mysqli_query($Conn, $qryUpdateBeforeTicket);
	if ($rstUpdateBeforeTicket == false) {
		throw new DBexception('업데이트 쿼리 오류');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new DBexception('업데이트 오류');
	}

	# 취소 시 승객수 -1 해주기 위한 row 락
	$qryBusMoveInfoPeoPleLock = "
		SELECT seq 
		  FROM bus_move_info
		 WHERE seq = " . $rgBeforeSelect['bus_move_info_seq'] . "
		  FOR UPDATE
	";
	$rstBusMoveInfoPeoPleLock = mysqli_query($Conn, $qryBusMoveInfoPeoPleLock);
	if ($rstBusMoveInfoPeoPleLock == false) {
		throw new DBexception('버스 운행 정보 락 쿼리 오류');
	}

	if (mysqli_num_rows($rstBusMoveInfoPeoPleLock) < 1) {
		throw new DBexception('버스 운행 정보 락 오류');
	}

	# 버스 승객 수 업데이트
	$qryBusMoveInfoPeoPle = "
		UPDATE bus_move_info SET
		  people_cnt = people_cnt - 1
		 WHERE seq = " . $rgBeforeSelect['bus_move_info_seq'] . "
	";
	$rstBusMoveInfoPeoPle = mysqli_query($Conn, $qryBusMoveInfoPeoPle);
	if ($rstBusMoveInfoPeoPle == false) {
		throw new DBexception('버스 승객 수 업데이트 쿼리 오류');
	}
	
	if (mysqli_affected_rows($Conn) < 1) {
		throw new DBexception('버스 승객 수 업데이트 오류');
	}	
	
	# 장부에서 수수료 조회
	$qrySelectCommission = "
		SELECT commission
		  FROM account_book
		 WHERE trade_code = '" . $rgBeforeTicketTradeCode['trade_code'] . "'
	";
	$rstSelectCommission = mysqli_query($Conn, $qrySelectCommission);
	if ($rstSelectCommission == false) {
		throw new DBexception('수수료 조회 쿼리 오류');
	}
	
	if (mysqli_num_rows($rstSelectCommission) < 1) {
		throw new DBexception('수수료 조회 오류');
	}

	$rgSelectCommission = mysqli_fetch_assoc($rstSelectCommission);
	if ($rgSelectCommission == false) {
		throw new DBexception('수수료 배열 오류');
	}

	$nCommission = $rgSelectCommission['commission'];

	# 변동 전 총액 확인 
	$nAllAmount = $CUserMile -> fnGetUserMile($All);
	if ($nAllAmount === false) {
		throw new DBexception ('출금 시 금액 확인 필요');
	}
	#user_mileage 출금 업데이트
	$rstChangeUpdate = $CUserMile -> fnChargeUpdate($bCheck, $rgBeforeSelect['price'], $strAccountCode);
	if ($rstChangeUpdate == false) {
		throw new DBexception('금액을 확인하세요 = 예매 취소(변경)');
	}	

	#Accum_mile, Accum_mile_log 업데이트,인서트
	$rstAccumMile = $CUserMile -> fnAccumMile($bCheck, $strAccountCode, $rgBeforeSelect['price'], $strTradeCode, $nAllAmount, $strTradeType);
	if ($rstAccumMile == false) {
		throw new DBexception('금액을 확인하세요 - 적립');
	}
	
	#user_mile_change_list 인서트   ###보완
	$rstChangeList = $CUserMile -> fnChangeList($bCheck, $rgBeforeSelect['price'], $strAccountCode, $strTradeCode);
	if ($rstChangeList == false) {
		throw new DBexception('변동 데이터 입력 오류');
	}

	#account_book 인서트
	$rstAccountBook = $CUserMile -> fnAccountBook($bCheck ,$strTradeCode, $strAccountCode, $strPaymentType, $rgBeforeSelect['price'], $nCommission, $strId, $strTradeType);
	if ($rstAccountBook == false) {
		throw new DBexception('장부 데이터 입력 오류');
	}

	/* 2. 예매 변경 프로세스 - 예매 */
	# 예매 거래코드 변수 초기화
	$strAccountCode = 'rm00t';
	$strTradeCode = date('YmdHis') . $strAccountCode . $rgUserInfo['user_num'] . '0';
	$bCheckReser = 'reservation';
	$strTradeType = 'r';

	#변경하게 될 버스 정보 조회
	$qryAfterTicket = "
		SELECT bus_num, bus_class, seat_cnt, people_cnt, move_time, price, bus_arrive_location, leave_day, arrive_time
		  FROM bus_move_info
		 WHERE seq = " . $nBusAfterSeq . "
	";
	$rstAfterTicket = mysqli_query($Conn, $qryAfterTicket);
	if ($rstAfterTicket == false) {
		throw new DBexception('변경될 버스 정보 조회 쿼리 오류');
	}
	
	if (mysqli_num_rows($rstAfterTicket) < 1) {
		throw new DBexception('변경될 버스 정보 조회 오류');
	}

	$rgAfterTicket = mysqli_fetch_assoc($rstAfterTicket);
	if ($rgAfterTicket == false) {
		throw new DBexception('변경될 버스 배열 오류');
	}

	$nCommission = $rgAfterTicket['price'] * 0.05;
	$nPrice = $rgAfterTicket['price'] + $nCommission;


	# reservation(예매)테이블 insert 쿼리
	$qryReservationInsert = "
		insert into reservation set
			trade_code =		'" . $strTradeCode . "',
			user_num =			" . $rgUserInfo['user_num'] . ",
			bus_move_info_seq = " . $nBusAfterSeq . ",
			status =			'y',
			account_code =		'" . $strAccountCode . "',
			bus_num =			" . $rgAfterTicket['bus_num'] . ",
			bus_class =			'" . $rgAfterTicket['bus_class'] . "',
			price =				" . $nPrice . ",
			seat_num =			'" . $nSeatNum . "',
			route =				'" . $rgAfterTicket['bus_arrive_location'] . "',
			trade_type =		'" . $strTradeType . "',
			start_day =			'" . $rgAfterTicket['leave_day'] . "',
			buy_day =			now(),
			change_day =		now()
	";
	$rstReservationInsert = mysqli_query($Conn, $qryReservationInsert);
	if ($rstReservationInsert == false) {
		throw new DBexception('예매 쿼리 오류');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new DBexception('예매 정보 작성 오류');
	}

	# bus_move_info 락 쿼리
	$qryBusMoveInfoLock = "
		SELECT seq
		  FROM bus_move_info
		 WHERE seq = " . $nBusAfterSeq . "
		  FOR UPDATE
	";
	$rstBusMoveInfoLock = mysqli_query($Conn, $qryBusMoveInfoLock);
	if ($rstBusMoveInfoLock == false) {
		throw new DBexception('락 쿼리 오류');
	}

	if (mysqli_num_rows($rstBusMoveInfoLock) < 1) {
		throw new DBexception('락 오류');
	}

	# bus_move_info 승객 수 업데이트
	$qryBusMoveInfo = "
		UPDATE bus_move_info SET
		  people_cnt = people_cnt + 1
		 WHERE seq = " . $nBusAfterSeq . "
	";
	$rstBusMoveInfo = mysqli_query($Conn, $qryBusMoveInfo);
	if ($rstBusMoveInfo == false) {
		throw new DBexception('승객 수 업데이트 쿼리 오류');
	} 
	
	if (mysqli_affected_rows($Conn) < 1) {
		throw new DBexception('승객 수 업데이트 오류');
	}
	
		/* 예매로 인한 출금 프로세스 */
	$nAllAmount = $CUserMile -> fnGetUserMile($All);
	if ($nAllAmount === false) {
		throw new DBexception ('예매 시 금액 확인 필요');
	}

	# user_mileage 예매로 인한 출금 업데이트
	$rstChangeUpdate = $CUserMile -> fnChargeUpdate($bCheckReser, $nPrice, $strAccountCode);
	if ($rstChangeUpdate == false) {
		throw new DBexception('금액을 확인하세요 = 예매');
	}	
	
	#Accum_mile, Accum_mile_list 업데이트 및 인서트
	$rstAccumMile = $CUserMile -> fnAccumMile($bCheckReser, $strAccountCode, $nPrice, $strTradeCode, $nAllAmount, $strTradeType);
	if ($rstAccumMile == false) {
		throw new DBexception('금액을 확인하세요 - 적립');
	}
	
	#user_mile_change_list 인서트
	$rstChangeList = $CUserMile -> fnChangeList($bCheckReser, $nPrice, $strAccountCode, $strTradeCode);
	if ($rstChangeList == false) {
		throw new DBexception('변동 데이터 입력 오류');
	}

	#account_book 인서트
	$rstAccountBook = $CUserMile -> fnAccountBook($bCheckReser ,$strTradeCode, $strAccountCode, $strPaymentType, $nPrice, $nCommission, $strId, $strTradeType);
	#	var_dump($rstAccountBook);
	if ($rstAccountBook == false) {
		throw new DBexception('장부 데이터 입력 오류');	
	}	

	#커밋
	$bCommit = $Conn->Commit();
	if($bCommit == false){
		throw new DBexception('트랜잭션 실패');
	}

	$strAlert = '변경 완료';
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
	$strLocation = '../user/UserReserInfo.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);

} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}

	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../bus/BusChangeForm.php';
	fnAlert($strAlert,$strLocation);
}
?>
