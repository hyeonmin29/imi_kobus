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

	/* DB 연결 시작 */
	$CDBConn = new DB;
	$Conn = $CDBConn->db;
	if ($Conn == false) {
		throw new exception('데이터베이스 연결 실패');
	}
	$rgCode = array(
		'cp00t', #핸드폰 충전완료
		'ca01t', #농협계좌 충전완료
		'ca02t', #우리은행 충전완료
		'ca03t', #카카오뱅크 충전완료
	);

	$CUserClass = new UserClass($Conn);
	$CUserMile = new UserMile($Conn);
	
	$strAllAmount = ''; # 클래스에서 총액을 가져오기 위한 공백값
	$strId = '';		# 클래스에서 세션값을 사용하기 위한 공백값
	$strUserNumColumn = array(
		'user_num',
		'user_name'
	);

	#회원번호 조회
	$rgUserInfo = $CUserClass -> fnUserInfo(implode(",", $strUserNumColumn), $strId);
	if($rgUserInfo == false) {
		throw new exception('회원 번호 조회 오류');
	}

	# 회원 정보 클래스에 입력
	$rgSetUserInfo = $CUserMile->fnSetUserInfo($rgUserInfo['user_num'], $rgUserInfo['user_name']);
	if ($rgSetUserInfo == false) {
		throw new exception('회원 번호 대입 오류');
	}

	# 총 금액 확인 -> 공백을 넣으면 모든 값 출력 / 값이 없거나 등록되어있지 않으면 0 출력
	$nAllAmount = $CUserMile -> fnGetUserMile($strAllAmount);
	if ($nAllAmount === false) {
		throw new exception('총액 조회 실패');
	}

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
<html>
	<h2>마일리지 충전 폼</h2>
	<body align = center>
		<p>충전 방법을 고르신 후 금액을 입력하세요.</p>
		<p>충전 수수료는 10%입니다.</p>
		<p>최소 충전액은 1000원 입니다.</p>
		<p>최대 충전금액은 100만원 입니다.</p>
		<p>현재 마일리지 금액 : <?=number_format($nAllAmount)?></p>

		<form method = "post" action = "../mileage/MileChargeOk.php">
			<p>충전 방법 = <Select name = 'account_code'>
						   <option value = 0> 충전 방법 선택하세요 </option>
						   <option value = 'ca01t'> 농협 계좌이체</option>
						   <option value = 'ca02t'> 우리은행 계좌이체</option>
						   <option value = 'ca03t'> 카카오뱅크 계좌이체</option>
						   <option value = 'cp00t'> 모바일 </option>
						</Select>
			</p>
			<p>충전 금액 = <input type = 'number' min = '1000' max = '1000000' name = 'mile_charge' placeholder="충전금액 입력"></p>
			</br>
			<p><input type = 'submit' value = '충전하기'>
				<input type = 'button' value = '마일리지 내역 체크' onclick = "window.location= '../user/MyInfo.php'">
				<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
			</p>
		</form>
	</body>
</html>