<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///db연결
include('../_inc/DBconnect.php');
ini_set('display_errors', true);
error_reporting(E_ALL);

$count=1;
$nMember_num = $_SESSION['member_Session_number'];

?>
    <html>
    <body>
    <h2>구매내역 상세정보</h2>

    <hr width="80%"/>
    <div>

        <ul id="headerRow">
            <li id="thCol">순번</li>
            <li id="thCol">제목</li>
            <li id="thCol">가격</li>
            <li id="thCol">구매일</li>
        </ul>
        <?php
try{
        $trans_check=$db->StartTrans();

        $rs = $db->Execute("select buy.sel_id, sel.sel_title, buy.buy_amount, buy.buy_date from buy buy join sel sel on buy.sel_id=sel.sel_id where buy.member_num=$nMember_num order by buy.buy_date desc  ");

        while (!$rs->EOF) {
            $nSel_id = $rs->fields[0];
            $strSel_title = $rs->fields[1];
            $nBuy_amount = $rs->fields[2];
            $dtBuy_regDate = $rs->fields[3];

            $rs->MoveNext();


            ?>
            <ul id="tdRow">
                <li id="tdCol"><?=$count++?></li>
                <li id="tdCol"><?= $strSel_title?></li>
                <li id="tdCol"><?= $nBuy_amount ?></li>
                <li id="tdCol"><?= $dtBuy_regDate ?></li>
                <form name="viewitem" method="post" action="../sel/sel_view.php">
                    <input type="hidden" name="sel_id" value="<?= $nSel_id ?>"/>
                    <input type="submit" value="제품 상세보기"/>

                </form>


            </ul>

        <?php }
        $db->CompleteTrans();?>


    </div>
    <hr width="100%"/>

    </div>
    <button type='button' onclick="location.href='../mileage_View/view_buyPoint.php'">뒤로가기</button>


    </body>
    </html>
<?php
include("../_inc/footer.php");
}catch (Exception $e) {
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
