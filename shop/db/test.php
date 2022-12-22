<?php

$a= (rand(100000,10000000));
$b= (rand(100000,1000000));
$c= (rand(100000,10000000));


echo $a,$b,$c;

include("../_inc/header.php");
//require("../adodb5/adodb.inc.php");
include("../db/dbconn.php");

ini_set('display_errors', true);
error_reporting(E_ALL);
try {
//    $driver = 'mysqli';
//    $db = newAdoConnection($driver);
//    $db->debug = false;
//
//    $db->connect('localhost', 'root', 'Kdkdldpadkdl123$%^', 'study');

    $db= new DB_conn();

    $test=$db->StartTrans();
for ($i=0; $i<3; $i++){
$db->Execute("insert into new_table(data1,data2,data3,data4) values($a,$a,$b,$c)");
}
    if($db==true){
        echo "이거된닼";}

 $db->CompleteTrans();
} catch (Exception $e) {
    die($e->getMessage());   // 에러메세지 출력
}
