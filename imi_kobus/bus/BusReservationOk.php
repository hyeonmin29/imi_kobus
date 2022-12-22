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
	
	/* 변수 초기화 */
	$nBusMoveInfoSeq = $_POST['bus_move_info_seq'];
	$strAccountCode = 'rm00t';
	$rgSeatNum = $_POST['seat_num'];
	$nCountSeat = count($_POST['seat_num']);
	$strTradeType = 'r';
	$All = '';
	$bCheck = 'reservation';
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

	# 같은 표를 이미 구매한 사람이 있으면 예약 불가
	for ($i = 0; $i < $nCountSeat; $i++) {
		$qryBusSeatCheck = "
			SELECT seat_num
			  FROM reservation
			 WHERE bus_move_info_seq = " . $nBusMoveInfoSeq . "
			   AND seat_num = " . $rgSeatNum[$i] . "
			   AND status = 'y'
		";  
		$rstBusSeatCheck = mysqli_query($Conn, $qryBusSeatCheck);
		if ($rstBusSeatCheck == false) {
			throw new exception('좌석 조회 쿼리 오류');
		}

		if (mysqli_num_rows($rstBusSeatCheck) > 0) {
			throw new exception('이미 예약된 좌석입니다.');
		}
	}
	
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

	# 총액 확인
	$nAllAmount = $CUserMile -> fnGetUserMile($All);
	if ($nAllAmount === false) {
		throw new exception ('예매 시 금액 확인 필요');
	}
	
	
	# 수수료
	$nCommission = $rgBusMoveInfo['price'] * 0.05;
	# 버스 금액
	$nPrice = $rgBusMoveInfo['price'] + $nCommission;
	# 버스 금액 * 예매 좌석 개수 
	$nAllPrice = $nPrice * $nCountSeat;
	
	
	# 보유하고있는 마일리지 금액이 결제 금액보다 적으면 예외처리
	if ($nAllAmount < $nAllPrice) {
		throw new exception('마일리지 금액이 부족합니다.');
	}	

	/* 트랜잭션 시작 */
	$bTrans_Check = $Conn->begin_transaction();
	if($bTrans_Check == false) {
		throw new DBexception('트랜잭션 실패');
	}

	/* 예매 프로세스 */
	# 중복클릭된 좌석 수만큼 반복
	for ($i = 0; $i < $nCountSeat; $i++) {
		# 거래코드
		$strTradeCode = date('YmdHis') . $strAccountCode . $rgUserInfo['user_num'] . $i;

		# reservation(예매)테이블 insert 쿼리
		$qryReservationInsert = "
			INSERT INTO reservation SET
				trade_code =		'" . $strTradeCode . "',
				user_num =			" . $rgUserInfo['user_num'] . ",
				bus_move_info_seq = " . $nBusMoveInfoSeq . ",
				status =			'y',
				account_code =		'" . $strAccountCode . "',
				bus_num =			" . $rgBusMoveInfo['bus_num'] . ",
				bus_class =			'" . $rgBusMoveInfo['bus_class'] . "',
				price =				" . $nPrice . ",
				seat_num =			'" . $rgSeatNum[$i] . "',
				route =				'" . $rgBusMoveInfo['bus_arrive_location'] . "',
				trade_type =		'" . $strTradeType . "',
				start_day =			'" . $rgBusMoveInfo['leave_day'] . "',
				buy_day =			NOW()
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
			 WHERE seq = " . $nBusMoveInfoSeq . "
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
			 WHERE seq = " . $nBusMoveInfoSeq . "
		";
		$rstBusMoveInfo = mysqli_query($Conn, $qryBusMoveInfo);
		if ($rstBusMoveInfo == false) {
			throw new DBexception('승객 수 업데이트 쿼리 오류');
		} 
		
		if (mysqli_affected_rows($Conn) < 1) {
			throw new DBexception('승객 수 업데이트 오류');
		}
		
		/* 예매로 인한 출금 프로세스 */
		# i가 0보다 클 때 nAllAmount는 업데이트 된 총액으로다시 조회해서 변경 금액 체크하기위해 조건문
		if ($i > 0) {
			$nAllAmount = $CUserMile -> fnGetUserMile($All);
			if ($nAllAmount === false) {
				throw new DBexception ('예매 시 금액 확인 필요');
			}
		}

		#user_mileage 예매로 인한 출금 업데이트
		$rstChangeUpdate = $CUserMile -> fnChargeUpdate($bCheck, $nPrice, $strAccountCode);		
		if ($rstChangeUpdate == false) {
			throw new DBexception('금액을 확인하세요 = 예매');
		}	
		
		#Accum_mile, Accum_mile_list 업데이트 및 인서트
		$rstAccumMile = $CUserMile -> fnAccumMile($bCheck, $strAccountCode, $nPrice, $strTradeCode, $nAllAmount, $strTradeType);
		if ($rstAccumMile == false) {
			throw new DBexception('금액을 확인하세요 - 적립');
		}
		
		#user_mile_change_list 인서트
		$rstChangeList = $CUserMile -> fnChangeList($bCheck, $nPrice, $strAccountCode, $strTradeCode);

		if ($rstChangeList == false) {
			throw new DBexception('변동 데이터 입력 오류');
		}
	
		#account_book 인서트
		$rstAccountBook = $CUserMile -> fnAccountBook($bCheck ,$strTradeCode, $strAccountCode, $strPaymentType, $nPrice, $nCommission, $strId, $strTradeType);
		if ($rstAccountBook == false) {
			throw new DBexception('장부 데이터 입력 오류');	
		}	
	}

	#커밋
	$bCommit = $Conn->Commit();
	if($bCommit == false){
		throw new DBexception('트랜잭션 실패');
	}

	$strAlert= '예매 되었습니다.';
	$strLocation = '../user/UserReserInfo.php';
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
