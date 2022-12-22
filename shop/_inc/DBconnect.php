<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	///클래스 있는 부분
include('../db/dbconn.php');

try{
	$driver = 'mysqli';
    $db = newADOConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
	if(!$db){
	//예외처리
     throw new Exception("db연결 오류");
	}
}catch (Exception $e) {
	echo $e->getMessage();
}


?>