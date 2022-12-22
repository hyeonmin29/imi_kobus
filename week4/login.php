<?php
$CMasterdb = mysqli_connect('localhost', 'root', 'Itemmania1324%^', 'dev_test', '3306', '/var/run/mysqld/mysql_3306.sock');
try{
	$strUserid = $_POST['id'];
	$strUserpasswd = $_POST['pw'];
	//empty는 전혀 없는지 확인
	if(empty($_POST['id'])){
		throw new Exception('아이디를 입력해주세요.');
	}
	else if(empty($_POST['pw'])){
		throw new Exception('비밀번호를 입력해주세요.');
	}
}catch(Exception $e){
	echo $e->getMessage();
}

//아이디 검사
$qry = "select * from dev_user_info where user_id = '" . $strUserid . "'";
$rst = $CMasterdb->query($qry);

//아이디 있으면 비밀번호 검사
//$rst에서 가져온 행의 개수가 1개일 때
//mysqli_affected_rows는 mysql 작업으로 처리된 행의 개수를 얻는다.
if(mysqli_num_rows($rst)==1){
	//$rst의 값을 배열로 호출한다 (assoc은 연관배열 -> 필드명(열이름,키값)을 통해 사용 가능)
	//array(필드명, 번호 두개 다 사용 가능) / row(번호만 사용 가능)
	//array는 필드 번호 두개가 저장되어 있어서 while문으로 번호만 증가시키면 오류 발생
	$rgRow=mysqli_fetch_assoc($rst);
	//post값과 비밀번호 일치하면 맞으면 세션 생성
	if($rgRow['user_passwd']==$strUserpasswd){
		$_SESSION['user_id'] = $strUserid;
		$_SESSION['user_no'] = $rgRow['user_no'];
		$_SESSION['user_birth'] = $rgRow['user_birth'];
		$_SESSION['user_city'] = $rgRow['user_city'];	
		if(isset($_SESSION['user_id'])){
			//아이디와 비밀번호가 일치하면 index페이지로 이동
			echo "<script>location.replace('index.php');</script>";
		}
	}else{
		//비밀번호 오류일 때
		echo "비밀번호 error"; 
}
//아이디가 오류일 때
}else{
	echo "로그인 정보 오류";
}
?>