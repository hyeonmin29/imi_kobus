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
	if (empty($_POST['seq'])) {
		throw new exception('값이 입력되지 않았습니다.');
	}	

	

	/* DB 연결 시작 */
	$CDBConn = new DB;
	$Conn = $CDBConn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}
	/* 변수 초기화 */
	$strAccountCode = 'em00f';
	$rgSeq = $_POST['seq'];
	$nCountSeq = count($rgSeq);

	/* 트랜잭션 시작 */
	$bTrans_Check=$Conn->begin_transaction();
	if($bTrans_Check == false) {
		throw new DBexception('트랜잭션 실패');
	}

	for ($i = 0; $i < $nCountSeq; $i++) {
		
		# 예매테이블 업데이트 할 row 락
		$qryLock = "
			SELECT seq
			  FROM reservation
			 WHERE seq = " . $rgSeq[$i] . "
			  FOR UPDATE
		";
		$rstLock = mysqli_query($Conn, $qryLock);
		if ($rstLock == false) {
			throw new DBexception('락 쿼리 오류');
		}

		if(mysqli_num_rows($rstLock) < 1) {
			throw new DBexception('락 오류');
		}
		
		# 예매 취소로 정보로 업데이트
		$qryWaitSelect = "
			UPDATE reservation SET
				status = 'n',
				trade_type = 'e',
				account_code = '" . $strAccountCode . "',
				change_day = NOW()
			 WHERE seq = " . $rgSeq[$i] . "
		";
		$rstWaitSelect = mysqli_query($Conn, $qryWaitSelect);
		if ($rstWaitSelect == false) {
			throw new DBexception('업데이트 쿼리 오류');
		}
		
		if (mysqli_affected_rows($Conn) < 1) {
			throw new DBexception('업데이트 오류');
		}
	}
	
	#커밋
	$bCommit = $Conn->Commit();
	if($bCommit == false){
		throw new DBexception('트랜잭션 실패');
	}

	$strAlert= '예약 취소 완료.';
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
	$strLocation = '../bus/UserWaitInfo.php';
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
