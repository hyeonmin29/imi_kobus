<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///member_Session_id존재하지 않으면
if (!isset($_SESSION['member_Session_id'])) {          // 로그인을 하지 않았다면 충전 페이지 진입 금지.
	///경고창 띄우고 index.php로 이동
    echo "<script>
alert(\"Login first.  go back.\");
window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
</script>";
    echo("<script>location.href='../index.php';</script>");

}

ini_set('display_errors', true);
error_reporting(E_ALL);
try {
	///데이터베이스 연결
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
} catch (Exception $e) {
    die($e->getMessage());   // 에러메세지 출력
}
///session값 member_num에 대입
$member_num = $_SESSION['member_Session_number'];
///mileage테이블에서 모든 결제수단을 조회하는데 0으로 대체한다 조건은 member_num이 $member_num과 값이 같아야 함
$rs = $db->Execute("select ifnull((cash_amount+credit_amount+phone_amount + buymileage_amount),0)  from mileage where member_num=$member_num");

///rs가 EOF를 만나면 종료
while (!$rs->EOF) {
	///mypoint_amount에 fields0에 저장된 rs값이 대입
    $mypoint_amount = $rs->fields[0];
	///다음 커서로 이동
    $rs->MoveNext();
}
///mypoint_amount =  mypoint_amount - 900
$mypoint_amount -= 900;
?>

    <html>
<body>
    <h2>포인트 출금소</h2>
    <hr width="80%"/>
<div id="#contsRow">
	<!--폼 이름은 milchargingform / 메소드는 post / 정보가 전해지는 곳은 mileage_withdrawOk.php / class는 formatag-->
    <form name="milchargingform" method="post" action="mileage_withdrawOk.php" class="formtag">
		<!--UI는 사각형-->
        <ul style="list-style-type:square">

            <p> 1회 출금당 900원의 수수료가 합산됩니다.</p>
            <br/>
            <li> 구매를 통해 획득한 마일리지는 출금할 수 없습니다.</li>
            <li> 최소 출금 금액은 1,000원 입니다.</li>
            <li> 당신의 현재 출금가능 포인트는 수수료 포함: <?= $mypoint_amount ?>원 입니다.</li>
            <br/><br/>

            <li> 은행 선택 :
				<!--선택형 블록박스 생성-->
                <SELECT name="bank_type">
                    <option value="0">choose bank</option>
                    <option value="1">카카오 뱅크</option>
                    <option value="2">농협</option>
                    <option value="3">우체국</option>
                </select></li>
            <br/>		  <!--type은 숫자, 전송되는 이름은 nbankNum ,입력하기 전까지 "-를 제외한 숫자만 입력"가 나오다가 입력하면 사라짐-->
            <li>계좌번호 입력 :<input type="number" name="nbankNum" placeholder="-를 제외한 숫자만 입력" </li>
            <br/>		  <!--위와 동일 , 최소값은 1000, 최대값은 $mypoint_amount-->
            <li>출금금액 입력 :<input type="number" name="pointPrice" min="1000" max="<?=$mypoint_amount?>" placeholder="금액"></li>
			<!--사용자에게 보이지 않지만 1이라는 값이 step의 이름으로 전송-->
            <input type="hidden" name="step" value="1"/>
        </ul>
        <ul><!--넓이 80%-->
            <hr width="80%"/>
			<!--중앙정렬-->
            <li align="center">
				<!--withdraw라는 값이 버튼 형식으로 생성 / submit은 폼을 제출하는 이벤트 발생시킴 /button은 아무것도 적지 않았을 때 submit과 같은 기능을 함-->
                <input type="submit" value="withdraw"/>
				<!--go home이라는 버튼을 누르면 index.php로 이동-->
                <input type="button" value="go home" onclick="window.location='../index.php'">
            </li>
        </ul>

    </form>
</div><?php
///footer정보를 가져옴
include("../_inc/footer.php");