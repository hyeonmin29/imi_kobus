<html>
<head>
<title>Bob's Auto Parts - Order Results</title>
</head>
<body>
<h1> Bob's AUTO Parts</h1>
<h2>Order Result</h2>
<p>Order Processed.</p>

<form action = "qwe.php" method = "post">
<table border="0">
<tr bgcolor = "#cccccc">
<td width = "150">Item</td>
<td width = "15">Quantity</td>
</tr>
<tr>
<td>Tires</td>
<td align="left"><input type="next" name="tireqty" size="3"
maxlength="3" /></td>
</tr>
<tr>
<td>oil</td>
<td align = "left"><input type = "text" name = "oilqty" size="3"
maxlength="3" /></td>
</tr>
<td>Spark Plugs</td>
<td align = "left"><input type = "text" name= "sparkqty" size="3"
maxlength="3" /></td>
<tr>
<td>Shipping Address</td>
<td align = "left"><input type = "text" name= "shippqty" size="20"
maxlength="20"/></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" value="Submit Order" /></td>
</tr>
</table>
</form>

<?php
//rq.txt를 읽는 형태로 파일을 엽니다.
// r = 읽기전용
// a = 쓰기전용모드로 열리나 기존 파일 내용 유지, w = 쓰기전용모드로 열리나 기존 파일 내용 삭제
// x = 새로운 파일을 쓰기 전용 모드로 엽니다.(같은 이름 존재 시 false 또는 error발생)
// r+ = 파일을 읽거나 쓸 수 있는 모드(파일 포인터는 맨 처음에 위치)
// w+ = 파일을 읽거나 쓸 수 있는 모드(기존 파일의 내용은 삭제)
// a+ = 파일을 읽거나 쓸 수 있는 모드(기존 파일의 내용은 유지)
// x+ = 새로운 파일을 읽기/쓰기 모드로 엽니다(기존 파일의 경우 flase/error발생)
$fpTextfile = fopen('rq.txt','r') or die ('파일을 열 수 없습니다.');
$nNumber1=1;

do{
	//fgets는 while루프와 함께 사용하여 php에서 텍스트 파일을 한 줄씩 읽는다. 없으면 첫번째 줄에서 끝
	//줄이 있으면 반환, 없으면 false
	$fpLine = fgets($fpTextfile);
	echo($nNumber1." ".$fpLine)."<br>";
	$nNumber1++;
}while($nNumber1<4);
//파일 닫기(자원 관리 위해 필수)
fclose($fpTextfile);

echo "<br>";
//한줄씩 읽는다 가장 기본
fgets() 
//태그 제거
fgetss()
//구분 문자로 나눠 배열에 저장
fgetcsv()

//파일 전체 읽기
//int형 바로출력
readfile()
//boolean형 바로 출력
fpassthru()
//array형 변수 저장
file()
//string형 변수 저장
file_get_contents()


//한글자씩 읽기
fgetc()
//임의의 읽기
fread()
//파일존재여부
file_exists()
//파일 크기
filesize()
//파일 지우기
unlink()
//파일 내부 탐색(파일 포인터를 시작으로 옮긴다)
rewind()
//파일 내부 탐색(현재 파일 포인터의 위치를 바이트 값으로 나타낸다)
ftell()
//파일 포인터를 파일의 다른 위치로 옮긴다.
//seek_set = 파일의 처음, seek_cur = 현재 위치, seek_end 파일의 끝
fseek()

?>

</body>
</html>


