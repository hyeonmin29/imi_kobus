<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

$CMaserdb = mysqli_connect('localhost', 'root', 'Itemmania1324%^', 'dev_test', '3306', '/var/run/mysqld/mysql_3306.sock');

//성공 or 실패
$bTrue = true;
 
//자동 커밋을 0으로 만들어주고
$rstSet = mysqli_query($CMaserdb, 'set autocommit=0');
//트랜잭션 시작
$rstTrs = mysqli_query($CMaserdb,'BEGIN_transaction');
 
 
//insert
$qryInsert  = "insert into ordersf values('12','3','90');";
$rstInsert = mysqli_query($CMaserdb,$qryInsert);

// rstInsert이나 mysql에서 실행된 작업이 0이면 $bTrue=false 반환
if(!$rstInsert || mysqli_affected_rows($CMaserdb) == 0){
	$bTrue = false;
}

// 작업 성공/실패 여부에 따라 COMMIT/ROLLBACK 처리
if(!$bTrue) {
    $rstRollback = mysqli_query($CMaserdb,'ROLLBACK');
    echo ("롤백 됐습니다.");
} else {
    $rstCommit = mysqli_query($CMaserdb,'COMMIT');
    echo("커밋 됐습니다.");
}

?>