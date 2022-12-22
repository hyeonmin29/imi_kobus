<?php
///header 정보 가져오기
include("../_inc/header.php");
///require 정보 가져오기
require("../adodb5/adodb.inc.php");
///만약 member_Session_id세션값이 존재하지 않으면
if(!isset($_SESSION['member_Session_id'])){          // 로그인을 하지 않았다면 충전 페이지 진입 금지.
    ///알림창 first. go back 띄우고 index.php로 이동
	echo "<script>alert(\"Login first.  go back.\");
		  window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		  </script>";
    echo("<script>location.href='../index.php';</script>");

}
ini_set('display_errors', true);
error_reporting(E_ALL);
try {
	///DB와 연결
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
    ///만약 db가 연결되지 않으면
	if(!$db){
		///예외처리
        throw new Exception("db연결 실패");
    }
///mileage_id에 세션값 대입
$mileage_id = $_SESSION['member_Session_mileage'];
	///트랜잭션 시작
    $trans_check=$db->StartTrans();
///mileage테이블에서 buypoint_amount값이 null일 경우 0으로 대체 / 조건은 mileage_id와 $mileage_id와 같고 레코드 락을 건다
$rs = $db->Execute("select ifnull(buypoint_amount,0) from mileage where mileage_id=$mileage_id  for update");

///rs가 끝나지 않으면 계속 루프실행
while (!$rs->EOF) {
    $mypoint_amount = $rs->fields[0];
    $rs->MoveNext();
}
	///만약 변경된 횟수가 1이하이면
    if ($db->Affected_Rows() <1){
		///예외처리
        throw new Exception("정보조회 오류",590);
    }
?>

<html>
<body>
<h2>구매포인트 교환소</h2>
<hr width="80%"/>
<div id="#contsRow">
	<!--milchargingform이 이름이고 메소드 형식은 post / 데이터가 보내지는 곳은 mileage_PointChargingOk.php /class는 formtag-->
    <form name="milchargingform" method="post" action="mileage_PointChargingOk.php" class="formtag">
		<!--UI스타일은 사각형-->
        <ul style="list-style-type:square">


            <p> 1000 포인트 단위로 마일리지로 교환 가능합니다.</p>
            <p>충전 수수료는 충전 금액의 15% 입니다.</p><br/>
            <li> 당신의 현재 구매 포인트는:<?= $mypoint_amount ?>원 입니다.</li>
            <br/><br/>
								<!--금액을 입력하세요를 클릭 시 pointPrice 입력 칸으로 이동 /숫자만 적을 수 있고 최대값 = 정수형 mypoint_amount/1000-->
            <li>금액을 입력하세요 : <input type="number" name="pointPrice" max="<?= (int)(($mypoint_amount) / 1000) ?>"/>천원</li>

        </ul>
        <ul>
			<!--넓이는 80%-->
            <hr width="80%"/>
			<!--중앙정렬-->
            <li align="center">
				<!--change이름으로 버튼생성-->
                <input type="submit" value="change"/>
				<!--go home이름으로 버튼 생성 클릭 시 index.php로 이동-->
                <input type="button" value="go home" onclick="window.location='../index.php'">
            </li>
        </ul>

    </form>
</div>
<?php
///footer 정보 가져옴
include("../_inc/footer.php");
///커밋
$db->CompleteTrans();

} catch (Exception $e) {
	///경고창을 띄우고 에러메시지,코드 출력하고/ index.php로 이동
	$error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>
        alert(\" $error_msg \");
        </script>";
    echo("<script>location.href='../index.php';</script>");
	///만약 db라는 변수가 존재하고, db가 연결되어 있으면
    if (isset($db) && $db->IsConnected() == true) {
		///만약 trans_check가 true이면
        if ($trans_check == true) {
			///롤백
            $db->FailTrans();
			///커밋
            $db->CompleteTrans();
            ///변수 삭제
			unset($trans_check);
        }
		///연결해제
        $db->Close();
		///변수삭제
        unset($db);
    }
	///종료
    exit;
}
