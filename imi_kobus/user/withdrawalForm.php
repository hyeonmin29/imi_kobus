<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );
include('../function/DBConn.php');
session_start();
try {
	/* 세션 값 검사 */               
	if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
		throw new exception('로그인이 필요합니다.');
	}

} catch(exception $e) {
	$strAlert= '에러발생 : ' . $e->getMessage();
	$strLocation = 'loginForm.php';
	/* 에러발생 함수 */
	fnAlert($strAlert,$strLocation);
}
?>
<html>
	<body align = 'center'>
		<h1>회원탈퇴 폼</h1>
		<form name = 'withdrawalForm' method = 'post' action = 'withdrawalOk.php'>
			<li>비밀번호 : <input type = 'password' name = 'user_pw'autofocus/></li>
			<li>전화번호 : <input type = 'number' name = 'user_phone'/></li>
			<p>
				<li>
					<input type = 'submit' value = '회원탈퇴'/>
					<input type = 'reset' value = '다시 쓰기'/>
					<input type = 'button' value = '홈으로 돌아가기' onclick = "window.location= '../user/MainPage.php'">
				</li>
			</p>
		</form>
	</body>
</html>
