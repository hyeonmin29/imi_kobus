<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
error_reporting(E_ALL);
ini_set("display_errors", 1);
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

$user_id = $_POST['user_id'];


$rs = $db->Execute("select cash_amount, credit_amount, phone_amount, buymileage_amount, buypoint_amount, member_num from mileage where member_num = (select member_num from member where member_id='$user_id')");


while (!$rs->EOF) {
    $user_cash = $rs->fields[0];
    $user_credit = $rs->fields[1];
    $user_phone = $rs->fields[2];
    $user_buyMileage=$rs->fields[3];
    $user_buyPoint = $rs->fields[4];
    $user_num = $rs->fields[5];

    $rs->MoveNext();
}
if (!isset($user_num)) {
    echo "<script>
                    alert(\"Can not find user id. go back. \");
                    window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
                    </script>";
    echo("<script>location.href='../admin/admin_userSearch.php';</script>");
}
?>
    <html>
    <body>
    <h2>유저 마일리지 변경 페이지 step 2</h2>
    <hr width="80%"/>
    <div id="#contsRow">

        <form name="admineditmile" method="post" action="admin_editMileOk.php" class="formtag">

            <ul style="list-style-type:square">

                <li><label for="nSelNum">userid : </label><?= $user_id ?></li>
                <br/>


                <li><label for="nCash">1)cash</label>
                    <input type="number" name="nCash" class="nCash" min="0" value="<?= $user_cash ?>"/></li>
                <br/>

                <li><label for="nCredit">2)credit</label>
                    <input type="number" name="nCredit" class="nCredit" min="0" value="<?= $user_credit ?>"/></li>
                <br/>

                <li><label for="nPhone">3)phone</label>
                    <input type="number" name="nPhone" class="nPhone" min="0" value="<?= $user_phone ?>"/></li>
                <br/>

                <li><label for="nPoint">4)buy mileage</label>
                    <input type="number" name="nBuyMileage" class="nPoint" min="0" value="<?= $user_buyMileage ?>"/></li>
                <br/>

                <li><label for="nPoint">5)buy point</label>
                    <input type="number" name="nBuyPoint" class="nPoint" min="0" value="<?= $user_buyPoint ?>"/></li>
                <br/>


                <input type="hidden" name="nNum" class="nNum" value="<?= $user_num ?>"/><br/>

                <p>*   변경하실 사항만 입력해주세요. </p>
                <p>**  변경할 총 마일리지를 입력해주세요. </p>
                <p>*** 수정하기를 누르면 바로 변경됩니다. 신중히 해주세요 </p>

            </ul>
            <ul>
                <hr width="80%"/>

                <li align="center"><input type="submit" value="수정하기"/>
                    <input type="reset" value="다시작성하기"/></li>
            </ul>

        </form>
    </div>
    </body>
    </html>
<?php
include("../_inc/footer.php");