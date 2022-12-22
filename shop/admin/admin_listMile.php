<?php
include("../_inc/header.php");
include_once('../adodb5/adodb-pager.inc.php');
require('../adodb5/adodb.inc.php');
ini_set('display_errors', true);
error_reporting(E_ALL);
?>
<html>
<body>
<h2>유저 보유 마일리지 리스트</h2>
<hr width="80%"/>
<div>
    <?php
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
    $user_cash = 0;
    $user_credit = 0;
    $user_phone = 0;
    $user_buymileage = 0;
    $user_buypoint = 0;
    $user_sum = 0;


    $sql = "select mem.member_id, mil.mileage_id,  mil.cash_amount , mil.credit_amount, mil.phone_amount,  mil.buymileage_amount , (mil.cash_amount +mil.credit_amount+mil.phone_amount+mil.buypoint_amount+mil.buymileage_amount) as 'Amount',mil.buypoint_amount from mileage mil join member mem on mil.member_num=mem.member_num  group by mil.member_num";

    $rs = $db->Execute("select mem.member_id, mil.mileage_id,  mil.cash_amount , mil.credit_amount, mil.phone_amount,  mil.buymileage_amount , (mil.cash_amount +mil.credit_amount+mil.phone_amount+mil.buypoint_amount+mil.buymileage_amount) as 'Amount',mil.buypoint_amount from mileage mil join member mem on mil.member_num=mem.member_num  group by mil.member_num");
    while (!$rs->EOF) {


        $user_cash += $rs->fields[2];
        $user_credit += $rs->fields[3];
        $user_phone += $rs->fields[4];
        $user_buymileage += $rs->fields[5];
        $user_buypoint += $rs->fields[7];
        $user_sum += $rs->fields[6];

        $rs->MoveNext();
    }

    ?>
    <table border="1">
        <tr><td colspan="6">// 유저 전체 마일리지 보유 합 //</td></tr>
        <tr>
            <td>[cash]</td>
            <td>[credit]</td>
            <td>[phone]</td>
            <td>[buymileage]</td>
            <td><-[sum]</td>
            <td>[buypoint]</td>
        </tr>


        <td><?= $user_cash ?></td>

        <td><?= $user_credit ?></td>

        <td><?= $user_phone ?></td>

        <td><?= $user_buymileage ?></td>

        <td><?= $user_sum ?></td>

        <td><?= $user_buypoint ?></td>


    </tr>
    </table>
    <?php
    $pager = new ADODB_Pager($db, $sql);
    $pager->Render($rows_per_page = 15);

    //유저 전체 구매 합
    $buy_sum = $db->Execute("select sum(buy_amount) as '구매 총합' from buy");
    //유저 전체 충전 합
    $withdarw_sum = $db->Execute("select sum(withdraw_price) as '출금 총합' from withdraw");


    ?>
    <li><?= $buy_sum ?></li>
    <li><?= $withdarw_sum ?></li>

</div>


</body>
</html>
<?php
include("../_inc/footer.php");