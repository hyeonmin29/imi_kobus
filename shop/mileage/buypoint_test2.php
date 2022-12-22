<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
include_once('../adodb5/adodb-pager.inc.php');

ini_set('display_errors', true);
error_reporting(E_ALL);
$price = 5700;
$buyPoint_result = 0;
$buypoint_regdateuy = array();
$buypoint_id = array();
$buypoint_price = array();
$count_array = 0;
try {
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
} catch (Exception $e) {
	///연결되지 않으면 에러메시지 출력
    die($e->getMessage());   // 에러메세지 출력
}

?>
    <html>
    <body>
    <h2>회원 리스트</h2>
    <hr width="80%"/>
    <div>
        <?php
		///test2에서 id,amount,regdate를 검색하는데 조건은 buypoint_type=500이거나 502 ->buypoint_regdate값으로 정렬
        $rs = $db->Execute("select buypoint_id, buypoint_amount, buypoint_regdate from test2 where buypoint_type=500 or 502 order by buypoint_regdate");
		
		///
        while (!$rs->EOF) {
			///fieds함수 배열에 값 추가
            $buypoint_id[] = $rs->fields[0];
            $buypoint_amount[] = $rs->fields[1];
            $buypoint_regdate[] = $rs->fields[2];
            $rs->MoveNext();
			///count_array -> 0부터 증가
            $count_array++;


        }
        // var_dump($rs);


        $totalNum = 0;
		//i는 count_array 이하까지 값 증가
        for ($i = 0; $i <= $count_array; $i++) {

			///만약 buypoint_amount - price값이 0보다 크면
            if (($buypoint_amount[$i] - $price) > 0) {
				///buypoint_amount - price값을 buyPoint_result1값에 대입
                $buyPoint_resutl = $buypoint_amount[$i] - $price;
				$price = 0;
				///test2에 있는 buypoint_amount값을 $buyPoint_result값으로 수정하는데 조건은 buypoint_id와 $buypoint_id값이 같은 행에서 수정
                $rs = $db->Execute("update test2 set buypoint_amount=$buyPoint_result where buypoint_id=$buypoint_id[$i]");
                ///멈춤
				break;
				///만약 $buyponit_amount값이 0이면
            } else if ($buypoint_amount == 0) {
				///다음 코드로 건너뛴다.
				continue;
				///if문이 아니면
            } else {
				///buyPoint_result값에 $price - $buypoint_amount[$i]; 대입
                $buyPoint_result = $price - $buypoint_amount[$i];
				///price는 0이아닌 $buyPoint_result;으로 대입 
                $price = $buyPoint_result;
				///test2의 buypoint_amount에 0으로 수정 조건은 buypoint_id와 buypoint_id[$i]가 같을 때
                $rs = $db->Execute("update test2 set buypoint_amount=0 where buypoint_id=$buypoint_id[$i]");
            }
            echo "id :" . $buypoint_id[$i];
            echo " price : " . $buypoint_amount[$i];
            echo " regdate :" . $buypoint_regdate[$i] . "<br/>";

        }
        ?>


    </div>
    </body>
    </html>
<?php
///footer 정보 가져오기
include("../_inc/footer.php");
