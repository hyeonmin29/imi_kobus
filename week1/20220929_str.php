<?php
//문자열함수

$strTest1 = trim($srtTest);
//문자열 처음과 끝에 있는 공백을 지운다
$strTest2 = ltrim($srtTest); 
//문자열 처음에 있는 공백을 지운다
$strTest3 = chop($srtTest); 
//문자열 끝에 있는 공백을 지운다
$strTest4 = strtoupper($srtTest); 
//모든 알파벳 대문자로
$strTest5 = strtolower($srtTest); 
//모든 알파벳 소문자로
$strTest6 = ucfirst($srtTest);
//문장의 처음이 알파벳이면 그것만 대문자로
$strReplace = str_replace('g','c',$srtTest2); 
//$strTest2 테스트 변수의 g문자열을 c로 바꾸어 리턴
$strTest8 = strlen($srtTest); 
//문자열의 길이를 정수값으로 리턴
$strTest9 = substr($srtTest2,1,3);		
//test2 변수에서 1번째 부터 3개 나열
$strTest10= substr_replace($srtTest2,3,-1);
//3이라는 문자열을 test2 뒤에서 첫번째 문자열 대체

?>