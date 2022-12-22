<?php
Define('LPG',998); //상수 대입
// "------------Quit--------------<br>"; //상수 종료

$lang = "php"; //변수 lang은 php
$strName = "아이템매니아"; //변수 strName은 아이템매니아
$g_nA = "54"; //변수 a는 54			 //참조 연산자 $g_nA =g_nB; 가능  b값 복사하여 a에 대입 가능 
									// $g_nA = &$g_nB;  $a = 7; 일 경우 a와 b 모두 7
$g_nB = "2"; //변수 b는 2            //unset($g_nA);로 둘의 관계 떼어낼 수 있음
$nResult1 = $g_nA + $g_nB; // 변수 result1는 변수 A와 B의 덧셈
$nResult2 = $g_nA - $g_nB; // 변수 result2는 변수 A와 B의 뺄셈
$nResult3 = $g_nA * $g_nB; // 변수 result3는 변수 A와 B의 곱셈
$nResult4 = $g_nA / $g_nB; // 변수 result4는 변수 A와 B의 나눗셈
$nResult5 = $g_nA % $g_nB; // 변수 result5는 변수 A와 B의 나머지
//"------------Quit--------------"; //변수 선언 종료


// ++$A; //선 증가 연산자 출력 (증가하여 출력)
// $A++; //후 증가 연산자 출력 (본인 먼저 출력 후 증가시켜 출력)
// --$B; //선 감소 연산자 출력 (감소하여 출력)
// $B--; //후 감소 연산자 출력 (본인 먼저 출력 후 감소하여 출력)
//"------------Quit--------------"; //증감 연산자 종료


echo $g_nA += $g_nB; //양쪽 변수를 더한 값을 왼쪽 변수에 할당
echo $g_nA -= $g_nB; //양쪽 변수를 뺀 값을 왼쪽 변수에 할당
echo $g_nA *= $g_nB; //양쪽 변수를 곱한 값을 왼쪽 변수에 할당
echo $g_nA /= $g_nB; //양쪽 변수를 나눈 값을 왼쪽 변수에 할당
echo $g_nA %= $g_nB; //양쪽 변수를 나눈 나머지 값을 왼쪽 변수에 할당
echo $g_nA .= $g_nB; //접합한 값을 왼쪽 변수에 할당
//------------Quit--------------<br>"; //복합 연산자 종료

$n_1 = 3;
$n_2 = 3.0;
$n_3 = 3;
$n_4 = 7;
echo $n_1 == $n_2; //등위 연산자로서 같기 때문에 true 1반환
echo $n_1 === $n_2; //같은 형 같은 값이 아니기 때문에 반환하지 않음 false
echo $n_1 == $n_3; //등위 연산자로서 같기 때문에 true 1반환
//------------Quit--------------<br>"; // 비교 연산자 종료


$langName = $lang .$strName; //문자열 연산자를 통해 문자열을 변수lang과 strName의 문자열을 붙임
echo " language $lang <br>";
echo $lang." ".$strName ;
echo " $langName<br>"; //문자열 연산자 .을 통해 새로운 문자열 생성
echo strlen("$strName"); // string 함수 이용하여 "아이템매니아" 길이 반환 ->문 자열이 언제 끝나는지 아는 것이 중요할 때

$strTest='     아이템 매니아 Itemmania ';
$strTest1 = trim($strTest); //문자열 처음과 끝에 있는 공백을 지운다
$strTest2 = ltrim($strTest);  //문자열 처음에 있는 공백을 지운다
$strTest3 = chop($strTest); //문자열 끝에 있는 공백을 지운다
$strTest4 = strtoupper($strTest); //모든 알파벳 대문자로
$strTest5 = strtolower($strTest); //모든 알파벳 소문자로
$strTest6 = ucfirst($strTest); //문장의 처음이 알파벳이면 그것만 대문자로

$strTest7='Change';
$res = str_replace('g','c',$strTest7);  //$strTest2 테스트 변수의 g문자열을 c로 바꾸어 리턴

$strTest8 = strlen($test); //문자열의 길이를 정수값으로 리턴


//"------------Quit--------------<br>"; //문자열 연산자 종료


//--------------if, else, elseif--------------

