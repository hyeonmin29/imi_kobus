<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');
ini_set('display_errors', true);
error_reporting(E_ALL);


$mileage_id = $_SESSION['member_Session_mileage'];
$font_color = "WHITE";
?>
    <html>
    <body>
    <h2>구매 포인트 상세정보</h2>

    <hr width="80%"/>
    <div>

        <ul id="headerRow">
            <li id="thCol">발생일</li>
            <li id="thCol">타입</li>
            <li id="thCol">발생금액</li>
            <li id="thCol">잔액</li>
        </ul>
        <?php
try{
        $trans_check=$db->StartTrans();
        $rs = $db->Execute("select buypoint_regdate, buypoint_type, buypoint_price from buypoint_log where mileage_id=$mileage_id and buypoint_type!=503 order by buypoint_regdate desc limit 10 for update ");
        if (!$rs) {
            echo "<script>
            alert(\"no buypoint balance.\");
            window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
            </script>";
            echo("<script>location.href='view_myMileage.php';</script>");
        }
        while (!$rs->EOF) {
            $member_regDate = $rs->fields[0];
            $member_type = $rs->fields[1];
            $member_price = $rs->fields[2];


            $rs->MoveNext();

            switch ($member_type) {
                case 500:
                    $member_type = "(+)구매포인트적립";
                    $font_color = "BLUE";
                    break;
                case 501:
                    $member_type = "(-)구매마일리지전환";
                    $font_color = "RED";
                    break;
                case 502:
                    $member_type = "운영자변경";
                    $font_color = "GREEN";
                    break;
                case 503:
                    $member_type = "보유일경과 삭제";
                    $font_color = "RED";
                    break;

                default:
                    echo "-";
                    break;
            }

            ?>
            <ul id="tdRow">
                <li id="tdCol"> <?= $member_regDate ?></li>
                <li id="tdCol"><font color="<?= $font_color ?>"><?= $member_type ?></font></li>
                <li id="tdCol"><?= $member_price ?></li>



            </ul>

        <?php }

        $db->CompleteTrans();?>


    </div>
    <hr width="100%"/>

    </div>
    <button type='button' onclick="location.href='view_myMileage.php'">뒤로가기</button>
    <button type='button' onclick="location.href='view_del_buyPoint.php'">삭제내역 확인</button>


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