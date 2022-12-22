<!----- 반복문 ----->

<!----- foreach문 시작----->
<?php
$nNumber1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9"); // array = 배열함수 (키값)
$nNumber2 = array("1", "2", "3", "4", "5", "6", "7", "8", "9");
foreach($nNumber1 as $key => $val)			//nNumber1의 값이 val로 순차적으로 전달
{
	echo "".$val." 단<br>";					//val 값 출력
	foreach($nNumber2 as $key2=>$val2)		//nNumber2의 값이 val2로 순차적으로 전달
	{
		echo $val ." X ". $val2 ." = ".($val*$val2)."<br>";		//val x val2 = 결과값으로 출력
	}
}
?>



<!----- for문 시작----->

<?php
try 
{
$dan = $_REQUEST["dan"];   //네임 dan값을 받아 $dan 변수값으로 지정
	if($dan > 9) throw new Exception("10단부터는 없습니다.");  // 9보다 큰 수 입력시 (10단부터는 없습니다.)를 e값에 저장
}
	catch(Exception $e) // e예외 지정
{
	echo $e->getMessage()."<br>"; //e값을 받아와 출력
	exit;
}
?>
<html>
<head>
<meta charset = "UTF-8"> <!-- 언어 UTF-8 지정 -->
<meta name = "viewport" content = "width=device-width, initial-scale=1"> <!-- 글꼴 값 지정 -->
<title><?=$nNumber?> for문 구구단표</title> <!-- 브라우저 탭의 제목 설명 -->
<style>
table {padding : 10px; border-collapse: collapse} 
</style> <!-- 테이블 박스 및 크기 지정 -->
</head>
<body>
<h2>for문 구구단표 출력</h2>
<form method=get action = "test.php"> <!-- method= 데이터 전송 방법, action=서버로 전송한 데이터를 수신할 url -->
구구단 입력:
<input type="number" name="dan" required = "required"><br> <!-- 숫자만 입력가능, dan=$_REQUEST (dan)부분에 입력, required속성->bool속성 -->
<input type="submit" value="제출숫자""><br> <!-- 클릭하여 보내는 방식, value=박스에 들어갈 이름-->
</form>
<table border = "1"> <!-- 구구단 박스 크기 -->

<?php
if($dan < 10){  // $dan 10이하일때..	
for($nNumber =1; $nNumber<=9; $nNumber++){ //1~9까지 증가
	$nNumber2 = $dan * $nNumber;     //nNumber2 = dan값과 증가값 곱
	print "<tr><td> $dan X $nNumber = $nNumber2</td></tr><br>"; 

}
}
?>
</table>
</body>
</html>





<!----- while문 시작----->

<?php
try 
{
$dan = $_REQUEST["dan"];   //네임 dan값을 받아 $dan 변수값으로 지정
	if($dan > 9) throw new Exception("10단부터는 없습니다.");  // 9보다 큰 수 입력시 (10단부터는 없습니다.)를 e값에 저장
}
	catch(Exception $e) // e예외 지정
{
	echo $e->getMessage()."<br>"; //e값을 받아와 출력
	exit; //9보다 큰 수가 나오면 아래 실행X
}
?>
<html>
<head>
<meta charset = "UTF-8"> <!-- 언어 UTF-8 지정 -->
<meta name = "viewport" content = "width=device-width, initial-scale=1"> <!-- 글꼴 값 지정 -->
<title><?=$nNumber?> while문 구구단표</title> <!-- 브라우저 탭의 제목 설명 -->
<style>
table {padding : 10px; border-collapse: collapse} 
</style> <!-- 테이블 박스 및 크기 지정 -->
</head>
<body>
<h2>while문 구구단표 출력</h2>
<form method=get action = "test.php"> <!-- method= 데이터 전송 방법, action=서버로 전송한 데이터를 수신할 url -->
구구단 입력:
<input type="number" name="dan" required = "required"><br> <!-- 숫자만 입력가능, dan=$_REQUEST (dan)부분에 입력, required속성->bool속성 -->
<input type="submit" value="제출숫자""><br> <!-- 클릭하여 보내는 방식, value=박스에 들어갈 이름-->
</form>
<table border = "1"> <!-- 구구단 박스 크기 -->

<?php
$nNumber=0;					//변수값은 0
while($nNumber<10){			//변수값이 10아래일때까지
$value = $dan * $nNumber;	// 입력받은 dan값과 nNumber값과 곱한 값이 value값
print "<tr><td> $dan X $nNumber = $value</td></tr><br>";		//결과값 출력
	$nNumber++;						//nNumber 증가하여 와일문 처음으로 돌아감
}
?>
</table>
</body>
</html>