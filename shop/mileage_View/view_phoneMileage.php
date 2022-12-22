<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');
ini_set('display_errors', true);
error_reporting(E_ALL);


$member_Num = $_SESSION['member_Session_number'];
$font_color = "WHITE";
?>
    <html>
    <body>
    <h2>핸드폰 마일리지 상세정보</h2>

    <hr width="80%"/>
    <div>

        <ul id="headerRow">
            <li id="thCol">발생일</li>
            <li id="thCol">타입</li>
            <li id="thCol">발생금액</li>
            <li id="thCol">잔액</li>
        </ul>
        <?php

        $trans_check=$db->StartTrans();

        $rs = $db->Execute("select phone_regdate, phone_type, phone_price, phone_amount from phone_mileage where member_num=$member_Num order by phone_regdate desc limit 10 for update");
try{
        if (!$rs) {
            throw new Exception("마일리지 조회 오류",039e39);
        }
        while (!$rs->EOF) {
            $member_regDate = $rs->fields[0];
            $member_type = $rs->fields[1];
            $member_price = $rs->fields[2];
            $member_amount = $rs->fields[3];

            $rs->MoveNext();

            switch ($member_type) {
                case 300:
                    $member_type = "(+)휴대폰충전";
                    $font_color = "BLUE";
                    break;
                case 301:
                    $member_type = "(-)휴대폰이용구매";
                    $font_color = "RED";
                    break;
                case 302:
                    $member_type = "(-)휴대폰출금";
                    $font_color = "RED";
                    break;
                case 303:
                    $member_type = "(+)운영자변경";
                    $font_color = "BLUE";
                    break;
                case 304:
                    $member_type = "(-)운영자변경";
                    $font_color = "RED";
                    break;
                case 305:
                    $member_type = "(+)물품판매대금";
                    $font_color = "GRAY";
                    break;

                default:
                    echo "-";
                    break;
            }

            ?>
            <ul id="tdRow">
                <li id="tdCol"><?= $member_regDate ?></li>
                <li id="tdCol"><font color="<?= $font_color ?>"><?= $member_type ?></font></li>
                <li id="tdCol"><?= $member_price ?></li>
                <li id="tdCol"><?= $member_amount ?></li>


            </ul>

        <?php }

        $db->CompleteTrans();?>


    </div>
    <hr width="100%"/>

    </div>
    <button type='button' onclick="location.href='view_myMileage.php'">뒤로가기</button>


    </body>
    </html>
<?php
include("../_inc/footer.php");
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