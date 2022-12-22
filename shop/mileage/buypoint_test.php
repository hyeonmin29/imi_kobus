<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
ini_set('display_errors', true);
error_reporting(E_ALL);

try {
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
} catch (Exception $e) {
    die($e->getMessage());   // 에러메세지 출력
}

$member_milId = $_SESSION['member_Session_mileage'];            // 멤버의 마일리지 번호를 저장
$member_num = $_SESSION['member_Session_number'];                // 멤버의 멤버 번호를 저장
$nPoint = $_POST['pointPrice'] * (1000);                        // 1000원 단위로 받았기 때문에 1000곱해준다.
$change_tax = $nPoint * 0.15;                                    // 포인트 충전의 경우 15%의 수수료가 추가된다.
$nPoint_sum = $nPoint - $change_tax;                            // 실제 충전 포인트는 구매금액-수수료


$db->StartTrans();

///buy _mileage에 데이터 추가 / id,type,num,price,amount,tax,regdate에  순서대로 데이터 추가/ 
$rs = $db->Execute("insert into buy_mileage (mileage_id, buymileage_type, member_num, buymileage_price, buymileage_amount, buymileage_tax, buymileage_regdate) values($member_milId,400,$member_num,$nPoint_sum,(select ifnull((select A.buymileage_amount from buy_mileage A where A.member_num=$member_num order by A.buymileage_regdate desc limit 1 ),0)+$nPoint_sum), $change_tax, now() )");

///만약 데이터가 추가되지 않으면
if (!$rs) {
	///롤백
    $db->FailTrans();
}
///test2에 데이터 추가 순서대로 11,7,7,500,17500,현재시간
$rs = $db->Execute("insert into test2 (buy_id, member_num, mileage_id, buypoint_type, buypoint_price, buypoint_regdate) values (11,7,7,500,17500,now())");
///커밋
$db->CompleteTrans();

?>
	<!--클릭하면 view_myMileage.php로 이동하는 버튼생성-->
    <button type='button' onclick="location.href='../mileage_View/view_myMileage.php'"> 보유 마일리지 확인</button>

<?php
///footer.php로 이동
include("../_inc/footer.php");