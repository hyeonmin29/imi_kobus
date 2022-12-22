<?php
try{
 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );

$fpRqtxt="rq.txt";

//rq.txt를 쓰기 모드로 열거나 오류가 나면 파일을 열 수 없습니다가 출력
$fpOpen = fopen('rq.txt','w')
if(!$fpOpen){
	throw new Exception('파일이 존재하지 않습니다.');
}
//r = 파일 읽기전용 쓰기 불가
//w = 파일 쓰기 전용 기존 파일 있으면 내용 지우고 작성
//a = 파일 쓰기 전용으로 열기 기존 파일 있으면 뒤에 덧붙임
//r+ = 파일 읽고 쓰기 가능 기존 파일 있을 때 내용 지우고 작성
//a+ = 파일 읽고 쓰기 가능 기존 파일 있을 때 뒤에 덧붙임

//rq.txt 파일의 내용을 hi123으로 변경
$fpWrite= fwrite($fpOpen,'hi123');
//rq.txt 파일의 내용을 hi put contents로 변경
$fpPut=file_put_contents($fpRqtxt,"hi put comtents");
//url을 입력 시 페이지가 해당 페이지 정보를 받아와서 출력 -> 사용하는게 다르기때문에 완전히 똑같지 않을 수 있음
echo file_get_contents('https://www.google.com/');
fclose($fpOpen);

//파일의 사이즈를 바이트 값으로 리턴
echo filesize($fpRqtxt);

//파일이 존재하는지 알아보기
if(file_exists($fpRqtxt)){
	echo "존재";
}else{
	throw new Exception("존재하지 않는 파일");
}


//fgets() = 한 번에 한 줄씩 읽기
//fgets는 열린 파일을 2번째 파라미터 값 바이트만큼 읽지만 -1된 바이트 만큼 읽습니다.
//원하는 바이트 수 만큼 읽고 줄이 바뀌거나 파일을 다 읽으면 종료
$fpFgets = fgets($fpOpen,10);


//readfile(),fpassthru(),file() 한 번에 전체 읽기
//한줄로 파일을 전체 읽은 후 닫음
readfile($fpRqtxt);
//포인터의 위치에서부터 파일을 읽음
fpassthru($fpOpen);
//fpFileArray이라는 변수에 파일을 읽어들이고, 파일 한 줄 한 줄이 배열의 한 요소가 된다.
$fpFileArray=file($fpRqtxt);


//파일을 지울 때 사용 / 지울 수 없는 경우나 파일이 없을 때 false값 리턴
// unlink($fpRqtxt);


//포인트 위치 시작으로 옮김
echo rewind($fpRqtxt);
//포인트 위치를 원하는 위치로 옮김 (바이트만큼) 
echo fseek($fpTextfile,9);
//현재 파일 포인터 위치를 바이트 값으로 나타냄
//fgets로 10바이트-1만큼 옮긴 후 확인해보면 결과값은 9가 나온것을 확인 할 수 있음
fgets($fpOpen,10);
echo ftell($fpOpen);
//fread = 현재 포인트부터 11번까지 읽어서 출력
echo fread($fpOpen,11);




//열린 파일의 쓰기 락 ->파일을 공유 불가
flock($fpOpen, LOCK_EX);
//걸어놓은 락을 푼다.
flock($fpOpen, LOCK_UN);
//열린 파일 읽기 락 ->파일 공유 가능
flock($fpOpen, LOCK_SH);
//락을 걸기 위해 스크립트가 정지하는 것을 막는다.
flock($fpOpen, LOCK_NB);



}
catch(Exception $e){
	echo $e->getMessage()."<br>";
	echo $e->getFile()."<br>";
	echo $e->getLine()."<br>";
	exit;
}


?>

-------------------------------------------------------------------------------------------

<?php
try
{
error_reporting( E_ALL );
ini_set( "display_errors", 1 );


//require를 사용해서 파일을 불러온다.
//require를 통해 불러오는 파일은 확장자를 가리지 않는다.
//require와 include의 기능상 차이는 없으나 에러가 났을 때 require=fatal,include=warning으로 뜨게 된다.
//fatal에러보다 warning에러가 더 심각한 에러 ->require가 더 엄격하게 처리된다.
//include = 포함할 파일이 없어도 다음 코드가 실행되지만 require = 포함할 파일이 없거나 에러이면 다음 코드 실행불가

//f1.php 내용 가져와서 출력
require('f1.php');
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br>";
//require_once는 같은 파일을 여러 번 써도 한 번만 실행되며 reuire문에는 영향을 미치지 않습니다.
//include_once도 동일
require_once('f1.php');
require_once('f1.php');
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//once 문이 requrie문보다 앞에 있으면 once문+require문 출력 2번 출력
require_once('rq.txt');
echo "<br>";
require('rq.txt');
echo "<br>";
require('reusable.php');
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";


//함수선언
//함수 이름은 문자,숫자,_만 사용 가능 숫자로 시작 불가
//함수는 변수와 달리 대소문자를 구분하지 않음
//파라미터 2개를 넣어주고 값을 넣어 출력하는 함수 작성
function fnName($fnName1,$fnName2){
	echo $fnName1." 입니다.<br>";
	echo $fnName2." 입니다.<br>";
}

//홍길동 입니다.  신데렐라 입니다. 2가지 결과가 출력
fnName('홍길동','신데렐라');
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";



//require값과,222 2가지 결과 출력
fnName(require('rq.txt'),222);
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";






//값을 반환하고 종료할 때 return을 사용
function fnPlus($fnPlus1,$fnPlus2){
		if(!is_numeric($fnPlus1) || !is_numeric($fnPlus2)){
			throw new Exception('숫자가 아닙니다.');
		}
		$fnPlus3 = $fnPlus1+$fnPlus2;
		echo $fnPlus1." + ".$fnPlus2." = ".$fnPlus3."<br>";
		return $fnPlus3;
}
//1+2=3 출력

fnPlus(4,1);
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";
}

catch(Exception $e){
	echo $e->getMessage()."<br>";
	echo $e->getFile()."<br>";
	echo $e->getLine()."<br>";
	exit;
}


?>