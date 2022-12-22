<?php
//include("../db/dbconn.php");
require("../adodb5/adodb.inc.php");
///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


$strName = $_POST['strName'];
$strPassword = $_POST['strPassword'];
$nSex = $_POST['nSex'];
$nTel = $_POST['nTel'];
$strAdd = $_POST['strAdd'];
$member_num = $_SESSION['member_Session_number'];

try {
	///트랜잭션 시작
    $trans_check=$db->StartTrans();

///멤버이름=$strName 비밀번호=$strPassword 성별=$nSex 전화번호=$nTel 주소=$strAdd 시간=now()값으로 업데이트 시키는데 조건은 멤버번호가 $member_num인 경우
$rs = $db->Execute("update member set member_name='$strName', member_password='$strPassword', member_gender='$nSex' , member_tel='$nTel', member_address='$strAdd', member_lastedit=now() where member_num=$member_num");
	///만약 db에서 실행된 쿼리가 없으면
    if ($db->Affected_Rows() <1){
		///예외처리
        throw new Exception("정보갱신 오류",5490);
    }
///인덱스로 이동
echo("<script>location.href='../index.php';</script>");
///footer정보를 가져온다
include("../_inc/footer.php");

///commit
$db->CompleteTrans();

}catch (Exception $e) {
	///예외처리 시 예외처리 문장, 발생코드를 출력
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>
        alert(\" $error_msg \");
        </script>";
    echo("<script>location.href='../index.php';</script>");
	///db가 존재하고 db가 연결되었을 때
    if (isset($db) && $db->IsConnected() == true) {
        if ($trans_check == true) {
			///rollback
            $db->FailTrans();
			///commit
            $db->CompleteTrans();
			///변수삭제
            unset($trans_check);
        }
		///연결 종료
        $db->Close();
		///변수 삭제
        unset($db);
    }
    exit;
}











