<html>
	<body align = 'center'>
		<h1>회원가입 폼</h1>
		<form name = 'RegForm' method = 'post' action = 'RegOk.php'>
			<li>이름 : <input type = 'text' name = 'user_name'></li>
			<li>아이디 : <input type = 'text' name = 'user_id' autofocus/></li>
			<li>비밀번호 : <input type = 'password' name = 'user_pw'></li>
			<li>전화번호 : <input type = 'number' name = 'user_phone'></li>
			<li>이메일 : <input type = 'email' name = 'user_email'></li>
			<li>생년월일 : <input type = 'date' min = '<?=date('Y-m-d', strtotime('-50 year'))?>'   max = '<?= date('Y-m-d')?>' name = 'user_birth'></li>

			<li>
				계좌종류 : <select name = 'user_account'>
							<option value = '농협'>농협</option>
							<option value = '카카오뱅크'>카카오뱅크</option>
							<option value = '우리은행'>우리은행</option>
						</select>
			</li>

			<li>계좌번호 : <input type = 'number' name = 'user_account_num'></li>
			<li><input type = 'submit' value = '가입하기'>
				<input type = 'reset' value = '다시 작성하기'>
				<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
			</li>

		</form>
	</body>
</html>
