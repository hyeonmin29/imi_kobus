<?php
session_destroy();
?>
<script>
	//알림창에서 로그아웃 띄우기
    alert("로그아웃 되셨습니다.");
	//로그인폼으로 돌아가기
    location.replace('loginform.php');
</script>
