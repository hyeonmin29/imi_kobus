<?php
include("../_inc/header.php");
include_once('../adodb5/adodb-pager.inc.php');
include("../adodb5/adodb.inc.php");
///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');
ini_set('display_errors', true);
error_reporting(E_ALL);


    $my_num = $_SESSION['member_Session_number'];
    $my_id = $_SESSION['member_Session_id'];



    $trans_check=$db->StartTrans();


// 멤버의 보유 마일리지 현황과 합계를 출력해줌.
    $sql = "select mem.member_id, mil.mileage_id,  mil.cash_amount , mil.credit_amount, mil.phone_amount,  mil.buymileage_amount , (mil.cash_amount +mil.credit_amount+mil.phone_amount+mil.buymileage_amount) as 'Amount',mil.buypoint_amount from mileage mil join member mem on mil.member_num=mem.member_num  where mem.member_num=$my_num";


    ?>

    <html>
    <body>
    <h2><?php echo $my_id; ?>님의 마일리지 정보</h2>
    <hr width="80%"/>
    <div id="#contsRow">


        <?php
try{
        //ADODB의 pager 기능 사용
        $pager = new ADODB_Pager($db, $sql);
        $pager->Render($rows_per_page = 15);
        if(!$pager){
            throw new Exception("정보 조회 오류",3828);
        }
        ?>

        <!--각 분류별 상세정보로 갈 수 있는 버튼-->
        <hr width="100%"/>
        <br/>
        <input type="button" value="현금 마일리지 상세정보" onclick="window.location='../mileage_View/view_cashMileage.php'"/>&nbsp;
        <input type="button" value="신용카드 마일리지 상세정보" onclick="window.location='../mileage_View/view_creditMileage.php'"/>&nbsp;
        <input type="button" value="핸드폰 마일리지 상세정보" onclick="window.location='../mileage_View/view_phoneMileage.php'"/>&nbsp;
        <input type="button" value="구매 마일리지 상세정보" onclick="window.location='../mileage_View/view_buyMileage.php'"/>&nbsp;
        <input type="button" value="구매 포인트 상세정보" onclick="window.location='../mileage_View/view_buyPoint.php'"/>
        <input type="button" value="구매 내역 정보" onclick="window.location='../buy/buy_List.php'"/>


    </div>


    </body>
    </html>
    <?php
    include("../_inc/footer.php");


    $db->CompleteTrans();
} catch (Exception $e) {
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>
        alert(\" $error_msg \");
        </script>";
    echo("<script>location.href='../index.php';</script>");

    if (isset($db) && $db->IsConnected() == true) {
        if ($trans_check == true) {
            $db->FailTrans();
            $db->CompleteTrans();
            unset($trans_check);
        }
        $db->Close();
        unset($db);
    }
    exit;
}
