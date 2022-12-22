<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');
ini_set('display_errors', true);
error_reporting(E_ALL);
try {
	$sel_id = $_POST['sel_id'];
    ///트랜잭션 시작
	$trans_check=$db->StartTrans();
	///db수정하는데 sel테이블에서 sel_quantity는 -999로 수정 sel id가 $sel_id와 같은 행 값
    $db->Execute("update sel set sel_quantity= -999 where sel_id=$sel_id ");            // delete가 아닌 update를 이용해 리스트에서만 노출안되도록..
	///만약 db에서 변경된 데이터가 1개 미만이면
    if ($db->Affected_Rows() <1){
		///예외처리
        throw new Exception("삭제실패 다시 시도해 주세요",142);
    }

	///커밋
	$db->CompleteTrans();


	echo "<script>
	alert(\"del ok!.\");
	window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
	</script>";

	echo("<script>location.href='../sel/sel_list.php';</script>");


	include("../_inc/footer.php");


}catch (Exception $e) {
	///예외처리 시 경고창, 메시지,코드 출력하고 index.php로 넘어감
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>
        alert(\" $error_msg \");
        </script>";
    echo("<script>location.href='../index.php';</script>");
	///db가 존재하고, db가 연결되어 있으면 true
    if (isset($db) && $db->IsConnected() == true) {
		///trans_check가 true이면
        if ($trans_check == true) {
			///롤백
            $db->FailTrans();
            ///커밋
			$db->CompleteTrans();
            ///변수삭제
			unset($trans_check);
        }
		///db연결종료
        $db->Close();
        ///db변수 삭제
		unset($db);
    }
    exit;
}


