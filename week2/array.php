<?php

 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );

//1~10까지 증가하는 배열 //문자열도 가능 range(1,10,2)==>1~10까지 2씩 증가하는 배열
$rgRange = range(1,10); 
for($i=0; $i<10; $i++){
	echo $rgRange[$i]."<br><br>";
}

$rgPrice = array('Tires'=>100,'Oil'=>10,'Spark Plugs'=>4);

foreach($rgPrice as $rgKey => $rgValue){
	echo $rgKey." : ".$rgValue."<br>";
}


$rgDrink = array('water','ade','coffee');
$rgDrink2 = array('water','ade','coffee');
$rgNumber = array('1','2','3');
//동일한 요소 가지고 있으면 true, 순서가 바뀌면 true 안됨
if($rgDrink == $rgDrink2){
	echo "true<br><br>";	
}

//3개의 배열 저장
$rgProduct = array(array('TIR','Tires',100),
				   array('OIL','Oil',10),
				   array('SPK','Spark Plugs',4));

echo '|'.$rgProduct[0][0].'|'.$rgProduct[0][1].'|'.$rgProduct[0][2].'|<br>';
echo '|'.$rgProduct[1][0].'|'.$rgProduct[1][1].'|'.$rgProduct[1][2].'|<br>';
echo '|'.$rgProduct[2][0].'|'.$rgProduct[2][1].'|'.$rgProduct[2][2].'|<br><br>';

// 위의 내용과 동일하게 출력 배열이 길어지면 for문 사용이 적절
for($row = 0; $row<3; $row++){
	for($column = 0; $column<3; $column++){
		echo '|'.$rgProduct[$row][$column];
	}
	echo "<br>";
}
	echo "<br><br>";

//3차원 배열
$rgProduct2 = array(array(array('TIR','Tires',100),
						  array('OIL','Oil',10),
						  array('SPK','Spark Plugs',4)
						  ),
					array(array('VAN_TIR','Tires',120),
						  array('VAN_OIL','Oil',12),
						  array('VAN_SPK','Spark Plugs',5)
						  ),
					array(array('TRK_TIR','Tires',150),
						  array('TRK_OIL','Oil',15),
						  array('TRK_SPK','Spark Plugs',6)
						  )
				  );

//3차원 배열 for문으로 출력
for($rgLayer = 0; $rgLayer<3; $rgLayer++){
	echo "rgLayer $rgLayer<br>";
	for($rgLow=0; $rgLow<3; $rgLow++){
		for($rgColumn = 0; $rgColumn<3; $rgColumn++){
			echo '|'.$rgProduct2[$rgLayer][$rgLow][$rgColumn];
		}
		echo "|<br>";
	}
}
echo "<br>";


$rgPrice = array(100,10,4,'a','b','W');
//배열 정렬 숫자 맨 앞, 영어는 대문자가 먼저
sort($rgPrice);
echo $rgPrice[0];
echo $rgPrice[1];
echo $rgPrice[2];
echo $rgPrice[3];
echo $rgPrice[4];
echo $rgPrice[5];
echo "<br>";

$rgPrice = array('Tires'=>100,'Oil'=>10,'Spark Plugs'=>4);
//요소의 값에 따라 정렬
asort($rgPrice);
foreach($rgPrice as $rgKey=>$rgValue){
	echo $rgKey." => ".$rgValue."<br>";
}
//알파벳 순서로 정렬
ksort($rgPrice);
foreach($rgPrice as $rgKey=>$rgValue){
	echo $rgKey." => ".$rgValue."<br>";
}
// sort, asort, ksort는 오름차순으로 정렬하지만 rsort,arsort, krsort는 내림차순 정렬

$product = array(array('TRI','Tires',100),
				 array('OIL','Oil',10),
				 array('SPK','Spark Plugs',4)
				 );
echo "<br>";
echo "<br>";


$rgNumbers = array();
for($i=10; $i>0; $i--){
	//push와 반대로 pop이 있는데, 배열 끝에서 한 요소를 리턴한 후 배열에서 없앤다.
	$a = array_push($rgNumbers, $i);
	echo $a."<br>";

}
echo "<br>";

$rgNumbers1 = array();
//range(10,0,-1)이면 10~1까지 1씩 줄어드는 내림차순 배열
$rgNumbers1 = range(1,10);
$rgNumbers1 = array_reverse($rgNumbers1);
for($i=0; $i<10; $i++){
	echo $rgNumbers1[$i]."<br>";
}
echo "<br>";

//explode 구분문자를 기준으로 문자열을 부분으로 나눔
$rgName = "Lee1,Hyeon2,Min3";
$rgExplode = explode(",",$rgName);
echo "<pre>";
print_r($rgExplode);
echo "<pre>";
echo "<br>";

//배열탐색함수
$rgArray = array(1,2,3,4,5,6,7);
//포인터가 가리키는 요소 리턴
$rgCurrent = current($rgArray);
echo $rgCurrent."<br>";
//포인터를 앞으로 한칸 이동
$rgNext = next($rgArray);
echo $rgNext."<br>";
//포인터를 뒤로 한칸 이동
$rgPrev = prev($rgArray);
echo $rgPrev."<br>";
//포인터를 맨 뒤로 이동
$rgEnd = end($rgArray);
echo $rgEnd."<br>";
//배열 현재 요소 리턴
$rgPos = pos($rgArray);
echo $rgPos."<br>";
//배열 첫번째 요소 리턴
$rgReset = reset($rgArray);
echo $rgReset."<br>";
echo "<br>";



//array_count_values()함수는 배열에 유일한 값이 몇개 있는지 1,2,3,1 =>1 2개/2 1개/3 1개
//count, sizeof-------------------------------------------------------------------

//extract 배열을 스칼라 변수로 변환하는 함수
//extract는 배열의 키가 변수 이름인 스칼라 변수로 만든다.
$rgExtract = array('i1' => '1', 'i2' => '2', 'i3' => '3', 'i4' => '4', 'i5' => '5');
extract($rgExtract);
echo "$i1 $i2 $i3 $i4 $i5";



//문자열 앞뒤 모든 공백 삭제
trim($srtTest);
//문자열 왼쪽 모든 공백 삭제
ltrim($strTest);
//문자열 오른쪽 모든 공백 삭제
rtrim($strTrst);
//
chop($strTest);

?>