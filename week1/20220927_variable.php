<?php
Define('LPG',998); //상수 대입

try //트라이 캐치(예외 발생 시 개발자가 직접 처리할 수 있도록 제공하는 구문)
{

   echo LPG; //상수 출력
echo "<br>";
echo "------------Quit--------------<br>"; //상수 종료

$lang = "php"; //변수 lang은 php
$strName = "아이템매니아"; //변수 strName은 아이템매니아

$g_nA = "54"; //변수 a는 54                   //참조 연산자 $g_nA =g_nB; 가능  b값 복사하여 a에 대입 가능 // $g_nA = &$g_nB;  $a = 7; 일 경우 a와 b 모두 7
$g_nB = "2"; //변수 b는 2                                                                    //unset($g_nA);로 둘의 관계 떼어낼 수 있음
$nResult1 = $g_nA + $g_nB; // 변수 result1는 변수 A와 B의 덧셈
$nResult2 = $g_nA - $g_nB; // 변수 result2는 변수 A와 B의 뺄셈
$nResult3 = $g_nA * $g_nB; // 변수 result3는 변수 A와 B의 곱셈
$nResult4 = $g_nA / $g_nB; // 변수 result4는 변수 A와 B의 나눗셈
$nResult5 = $g_nA % $g_nB; // 변수 result5는 변수 A와 B의 나머지
//"------------Quit--------------"; //변수 선언 종료


echo "$nResult1 "; //A와 B의 덧셈
echo "$nResult2 "; //A와 B의 뺄셈
echo "$nResult3 "; //A와 B의 곱셈
echo "$nResult4 "; //A와 B의 나눗셈
echo "$nResult5<br>"; //A와 B의 나머지
echo "------------Quit--------------<br>"; //산술 연산자 종료



//echo ++$A; //선 증가 연산자 출력 (증가하여 출력)
//echo $A++; //후 증가 연산자 출력 (본인 먼저 출력 후 증가시켜 출력)
//echo --$B; //선 감소 연산자 출력 (감소하여 출력)
//echo $B--; //후 감소 연산자 출력 (본인 먼저 출력 후 감소하여 출력)
//"------------Quit--------------"; //증감 연산자 종료


echo $g_nA += $g_nB; //양쪽 변수를 더한 값을 왼쪽 변수에 할당
echo "<br>";
echo $g_nA -= $g_nB; //양쪽 변수를 뺀 값을 왼쪽 변수에 할당
echo "<br>";
echo $g_nA *= $g_nB; //양쪽 변수를 곱한 값을 왼쪽 변수에 할당
echo "<br>";
echo $g_nA /= $g_nB; //양쪽 변수를 나눈 값을 왼쪽 변수에 할당
echo "<br>";
echo $g_nA %= $g_nB; //양쪽 변수를 나눈 나머지 값을 왼쪽 변수에 할당
echo "<br>";
echo $g_nA .= $g_nB; //접합한 값을 왼쪽 변수에 할당
echo "<br>";
echo "------------Quit--------------<br>"; //복합 연산자 종료

$n_1 = 3;
$n_2 = 3.0;
$n_3 = 3;
$n_4 = 7;
echo $n_1 == $n_2; //등위 연산자로서 같기 때문에 true 1반환
echo $n_1 === $n_2; //같은 형 같은 값이 아니기 때문에 반환하지 않음 false
echo $n_1 == $n_3; //등위 연산자로서 같기 때문에 true 1반환
echo "------------Quit--------------<br>"; // 비교 연산자 종료


$langName = $lang .$strName; //문자열 연산자를 통해 문자열을 변수lang과 strName의 문자열을 붙임
echo " language $lang <br>";
echo $lang." ".$strName ;
echo "<br>";
echo " $langName<br>"; //문자열 연산자 .을 통해 새로운 문자열 생성
echo strlen("$strName"); // string 함수 이용하여 "아이템매니아" 길이 반환 ->문 자열이 언제 끝나는지 아는 것이 중요할 때
echo "<br>";
echo "------------Quit--------------<br>"; //문자열 연산자 종료


//--------------if, else, elseif--------------

if($n_1 > $n_4){               //n1 > n4 이면..
   echo "1번이 4번보다 많다";         //1번이 4번보다 많다 출력
}else{                        //만약
   if($n_1 < $n_4)
      echo "1번이 4번보다 적다";
   if($n_1 == $n_2)
      echo "1번과 2번의 값은 동일하다";
}
echo "<br>";

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
echo "<br>";

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

echo "------------Quit--------------<br>"; //조건문 종료

//------------------for문----------------------

for($nValue=0; $nValue<=7; $nValue++){ //0~7까지 1씩 증가하는 반복문   for(초기값; 조건식; 증감식;)
   echo "".$nValue." ";
}
echo "<br>";

for($nValue=0; $nValue<=10; $nValue++){ //0~10까지 1씩 증가하는 반복문   for(초기값; 조건식; 증감식;)
   if($nValue % 2 == 1 ){          //변수값을 2로 나누었을 때 나머지가 1인 값
      continue;                //건너뛴다
   }
   echo "".$nValue." ";                //남은 countinue 거치고 남은 값 출력 (짝수)
}
echo "<br>";

for($nValue=0; $nValue<=10; $nValue++){ //0~10까지 1씩 증가하는 반복문   for(초기값; 조건식; 증감식;)
   if($nValue % 2 == 1 ){          //변수값을 2로 나누었을 때 나머지가 1인 값
      continue;                //건너뛴다
   }
   if($nValue > 6 ){             //변수값이 6보다 크면
         break;                //break
   }
   echo "".$nValue." ";                //countinue, break 거치고 남은 값 출력 (짝수)
}
echo "<br>";
echo "---for---<br>";

//-----------------while문---------------------

$nValue = 0;
while($nValue <= 6){
   echo "".$nValue." ";
   $nValue++;
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
echo "<br>";

echo "---while문---<br>";
echo "------------Quit--------------<br>"; //반복문 종료



echo $_SERVER['PHP_SELF']; //파일명과 경로 정보(해커 이용 가능성 있음)
echo "<br>";
echo $_SERVER['SERVER_NAME']; //파일 위치
echo "<br>";
echo $_SERVER['HTTP_HOST'];//
echo "<br>";
echo $_SERVER['HTTP_USER_AGENT']; //서버의 모듈 엔진, 관련정보 출력 (어떤 프로그램 돌리고 있는지)
echo "<br>";
echo $_SERVER['SCRIPT_NAME']; //스크립트 파일 이름 출력
echo "<br>";





$user_info['level']=1;
$subject = $_POST['aaa'];


	if($user_info['level'] !=1) throw new Exception('글쓰기 권한이 없습니다.', 0);
	if(!isset($_POST['subject']))throw new Exception('제목을 입력해주세요.', 1);
	if(!isset($_POST['contents']))throw new Exception('내용을 입력해주세요.',2);
	if(!mysql_query($dbh, $sql)) throw new Exception('입력 실패하였습니다.',3);
	echo("성공적으로 입력되었습니다.");
}





catch(Exception $e) //예외(Exception)란 특별한 처리가 필요한 이례적인 상황
{
   switch($e->getcode())
   {
      case 0:
         echo($e->getMessage());
      break;
      case 1:
         echo($e->getMessage());
      break;
      case 2:
         echo($e->getMessage());
      break;
      case 3:
         echo($e->getMessage());
      break;
      case 4:
         echo($e->getMessage());
      break;
   }
}
?>