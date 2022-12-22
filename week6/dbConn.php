<?php
$strHost = 'localhost';
$strId = 'root';
$strPw = 'Itemmania1324%^';
$strTable = 'dev_test';
$strPort = '3306';
$strSock = '/var/run/mysqld/mysql_3306.sock';
$CMaster = mysqli_connect($strHost,$strId,$strPw,$strTable,$strPort,$strSock);
try{

	if(!$CMaster){
		throw new exception('db연결 오류');
	}

}catch(Exception $e){
	echo $e->getMessage()."<br>";
}
?>