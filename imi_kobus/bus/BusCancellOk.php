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
	
	/* POST값 검사 */
	if (empty($_POST['reservation_seq'])) {
		throw new exception('값이 입력되지 않았습니다.');
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
	$nReserSeq = $_POST['reservation_seq'];
	$strAccountCode = 'rm00f';
	$strTradeType = 'r';
	$All = '';
	$bCheck = 'charge'; #취소하면 마일리지 충전
	$strUserNumColumn = array(
		'user_num',
		'user_name'
	);
	$strPaymentType = 'm';
	$strId = '';			#장부에서 세션ID값을 ID로 사용하기 위한 공백값

	# 현재 표가 취소된 표인지 조회
	$qrySelectReser = "
		select status, bus_move_info_seq, start_day
		from reservation
		where seq = " . $nReserSeq . "
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
		throw new exception('출발 30분 전 표는 취소할 수 없습니다.');
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

	/* 트랜잭션 시작 */
	$bTrans_Check = $Conn->begin_transaction();
	if($bTrans_Check == false) {
		throw new DBexception('트랜잭션 실패');
	}
	
	# 취소 시 승객수 -1 해주기 위한 row 락
	$qryBusMoveInfoPeoPleLock = "
		SELECT seq 
		  FROM bus_move_info
		 WHERE seq = " . $rgSelectReser['bus_move_info_seq'] . "
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
		 WHERE seq = " . $rgSelectReser['bus_move_info_seq'] . "
	";
	$rstBusMoveInfoPeoPle = mysqli_query($Conn, $qryBusMoveInfoPeoPle);
	if ($rstBusMoveInfoPeoPle == false) {
		throw new DBexception('버스 승객 수 업데이트 쿼리 오류');
	}
	
	if (mysqli_affected_rows($Conn) < 1) {
		throw new DBexception('버스 승객 수 업데이트 오류');
	}	

	# 예매 테이블에서 취소할 정보 조회
	$qryReservationCancell = "
		SELECT trade_code, bus_num, bus_class, price, seat_num, route, start_day, buy_day
		  FROM reservation
		 WHERE seq = " . $nReserSeq . "
	";
	$rstReservationCancell = mysqli_query($Conn, $qryReservationCancell);
	if ($rstReservationCancell == false) {
		throw new DBexception('예매 상태 조회 쿼리 오류');
	}
	
	if (mysqli_num_rows($rstReservationCancell) < 1) {
		throw new DBexception('예매 상태 조회 오류');
	}

	$rgReservationCancell = mysqli_fetch_assoc($rstReservationCancell);
	if ($rgReservationCancell == false) {
		throw new DBexception('취소 표 정보 배열 오류');
	}

	# 거래번호로 장부에서 수수료 조회 쿼리
	$qrySelectCommission = "
		SELECT commission
		  FROM account_book
		 WHERE trade_code = '" . $rgReservationCancell['trade_code'] ."'
	";
	$rstSelectCommission = mysqli_query($Conn, $qrySelectCommission);
	if ($rstSelectCommission == false) {
		throw new DBexception('수수료 조회 쿼리 실패');
	}
	if (mysqli_num_rows($rstSelectCommission) < 1) {
		throw new DBexception('수수료 조회 실패');
	}

	$rgSelectCommission = mysqli_fetch_assoc($rstSelectCommission);
	if ($rgSelectCommission == false) {
		throw new DBexception('수수료 배열 실패');
	}

	$nCommission = 0;
	
	# 출발 30분 전 = 취소 불가능 66Line 체크
	# 출발 30분 후 ~ 1시간 전 수수료 50%
	if (strtotime($rgReservationCancell['start_day']) > strtotime(date('Y-m-d H:i:s',strtotime('+30 minutes'))) 
		and strtotime($rgReservationCancell['start_day']) < strtotime(date('Y-m-d H:i:s',strtotime('+60 minutes')))) {
		$nCommission = floor($rgReservationCancell['price'] * 0.5);
	}

	# 출발 1시간 후 ~2시간 전 수수료 30% 
	# 출발 2시간 ~ 수수료 없음
	if(strtotime($rgReservationCancell['start_day']) > strtotime(date('Y-m-d H:i:s',strtotime('+60 minutes'))) 
		and strtotime($rgReservationCancell['start_day']) < strtotime(date('Y-m-d H:i:s',strtotime('+120 minutes')))) {

		$nCommission = floor($rgReservationCancell['price'] * 0.3);
	}
		
	# 취소할 때 금액 - 수수료
	$nChangeMile = $rgReservationCancell['price'] - $nCommission;

	# 취소할 티켓 업데이트
	$qryResevationCancell = "
		UPDATE reservation SET
			status =	  'n',
			account_code = '" . $strAccountCode . "',
			trade_type =  '" . $strTradeType . "',
			change_day =  NOW()
		WHERE seq = " . $nReserSeq . "
	";
	$rstReservationCancell = mysqli_query($Conn, $qryResevationCancell);
	if ($rstReservationCancell == false) {
		throw new DBexception('취소티켓 업데이트 쿼리 오류');
	}

	if (mysqli_affected_rows($Conn) < 1) {
		throw new DBexception('취소티켓 업데이트 오류');
	}

	# 변동 전 총액 확인 
	$nAllAmount = $CUserMile -> fnGetUserMile($All);
	if ($nAllAmount === false) {
		throw new DBexception ('출금 시 금액 확인 필요');
	}

	#user_mileage 출금 업데이트
	$rstChangeUpdate = $CUserMile -> fnChargeUpdate($bCheck, $nChangeMile, $strAccountCode);
	if ($rstChangeUpdate == false) {
		throw new DBexception('금액을 확인하세요 = 출금');
	}	

	#Accum_mile, Accum_mile_log 업데이트,인서트
	$rstAccumMile = $CUserMile -> fnAccumMile($bCheck, $strAccountCode, $nChangeMile, $strTradeCode, $nAllAmount, $strTradeType);
	if ($rstAccumMile == false) {
		throw new DBexception('금액을 확인하세요 - 적립');
	}

	#user_mile_change_list 인서트   ###보완
	$rstChangeList = $CUserMile -> fnChangeList($bCheck, $nChangeMile, $strAccountCode, $strTradeCode);
	if ($rstChangeList == false) {
		throw new DBexception('변동 데이터 입력 오류');
	}

	#account_book 인서트
	$rstAccountBook = $CUserMile -> fnAccountBook($bCheck ,$strTradeCode, $strAccountCode, $strPaymentType, $nChangeMile, $nCommission, $strId, $strTradeType);
	if ($rstAccountBook == false) {
		throw new DBexception('장부 데이터 입력 오류');
	}

	/* 예약자 있는지 조회하고 있으면 예매 */
	# 예매 예약 있는지 조회
	$qrySelectWait = "
		SELECT user_num, seq
		  FROM reservation
		 WHERE status = 'w'
		   AND bus_move_info_seq = " . $rgSelectReser['bus_move_info_seq'] . "
		   AND TIMESTAMPDIFF(MINUTE, '" . $dtWaitCheck . "', start_day) > 10
		  ORDER BY buy_day ASC 
		  LIMIT 1
	";
	$rstSelectWait = mysqli_query($Conn, $qrySelectWait);
	if ($rstSelectWait == false) {
		throw new DBexception('예매 예약 유무 조회 쿼리 오류');
	}

	if (mysqli_num_rows($rstSelectWait) > 0) {		
		$rgSelectWait = mysqli_fetch_array($rstSelectWait);
		if ($rgSelectWait == false) {
			throw new DBexception('예매 예약 버스 시퀀스 배열 오류');
		}

		# 예약자 이름 조회 쿼리
		$qryWaitUserName = "
			SELECT user_name, user_id
			  FROM user_info
			 WHERE user_num = " . $rgSelectWait['user_num'] . "
		";
		$rstWaitUserName = mysqli_query($Conn, $qryWaitUserName);
		if ($rstWaitUserName == false) {
			throw new DBexception('예약자 이름 조회 쿼리 오류');
		}
		
		if (mysqli_num_rows($rstWaitUserName) < 1) {
			throw new DBexception('예약자 이름 조회 오류');
		}

		$rgWaitUserName = mysqli_fetch_assoc($rstWaitUserName);
		if ($rgWaitUserName == false) {
			throw new DBexception('예약자 이름 배열 오류');
		}

		$strAllCode = '';						#클래스에서 예약자 총 금액을 가져오기 위한 공백
		$strId = $rgWaitUserName['user_id'];	#클래스에서 예약자 정보를 바꾸기 위함

		# 회원 정보 클래스에 입력
		$rgSetUserInfo = $CUserMile->fnSetUserInfo($rgSelectWait['user_num'], $rgWaitUserName['user_name']);
		if ($rgSetUserInfo == false) {
			throw new DBexception('회원 번호 대입 오류');
		}

		# 변동 전 총액 확인 
		$nAllAmount = $CUserMile -> fnGetUserMile($strAllCode);
		if ($nAllAmount === false) {
			throw new DBexception ('예약 확정 시 금액 확인 필요');
		}
		
		# 금액 체크 위한 변수
		$nCheckCommission = $rgReservationCancell['price'] * 0.05;
		$nCheckPrice = $rgReservationCancell['price'] + $nCheckCommission;
		
		# 예약 예매자의 총 금액이 예매 버스 금액보다 많을 때 실행
		if($nAllAmount > $nCheckPrice) {
			# 예매 테이블 예약자 row 락
			$qryUpdateWaitLock = "
				SELECT price, account_code, bus_class, user_num, trade_code, route
				  FROM reservation
				 WHERE status = 'w'
				   AND bus_move_info_seq = " . $rgSelectReser['bus_move_info_seq'] . "
				   AND TIMESTAMPDIFF(MINUTE, '" . $dtWaitCheck . "', start_day) > 10
				  ORDER BY buy_day ASC LIMIT 1
				  FOR UPDATE
			";
			$rstUpdateWaitLock = mysqli_query($Conn, $qryUpdateWaitLock);
			if ($rstUpdateWaitLock == false) {
				throw new DBexception('예약자 업데이트 락 쿼리 오류');
			}
		
			if (mysqli_num_rows($rstUpdateWaitLock) < 1) {
				throw new DBexception('예약자 업데이트 락 오류');
			}

			$rgUpdateWaitLock = mysqli_fetch_assoc($rstUpdateWaitLock);
			if ($rgUpdateWaitLock == false) {
				throw new DBexception('예약자 정보 배열 오류');
			}
			
			# 예매 테이블 업데이트
			$qryUpdateWait = "
				UPDATE reservation SET
					status = 'y',
					seat_num = " . $rgReservationCancell['seat_num'] .",
					change_day = now()
				WHERE status = 'w'
				  AND bus_move_info_seq = " . $rgSelectReser['bus_move_info_seq'] . "
				  AND TIMESTAMPDIFF(MINUTE, '" . $dtWaitCheck . "', start_day) > 10
				 ORDER BY buy_day ASC LIMIT 1
			";
			$rstUpdateWait = mysqli_query($Conn, $qryUpdateWait);
			if ($rstUpdateWait == false) {
				throw new DBexception('예약자 업데이트 쿼리 오류');
			}

			if (mysqli_affected_rows($Conn) < 1) {
				throw new DBexception('예약자 업데이트 오류');
			}

			# 버스 승객 수 업데이트
			$qryBusMoveInfoPlus = "
				UPDATE bus_move_info SET
				  people_cnt = people_cnt + 1
				 WHERE seq = " . $rgSelectReser['bus_move_info_seq'] . "
			";
			$rstBusMoveInfoPlus = mysqli_query($Conn, $qryBusMoveInfoPlus);
			if ($rstBusMoveInfoPlus == false) {
				throw new DBexception('버스 승객 수 업데이트 쿼리 오류');
			}
			
			if (mysqli_affected_rows($Conn) < 1) {
				throw new DBexception('버스 승객 수 업데이트 오류');
			}	

			/* 변수 초기화 */
			$strAccountCode = $rgUpdateWaitLock['account_code'];
			$strTradeCode = $rgUpdateWaitLock['trade_code'];
			$nCommission = $rgUpdateWaitLock['price'] * 0.05;	
			$nMileCharge = $rgUpdateWaitLock['price'] + $nCommission;
			$bCheck = 'wait';
			$strTradeType = 'e';
			$strPaymentType = 'm';
			
			#user_mileage 출금 업데이트
			$rstChangeUpdate = $CUserMile -> fnChargeUpdate($bCheck, $nMileCharge, $strAccountCode);
			if ($rstChangeUpdate == false) {
				throw new DBexception('금액을 확인하세요 = 예약출금');
			}	

			#Accum_mile, Accum_mile_log 업데이트,인서트
			$rstAccumMile = $CUserMile -> fnAccumMile($bCheck, $strAccountCode, $nMileCharge, $strTradeCode, $nAllAmount, $strTradeType);
			if ($rstAccumMile == false) {
				throw new DBexception('금액을 확인하세요 - 예약적립');
			}

			#user_mile_change_list 인서트 
			$rstChangeList = $CUserMile -> fnChangeList($bCheck, $nMileCharge, $strAccountCode, $strTradeCode);
			if ($rstChangeList == false) {
				throw new DBexception('변동 데이터 입력 오류 - 예약');
			}

			#account_book 인서트
			$rstAccountBook = $CUserMile -> fnAccountBook($bCheck ,$strTradeCode, $strAccountCode, $strPaymentType, $nMileCharge, $nCommission ,$strId, $strTradeType);
			if ($rstAccountBook == false) {
				throw new DBexception('장부 데이터 입력 오류 - 예약');
			}
		}
	}

	#커밋
	$bCommit = $Conn->Commit();
	if($bCommit == false){
		throw new DBexception('트랜잭션 실패');
	}

	$strAlert= '예매 취소 완료';
	$strLocation = '../bus/BusCancellForm.php';
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
	$strLocation = '../bus/BusCancellForm.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);

} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}

	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../bus/BusCancellForm.php';
	fnAlert($strAlert,$strLocation);
}
?>
