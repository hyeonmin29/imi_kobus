<html>
	<head>
		<title>home</title>
	</head>
	<body align = center>
		<h2>IMI_kobus</h2>
		<ul>
			<a href = '../mileage/MileChargeForm.php' id = 'current'>마일리지 충전</a> &nbsp
			<a href = '../mileage/MileWithdrawForm.php' id = 'current'>마일리지 출금</a>&nbsp
			<a href = '../bus/BusSelectForm.php' id = 'current'>버스 예매</a>&nbsp
			<a href = '../bus/BusCancellForm.php' id = 'current'>예매 취소 및 변경</a>&nbsp
			<a href = '../user/UserWaitInfo.php' id = 'current'>예약 정보</a>&nbsp
			<a href = '../user/UserReserInfo.php' id = 'current'>예매 정보</a>&nbsp
			<a href = '../user/MyInfo.php' id = 'current'>내 정보</a>&nbsp
		</ul>

		<ul>
			<?php
			session_Start();		
			if($_SESSION['user_id'] === 'admin') {
				?>
				<h1><?=$_SESSION['user_id']?>님 반갑습니다.</h1>
				<a href = '../user/LogOut.php' id = 'current'>로그아웃</a>&nbsp
				<a href = '../bus/BusUpdate.php' id = 'current'>버스 운행정보 업데이트 페이지</a>
				<?php
				exit;
			}

			if ($_SESSION['user_id'] === 'driver') {
				?>
				<h1><?=$_SESSION['user_id']?>버스기사 Page</h1>
				<a href = '../user/LogOut.php' id = 'current'>로그아웃</a>&nbsp
				<a href = '../user/DriverRegForm.php' id = 'current'>버스기사 회원가입 페이지</a>
				<?php
				exit;
			}
			
			if (isset($_SESSION['user_id']) & !empty($_SESSION['user_id'])) {
				?>
				<h3><?=$_SESSION['user_id']?>님 반갑습니다.</h3>
				<a href = '../user/LogOut.php' id = 'current'>로그아웃</a>&nbsp
				<a href = '../user/withdrawalForm.php' id = 'current'>회원 탈퇴</a>
				<?php
			} else {
				?>
				<a href = '../user/LoginForm.php' id = 'current'>로그인</a>&nbsp
				<a href = '../user/RegForm.php' id = 'current'>회원가입</a>
				<?php
			}
			?>
		</ul>
				티켓 조회 - 거래번호를 입력하세요<br>
		<form action = '../user/ReserBusSelect.php' method = 'POST'>
			<input type = 'select' name = 'trade_code'>
			<input type = 'submit' value = '티켓 조회하기'>
		</form>
	</body>
</html>