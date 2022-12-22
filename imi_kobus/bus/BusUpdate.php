<?php
error_reporting( E_ALL );
session_start();
ini_set( "display_errors", 1 );
include('../function/DBConn.php');
include('../function/BusFunction.php');

try {
	/* 세션 값 검사 */               
	if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || $_SESSION['user_id'] != 'admin') {
		throw new exception('관리자 아이디로 로그인해주세요.');
	}

	/* DB 연결 시작 */
	$CDBConn = new DB;
	$Conn = $CDBConn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}

	$CBusClass = new BusClass($Conn);

	/** 배차시간 **/
	$dtToday = date("Y-m-d H:i:s", strtotime("+3 day"));

	#홀수일 시작 시간
	$dtTime = date("Y-m-d 00:00:00");
	
	#짝수일 시작 시간
	if (date("d") % 2 == 0) {
		$dtTime = date("Y-m-d 00:15:00");
	}

	$dtMinutes = "+0 minutes";
	
	# 배차시간 담는 배열 변수
	$rgLeaveDay = array();
	do{
		#날짜 끝 자리 체크
		$bDayCheck = (substr($dtTime, 9,1));
		$bEvenCheck = (substr($dtTime, 11));
	
		$dtDay = date("Y-m-d H:i:s", strtotime($dtTime. $dtMinutes));
		$dtTime = $dtDay;

		# 짝수일이면 00시 00분 00초부터 배차 시작
		if ($bDayCheck % 2 == 0) {
			if($bEvenCheck == '23:45:00'){
				$dtDay = $dtDay = date("Y-m-d H:i:s", strtotime($dtTime. "-15 minutes"));
				$dtTime = $dtDay;
			}
		}

		# 홀수일이면 00시 15분 00초부터 배차 시작
		if ($bDayCheck % 2 == 1) {
			if($bEvenCheck == '23:30:00'){
				$dtDay = $dtDay = date("Y-m-d H:i:s", strtotime($dtTime. "+15 minutes"));
				$dtTime = $dtDay;
			}
		}	

		$rgLeaveDay[] = $dtTime;
		$dtMinutes = "+30 minutes";
	
	} while($dtToday > $dtTime);

	$nCountLeaveDay = count($rgLeaveDay);


	$strLocation = array(
		'jeonju',
		'seoul',
		'incheon'
	);
	
	/* 트랜잭션 시작 */
	$bTrans_Check = $Conn->begin_transaction();
	if($bTrans_Check == false) {
		throw new DBexception('트랜잭션 실패');
	}

	#전주->서울
	$rstJeonjuSeoulInfo = $CBusClass->fnMoveInfo($strLocation[0], $strLocation[1], $rgLeaveDay);
	if ($rstJeonjuSeoulInfo == false) {
		throw new DBexception('전주->서울 실패');
	}

	#서울->전주
	$rstSeoulJunJuMoveInfo = $CBusClass->fnMoveInfo($strLocation[1], $strLocation[0], $rgLeaveDay);
	if ($rstSeoulJunJuMoveInfo == false) {
		throw new DBexception('서울->전주 실패');
	}

	#전주->인천
	$rstJeonjuIncheonMoveInfo = $CBusClass->fnMoveInfo($strLocation[0], $strLocation[2], $rgLeaveDay);
	if ($rstJeonjuIncheonMoveInfo == false) {
		throw new DBexception('전주->인천 실패');
	}

	#인천->전주
	$rstIncheonJunjuMoveInfo = $CBusClass->fnMoveInfo($strLocation[2], $strLocation[0], $rgLeaveDay);
	if ($rstIncheonJunjuMoveInfo == false) {
		throw new DBexception('인천->전주 실패');
	}

	#커밋
	$bCommit = $Conn->Commit();
	if($bCommit == false){
		throw new DBexception('트랜잭션 실패');
	}

	$strAlert = '업데이트 완료';
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
	$strLocation = '../user/MainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
} catch(exception $e) {
	if ($Conn == true) {
		mysqli_close($Conn);
		unset($Conn);
	}
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = '../user/MainPage.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
}
?>