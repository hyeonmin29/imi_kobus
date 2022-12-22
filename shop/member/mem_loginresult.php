<?php
include("../_inc/header.php");
///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>
    <html>
<body>
<h2>로그인 페이지</h2>
<hr width="80%"/>
<div id="#contsRow">

<?php
try{
    $member_id = $_POST['strId'];
    $member_password = $_POST['strPw'];
	if(empty($member_id) || empty($member_password)){
		throw new Exception('아이디 및 비밀번호를 확인하세요');
	}

    $rs = $db->Execute("select mem.member_num, mem.member_id, mem.member_password, mem.member_admin, mil.mileage_id  
						from member mem join mileage mil 
						on mem.member_num=mil.member_num  
						where mem.member_id='$member_id' and mem.member_password=SHA1('$member_password') ");

	///select로 조회된 행개수 카운트
	$rstCount = $rs->recordCount();
	if($rstCount < 1){	
		throw new Exception('로그인 정보 조회 실패');
	}
	///session_start();원래 있어야 할 자리
    while (!$rs->EOF) {
		$_SESSION['member_Session_id'] = $rs->fields[1];
        $_SESSION['member_Session_number'] = $rs->fields[0];
        $_SESSION['member_Session_admin'] = $rs->fields[3];
        $_SESSION['member_Session_mileage'] = $rs->fields[4];
        $rs->MoveNext();
    }
	$rs->MoveLast();
	$rs->close();

?>
	<form name="memlogin" method="post" action="../index.php" class="formtag">
		<ul style="list-style-type:square">
			<h2 align="center">안녕하세요 <?php echo $_SESSION['member_Session_id']; ?>님 반갑습니다.</h2>
		</ul>
        <ul>
			<hr width="80%"/>
			<li align="center">
				<input type="submit" value="홈으로 가기"/>
				<input type="button" value="마이룸 가기" onclick="window.location='../mileage_View/view_myMileage.php'"/>
			</li>
        </ul>
    </form>
</div>
<?php
///footer.php 정보를 가져온다
include("../_inc/footer.php");
}catch (Exception $e) {
	/// 예외처리 문자와 코드 출력
    $error_msg = '에러발생 : ' . $e->getMessage();
    /// error_msg에 해당하는 경고창 출력
	echo "<script>alert(\" $error_msg \");</script>";
    echo("<script>location.href='../member/mem_login.php';</script>");
	if (isset($db) && $db->IsConnected() == true) {
		$db->close();
		unset($db);
	}
	exit;
}

