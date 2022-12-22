<?php
//include("../db/dbconn.php");
require("../adodb5/adodb.inc.php");
error_reporting(E_ALL);

ini_set("display_errors", 1);


$strId = $_POST['strId'];
$strName = $_POST['strName'];
$strPassword = $_POST['strPassword'];
$strPassword_check = $_POST['strPassword_check'];
$nSex = $_POST['nSex'];
$strTel = $_POST['strTel'];
$strAdd = $_POST['strAdd'];
//  미입력 체크 및 비밀번호 미스매칭 체크
/************************************isset 수정 -> empty******************************
if (!isset($strId)) {
    echo "<script>
		alert(\"check id!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
// 이름 미입력 체크
if (!isset($strName)) {
    echo "<script>
		alert(\"check name!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
// 비밀번호 미입력 체크
if (!isset($strPassword)) {
    echo "<script>
		alert(\"check pw!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";

    echo("<script>location.href='mem_regForm.php';</script>");
}
// 비밀번호체크 미입력 체크
if ($strPassword !== $strPassword_check) {
    echo "<script>
		alert(\"not equal pw.  plz check!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
// 성별 미입력 체크
if (!isset($nSex)) {
    echo "<script>
		alert(\"check your gender!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
//전화번호 미입력 체크
if (!isset($strTel)) {
    echo "<script>
		alert(\"check Tel!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
// 주소 미입력 체크
if (!isset($strAdd)) {
    echo "<script>
		alert(\"check add!.\");
		window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
		</script>";
    echo("<script>location.href='mem_regForm.php';</script>");
    exit;
}
************************************************************************************/
echo $strId;
echo $strName;
echo $strPassword;
echo $nSex;
echo $strTel;
echo $strAdd;
try {
    if(!$db){
        throw new Exception("db연결 오류",94);
    }
    $trans_check=$db->StartTrans();
/*****************트랜잭션 시작 실패 -> 예외처리***
------------------------------------------
*****************************************/
$rs = $db->Execute("insert into member(member_id, member_password, member_name, member_tel, member_address, member_gender, member_regdate, member_lastedit) VALUES ('$strId',SHA1('$strPassword'),'$strName','$strTel','$strAdd',$nSex,now(), now())");
$mem_number = $db->Insert_ID();
$rs1 = $db->Execute("insert into mileage (member_num) values ($mem_number)");
    if ($rs=$db->Affected_Rows() <1){
        throw new Exception("등록 오류",5490);
    }
    if ($rs1=$db->Affected_Rows() <1){
        throw new Exception("등록 오류",5490);
    }

    unset($rs);
    unset($rs1);
    unset($mem_number);

$db->CompleteTrans();
echo "<script>alert(\"welcom shoes shop~.\");window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');</script>";
echo("<script>location.href='mem_login.php';</script>");

include("../_inc/footer.php");

}catch (Exception $e) {
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>alert(\" $error_msg \");</script>";
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