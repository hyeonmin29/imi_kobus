<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

class DB{
	private $strHost = 'localhost';
	private $strId = 'root';
	private $strPw = 'Itemmania1324%^';
	private $strTable = 'dev_test';
	private $strPort = '3306';
	private $strSock = '/var/run/mysqld/mysql_3306.sock';
	
	public function __construct(){
		$this->db = mysqli_connect($this->strHost, $this->strId, $this->strPw, $this->strTable, $this->strPort, $this->strSock);
	}
}

function fnAlert($strAlert , $strLocation) {
	$strAlrt = "<script>alert(\"$strAlert\");</script>";
	$strAlrt .= ("<script>location.href='./$strLocation';</script>");

	echo $strAlrt;
}

function gap_time($dtTime) {
	
	$dtTime = strtotime ($dtTime);
	$end_time = strtotime (date("H:i:s"));
	
	if ($dtTime < $end_time) {
		$diff = $end_time -$dtTime;
	} else {
		$diff = "-".$dtTime - $end_time;
	}

	$hours = floor ($diff/3600);
	$diff = $diff - ($hours*3600);
	$min = floor ($diff/60);
	$sec = $diff - ($min*60);
	return sprintf ("%02d:%02d:%02d", $hours, $min, $sec); 
}

class DBexception extends exception {
}




?>