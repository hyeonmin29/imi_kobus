<html>
	<body align = 'center'>
		<h1>버스기사 회원가입 폼</h1>
		<form name = 'RegForm' method = 'post' action = 'DriverRegOk.php'>
			<li>이름 : <input type = 'text' name = 'driver_name'></li>
			<li>아이디 : <input type = 'text' name = 'driver_id' autofocus/></li>
			<li>비밀번호 : <input type = 'password' name = 'driver_pw'></li>
			<li>전화번호 : <input type = 'number' name = 'driver_phone'></li>
			<li>이메일 : <input type = 'email' name = 'driver_email'></li>
			<li>지역 : <input type = 'text' name = 'driver_location'></li>

			<li>
				<input type = 'submit' value = '가입하기'>
				<input type = 'reset' value = '다시 작성하기'>
				<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
			</li>

		</form>
	</body>
</html>
