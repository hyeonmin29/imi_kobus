<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
//include_once ("../db/dbconn.php");
///shopFunction 가져옴
include_once ("../class/ShopFunction.php");
echo "fnGetMileage test //////";

//$db = new DB_conn();

//if($db==null){
  //  echo "연결실패";
 //   exit;

//}
///shopFunction을 인스턴스로 가져오고
$function = new ShopFunction();
///function이 빈값이면
if($function==null){
	///펑션실패 호출하고
    echo "펑션실패";
	///종료
    exit;
}

///배열 생성
$user_info=Array();
///배열 필드명 user_id에 member_Session_id값 대입
$user_info['user_id']=$_SESSION['member_Session_id'];
///배열 필드명 user_num에 member_Session_number값 대입
$user_info['user_num']=$_SESSION['member_Session_number'];
//$function->fnSetUser($user_info);
//$result = $function->fnGetMileage(1);
///function값을 testInsert(1)로 값 전달
$result=$function->testInsert(1);
///만약 빈값이면
if($result==null){
   ///출력
   echo"///  쿼리결과 안들어옴";
}
//
//
//
var_dump($result);


