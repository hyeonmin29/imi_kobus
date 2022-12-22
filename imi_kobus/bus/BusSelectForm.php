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

		/* 변수 초기화 */
	$strUserNumColumn = array(
		'user_num',
		'user_name',
	);
	$All = '';
	$strId = '';

	$CUserClass = new UserClass($Conn);
	$CUserMile = new UserMile($Conn);

	$rgUserInfo = $CUserClass -> fnUserInfo(implode(",", $strUserNumColumn), $strId);
	if($rgUserInfo == false) {
		throw new exception('회원 번호 조회 오류');
	}

	#회원 정보 클래스에 입력
	$rgSetUserInfo = $CUserMile->fnSetUserInfo($rgUserInfo['user_num'], $rgUserInfo['user_name']);
	if ($rgSetUserInfo == false) {
		throw new exception('회원 번호 대입 오류');
	}


	#총액 확인
	$nAllAmount = $CUserMile -> fnGetUserMile($All);
	if ($nAllAmount === false) {
		throw new exception ('총 금액 조회 오류 필요');
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
	<h2>버스 조회 폼</h2>
	<body align = 'center'>
		<h3>예매는 현재 시간 부터 +3일 까지만 가능합니다</h3>
		<p>예매 수수료는 5%입니다.</p>
		<p>보유 마일리지 : <?=number_format($nAllAmount)?></p>
		


			<table align = center border = 1 cellspacing = "0"> 
				<tr><th>경로</th> <th>프리미엄</th> <th>우등</th> <th>일반</th></tr>	
				<?
				for ($i = 0; $i < 2; $i++) {
					$strLocation = array(
						'전주 - 서울',
						'전주 - 인천'
					);

					$nPremium = array(
						'25600',
						'25900'
					);

					$nHonor = array(
						'20600',
						'20900'
					);

					$nNomal = array(
						'15600',
						'15900'
					);
					?>
					<tr>
						<td align = center><?= $strLocation[$i]?></td>
						<td align = center><?= number_format($nPremium[$i])?></td>
						<td align = center><?= number_format($nHonor[$i])?></td>
						<td align = center><?= number_format($nNomal[$i])?></td>
					</tr>	
					<?
				}
				?>
			</table>

		<form name = "bus_selectForm" method = "post" action = "../bus/BusReservationForm.php">
			<p>출발위치 :
				<Select name = 'leave_location'>
						   <option value = '0'> 출발위치를 선택하세요 </option>
						   <option value = 'jeonju'> 전주 </option>
						   <option value = 'seoul'> 서울 </option>
						   <option value = 'incheon'> 인천 </option>
				</Select>
			</p>

			<p>도착위치 :
				<Select name = 'arrive_location'>
						   <option value = '0'> 도착위치를 선택하세요 </option>
						   <option value = 'jeonju'> 전주 </option>
						   <option value = 'seoul'> 서울 </option>
						   <option value = 'incheon'> 인천 </option>
				</Select>
			</p>
				<p><li>날짜 : <input type = 'date' min = '<?=date('Y-m-d')?>'   max = '<?= date('Y-m-d',strtotime('+3 day'))?>' name = 'day'></li></p>
				<p><li>시간 : <input type = 'time'  name = 'bustime'></li></p>
				<input type = 'submit' value = '선택하기'>
				<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
		</form>
		</p>
	</body>
</html>





