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
<!-- 글꼴 값 지정 -->
<meta name = "viewport" content = "width=device-width, initial-scale=1"> 
<title><?=$nNumber?> for문 구구단표</title>
<style>
table {padding : 10px; border-collapse: collapse}
</style>
</head>
<body>
<h2>for문 구구단표 출력</h2>
<!-- method= 데이터 전송 방법, action=서버로 전송한 데이터를 수신할 url -->
<form method=get action = "20220929_for.php">
구구단 입력:
<!-- 숫자만 입력가능, nNumber2=$_REQUEST (nNumber2)부분에 입력-->
<input type="number" name="nNumber2" required = "required"><br>
<!-- 클릭하여 보내는 방식, value=박스에 들어갈 이름-->
<input type="submit" value="제출숫자""><br>
</form>
<!-- 구구단 박스 크기 -->
<table border = "1">
<?php
// $dan 10이하일때..	
if($nNumber2 < 10){
	for($nNumber =1; $nNumber<=9; $nNumber++){ 
		$nNumber3 = $nNumber2 * $nNumber;
		print "<tr><td> $nNumber2 X $nNumber = $nNumber3 </td></tr><br>"; 
		}
	}
}
catch(Exception $e){
	echo $e->getMessage()."<br>";
	exit;
}
?>
</table>
</body>
</html>