<?php
$rgArr = array(1, 5, 7, 3, 3, 1, 2);
$rgArr2 = array(7, 5, 1, 331, 34, 12, 21);

//배열 확인
count($rgArr); 
//요소 값이 몇개인지 확인
sizeof($rgArr); 
// 요소 개수가 몇개인지 확인
array_count_values($rgArr);
// 요소 값이 몇 번 등장하는지 확인
sort($rgArr);
//배열 요소 값을 순차적으로 배열
sort($rgArr, SORT_NUMERIC);
//배열 요소를 숫자크기로 비교
sort($rgArr, SORT_STRING);
//배열 요소를 문자열로 비교
asort($rgArr);
//요소 값을 기준으로 배열 정렬
$rgMerge = array_merge($rgArr, $rgArr2);
//두 배열을 하나로 합치기
array_push($rgArr, 123,4);
//배열에 2가지 요소를 추가
array_reverse($rgArr);
//배열 역순으로 정렬

$rgArry= array();
$rgArry[0] = 1;
$rgArry[1] = 2;
$rgArry[2] = 3;
$rgArry[3] = 4;
$rgArry[4] = 5;

$rgReverse = array_reverse($rgArry);
echo $rgArry[4]."<br>".$rgArry[3]."<br>"
	.$rgArry[2]."<br>".$rgArry[1]."<br>".$rgArry[0]."<br>";


$rgArr = array();
$rgArr [0] = "apple";
$rgArr [1] = "banana";
$rgArr [2] = "orange";
//mango, grape 2가지 요소를 추가
array_Push ($rgArr, "mango","grape");
//3번 요소(mango) 삭제
unset($rgArr[3]);
echo $rgArr[0]."<br>".$rgArr[1]."<br>".$rgArr[2]."<br>".$rgArr[3]."<br>".$rgArr[4]."<br>";


$rgArr = array("foo", "bar", "hello", "world");
//문자열을 정수형으로 변환
var_dump($rgArr);
?>