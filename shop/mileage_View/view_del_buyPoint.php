<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
include_once('../adodb5/adodb-pager.inc.php');
///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');
ini_set('display_errors', true);
error_reporting(E_ALL);

$mileage_id = $_SESSION['member_Session_mileage'];
$member_type = "보유일경과 삭제";
$font_color = "RED";
?>
    <html>
    <body>
    <h2>삭제된 구매포인트</h2>

    <hr width="80%"/>
    <div>

        <?php
       $sql="select buypoint_deldate, buypoint_type, buypoint_price from buypoint where mileage_id=$mileage_id and buypoint_type=503 order by buypoint_delDate desc ";


        $pager = new ADODB_Pager($db, $sql);
        $pager->Render($rows_per_page = 15);
            ?>




    </div>
    <hr width="100%"/>

    </div>
    <button type='button' onclick="location.href='view_buyPoint.php'">뒤로가기</button>



    </body>
    </html>
<?php
include("../_inc/footer.php");