if($n_1 > $n_4){               //n1 > n4 이면..
   echo "1번이 4번보다 많다";         //1번이 4번보다 많다 출력
}else{                        //만약
   if($n_1 < $n_4)
      echo "1번이 4번보다 적다";
   if($n_1 == $n_2)
      echo "1번과 2번의 값은 동일하다";
}

//-------------------------------------------

$nPoint = 90;
$nLine = 80;
$nUnderline = 40;

if($nPoint > $nLine){            //nPoint가 nLine보다 클 경우 합격
   echo '합격';
}elseif($nPoint > $nUnderline){      //nPoint가 nUnderline보다 클 경우 추가시험
   echo '추가 시험';
}else{                        //다 아닐 경우 낙
   echo '낙';
}

//---------------------------------------------

$nT = 10000;   //타이어 가격
$nCount = 59;   //개수
if($nCount <= 10){                     //10개 이하이면..
   $nDiscount = 0;                     //할인율 0%
   echo "할인율은 0%이며 가격은 ";
   echo $nT * $nCount - ($nT * $nCount * $nDiscount);    //가격 * 개수 -(가격 * 개수 * 할인율)
   echo "입니다<br>";
}elseif(($nCount>=10) &&($nCount<=49)){      //10개 이상 49개 이하이면..      
   $nDiscount = 5/100;                  //할인율 5%
   echo "할인율은 5%이며 가격은 ";
   echo $nT * $nCount - ($nT * $nCount * $nDiscount);
   echo "입니다<br>";
}elseif(($nCount>=50) &&($nTire<=99)){      //50개 이상 99개 이하이면..
   $nDiscount = 10/100;               //할인율 10%
   echo "할인율은 10%이며 가격은 ";
   echo $nT * $nCount - ($nT * $nCount * $nDiscount);
   echo "입니다<br>";
}elseif($nCount>=100){                  //100개 이상이면..
   $nDiscount = 15/100;               //할인율 15%
   echo "할인율은 15%이며 가격은 ";
   echo $nT * $nCount - ($nT * $nCount * $nDiscount);
   echo "입니다<br>";
}


//-----------swich----------------

$var = '남자';                  
switch($var){                  //조건변수에 var를 넣어 비교
   case"남학생":                  //조건변수와 비교값 일치 X
      echo "var는 남학생입니다.<br>";      //일치하지 않아 출력 X
   break;                     //케이스문이 실행되지않아 break 되지 않고 다음 case문으로 넘어감
   case"남자":                  //조건변수와 비교값이 일치
      echo "var는 남자입니다.<br>";      //일치하여 출력 O
   break;                     //케이스문 정상출력으로 break, break넣지 않으면 다음 케이스문까지 실행
   case"사람":
      echo "var는 사람입니다.<br>";
   break;
   default:                           //조건변수와 모든 비교값이 일치하지 않으면 default값 출력
      echo "var는 남학생, 남자, 사람 아닙니다<br>";
   break;
}
//"------------Quit--------------<br>"; //조건문 종료

//------------------for문 시작----------------------

for($nValue=0; $nValue<=7; $nValue++){ //0~7까지 1씩 증가하는 반복문   for(초기값; 조건식; 증감식;)
   echo "".$nValue." ";
}

for($nValue=0; $nValue<=10; $nValue++){ //0~10까지 1씩 증가하는 반복문   for(초기값; 조건식; 증감식;)
   if($nValue % 2 == 1 ){          //변수값을 2로 나누었을 때 나머지가 1인 값
      continue;                //건너뛴다
   }
   echo "".$nValue." ";                //남은 countinue 거치고 남은 값 출력 (짝수)
}

for($nValue=0; $nValue<=10; $nValue++){ //0~10까지 1씩 증가하는 반복문   for(초기값; 조건식; 증감식;)
   if($nValue % 2 == 1 ){          //변수값을 2로 나누었을 때 나머지가 1인 값
      continue;                //건너뛴다
   }
   if($nValue > 6 ){             //변수값이 6보다 크면
         break;                //break
   }
   echo "".$nValue." ";                //countinue, break 거치고 남은 값 출력 (짝수)
}

//-----------------while문 시작---------------------

