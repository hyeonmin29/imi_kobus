<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
include_once('../adodb5/adodb-pager.inc.php');
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



?>
    <html>
    <body>
    <h2>매출 확인 페이지</h2>

    <hr width="80%"/>

    <div>

        <?php
        $rs = "select * from sel ";

        $pager = new ADODB_Pager($db, $rs);
        $pager->Render($rows_per_page = 15);
       ?>


    </div>
    <hr width="100%"/>

    </div>

    <button type='button' onclick="location.href='admin_listSel.php'">뒤로가기</button>



    </body>
    </html>
<?php
include("../_inc/footer.php");
