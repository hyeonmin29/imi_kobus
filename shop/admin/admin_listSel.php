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
$count = 1;
$sum_amount = 0;

$nMember_num = $_SESSION['member_Session_number'];


?>
    <html>
    <body>
    <h2>매출 확인 페이지</h2>

    <hr width="80%"/>

    <div>

        <ul id="headerRow">
            <li id="thCol">순번</li>
            <li id="thCol">제목</li>
            <li id="thCol">가격</li>
            <li id="thCol">잔여수량</li>
            <li id="thCol">판매수량</li>
            <li id="thCol">매출액</li>

        </ul>
        <?php
        $rs = $db->Execute("select  A.sel_id,A.sel_title,A.sel_price,A.sel_quantity,sum(B.buy_amount)as amount ,sum(B.buy_quantity) as sel_quantity from sel A join buy B on A.sel_id=B.sel_id group by A.sel_id order by amount desc; ");
        while (!$rs->EOF) {
            $nSel_id = $rs->fields[0];
            $nSel_title = $rs->fields[1];
            $nSel_price = $rs->fields[2];
            $nSel_lessQuantity = $rs->fields[3];
            $nSel_amount = $rs->fields[4];
            $nSel_selQuantity = $rs->fields[5];

            $sum_amount += $nSel_amount;

            $rs->MoveNext();

            if ($nSel_lessQuantity < 0) {
                $nSel_lessQuantity = "품절";
            }

            ?>


            <ul id="tdRow">
                <li id="tdCol"><?= $count++ ?></li>
                <li id="tdCol"><?= $nSel_title ?></li>
                <li id="tdCol"><?= $nSel_price ?></li>
                <li id="tdCol"><?= $nSel_lessQuantity ?></li>
                <li id="tdCol"><?= $nSel_selQuantity ?></li>
                <li id="tdCol"><?= $nSel_amount ?></li>
                <form name="viewitem" method="post" action="../sel/sel_view.php">
                    <input type="hidden" name="sel_id" value="<?= $nSel_id ?>"/>
                    <input type="submit" value="제품 상세보기"/>

                </form>


            </ul>

        <?php } ?>


    </div>
    <hr width="100%"/>

    </div>
    <p align="center"><h4>총 매출 :\<?= $sum_amount ?></h4></p>
    <button type='button' onclick="location.href='admin_showSel.php'">모든상품보기</button>


    </body>
    </html>
<?php
include("../_inc/footer.php");