$nValue = 0;
while($nValue <= 6){		//nValue값이 0~6까지,,
   echo "".$nValue." ";
   $nValue++;				//증가
}
echo "<br>";

$nValue = 0;
while($nValue <=10){            //10이하값까지
   if($nValue % 2 == 0 ){         //2로 나누었을 때 0인값만
      $nValue++;               //처리 스크립트 부분에서 증감식을 사용하기 때문에 컨티뉴 전에 증감식 사용해야함
      continue;               //건너뛴다
   }
   if($nValue > 7){            //7보다 클 때
      $nValue++;               //처리 스크립트 부분에서 증감식을 사용하기 때문에 컨티뉴 전에 증감식 사용해야함
      break;                  //멈춘다
   }
   echo "".$nValue." ";         //2로 나누었을 때 0인값(짝수)을 제외하고 7보다 크면 멈춘 값을 출력 0~6 중 짝수만 출력
   $nValue++;
}
//"------------Quit--------------<br>"; //반복문 종료
}
?>

<?php

//문자열함수
$test='     아이템 매니아 ItemMania ';
$Test1 = trim($test); //문자열 처음과 끝에 있는 공백을 지운다
echo $Test1."<br><br>";
$Test2 = ltrim($test);  //문자열 처음에 있는 공백을 지운다
echo $Test2.'<br><br>';
$Test3 = chop($test); //문자열 끝에 있는 공백을 지운다
echo $Test3.'<br><br>';
$strTest4 = strtoupper($test); //모든 알파벳 대문자로
echo $strTest4.'<br><br>';
$strTest5 = strtolower($test); //모든 알파벳 소문자로
echo $strTest5.'<br><br>';
$strTest6 = ucfirst($test); //문장의 처음이 알파벳이면 그것만 대문자로
echo $strTest6.'<br><br>';
$test2='Change';
$strReplace = str_replace('g','c',$test2);  //$strTest2 테스트 변수의 g문자열을 c로 바꾸어 리턴
echo $strReplace.'<br><br>';
$strTest8 = strlen($test); //문자열의 길이를 정수값으로 리턴
echo $strTest8."<br><br>";
$strTest9 = substr($test2,1,3);		//test2 변수에서 1번째 부터 3개 나열
echo $strTest9."<br><br>";
$strTest10= substr_replace($test2,3,-1);	//3이라는 문자열을 test2 뒤에서 첫번째 문자열 대체
echo $strTest10;
?>

<?php
$rgNumber1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9");
$rgNumber2 = array("1", "2", "3", "4", "5", "6", "7", "8", "9");
foreach($rgNumber1 as $key => $val){
	echo "".$val." 단<br>";	
	foreach($rgNumber2 as $key2=>$val2)	
	{
		echo $val ." X ". $val2 ." = ".($val*$val2)."<br>";
	}
}
?>

<?php
//문자열함수
$strTest=' 아이템 매니아 ItemMania ';
$Test1 = trim($strTest);
//문자열 처음과 끝에 있는 공백을 지운다
$Test2 = ltrim($strTest);  
//문자열 처음에 있는 공백을 지운다
$Test3 = chop($strTest); 
//문자열 끝에 있는 공백을 지운다
$strTest4 = strtoupper($strTest); 
//모든 알파벳 대문자로
$strTest5 = strtolower($strTest); 
//모든 알파벳 소문자로
$strTest6 = ucfirst($strTest); 
//문장의 처음이 알파벳이면 그것만 대문자로
$strReplace = str_replace('g','c',$strTest2);  
//$strTest2 테스트 변수의 g문자열을 c로 바꾸어 리턴
$strTest8 = strlen($strTest); 
//문자열의 길이를 정수값으로 리턴
$strTest9 = substr($strTest2,1,3);		
//strTest2 변수에서 1번째 부터 3개 나열
$strTest10= substr_replace($strTest2,3,-1);	
//3이라는 문자열을 strTest2 뒤에서 첫번째 문자열 대체

try{
	if($nNumber > 9){
	//예외 처리하길 원하는 실행코드
	throw new Exception("예외 발생");
	}
}
//(매개변수 지정)
catch(Exception $e){
	echo $e->getMessage();
	echo $e->getLine();
	echo $e->getFile();
	exit; 

}
>?