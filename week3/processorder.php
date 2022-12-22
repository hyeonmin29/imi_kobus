<?php
require_once("file_exceptions.php");
$nTireqty = 1;
$nOilqty = 3;
$nSparkqty = 2;
$strAddress = '아이엠아이';
$dtDate = date('H:i, jS F');

echo "<p>주문 처리 시간 ".$dtDate."</p>";
echo '<p>주문 내역 : </p>';

$nTotalqty = $nTireqty + $nOilqty + $nSparkqty;
echo "주문 수량: ".$nTotalqty."<br />";
try{
	if( $nTotalqty == 0) {
		throw new Exception( "이전 페이지에서 아무것도 주문하지 않았습니다.<br />");
	}else{
		if ( $nTireqty > 0 ) {
			echo $nTireqty." 타이어<br />";
		}
		if ( $nOilqty > 0 ) {
			echo $nOilqty." 기름병<br />";
		}
		if ( $nSparkqty > 0 ) {
			echo $nSparkqty." 점화 플러그 <br />";
		}
	}
}
catch(Exception $e)
{
	echo $e->getMessage();
	echo $e->getLine();
	exit;
}
$nTotalamount = 0;

define('TIREPRICE', 100);
define('OILPRICE', 10);
define('SPARKPRICE', 4);
$nTotalamount = $nTireqty * TIREPRICE
			 + $nOilqty * OILPRICE
			 + $nSparkqty * SPARKPRICE;
$nTotalamount=number_format($nTotalamount, 2, '.', ' ');
echo "<p>총 주문 : ".$nTotalamount."</p>";
echo "<p>배송 받을 주소 : ".$strAddress."</p>";
$strOutputstring = $dtDate."\t".$nTireqty." 타이어 \t".$nOilqty." 기름\t"
 .$nSparkqty." 점화 플러그\t\$".$nTotalamount
 ."\t". $strAddress."\n";
?>

 
 
 
 
 
