<?php
try 
{
	$nNumber2 = $_REQUEST["nNumber2"];
	if($nNumber2 > 9){
		throw new Exception("10단부터는 없습니다."); 
	}
?>
<html>
<head>
<meta charset = "UTF-8"> 
<meta name = "viewport" content = "width=device-width, initial-scale=1"> 
<!-- 글꼴 값 지정 -->
<title><?=$nNumber?> while문 구구단표</title>
<style>
table {padding : 10px; border-collapse: collapse} 
</style>
</head>
<body>
<h2>while문 구구단표 출력</h2>
<!-- method= 데이터 전송 방법, action=서버로 전송한 데이터를 수신할 url -->
<form method=get action = "20220929_while.php">
구구단 입력:
<!-- 숫자만 입력가능, nNumber2=$_REQUEST (nNumber2)부분에 입력-->
<input type="number" name="nNumber2" required = "required"><br>
<!-- 클릭하여 보내는 방식, value=박스에 들어갈 이름-->
<input type="submit" value="제출숫자""><br> 
</form>
<!-- 구구단 박스 크기 -->
<table border = "1"> 
<?php
$nNumber=0;
while($nNumber<10){
	$nValue = $nNumber2 * $nNumber;
	print "<tr><td> $nNumber2 X $nNumber = $nValue</td></tr><br>"; 
	$nNumber++;
	}
}
catch(Exception $e){
	echo $e->getMessage()."<br>";
	echo $e->getFile()."<br>";
	exit;
}
?>
</table>
</body>
</html>

