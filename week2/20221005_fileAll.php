<?php
try 
{

 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );

//1~10까지 증가하는 배열 //문자열도 가능 range(1,10,2)==>1~10까지 2씩 증가하는 배열
$rgRange = range(1,10); 
for($i=0; $i<sizeof($rgRange); $i++){
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
				   array('SPK','Spark Plugs',4)
				   );

// 위의 내용과 동일하게 출력 배열이 길어지면 for문 사용이 적절
for($row = 0; $row<sizeof($rgProduct); $row++){
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


$rgArray = array(100,'나',10,4,'a','b','가','W');
//배열 정렬 숫자 맨 앞, 영어는 대문자가 먼저
sort($rgArray);
$sizeof=sizeof($rgArray);
for($i=0; $i<$sizeof; $i++){
	echo $rgArray[$i].'<br>';
}
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
for($i=0; $i<10; $i++){
	//push와 반대로 pop이 있는데, 배열 끝에서 한 요소를 리턴한 후 배열에서 없앤다.
	array_push($rgNumbers, $i);
	echo $rgNumbers[$i]."<br>";
}
echo "<br>";

$rgNumbers1 = array();
//range(10,0,-1)이면 10~1까지 1씩 줄어드는 내림차순 배열
$rgNumbers1 = range(1,10);
$rgNumbers1 = array_reverse($rgNumbers1);
$sizeof=sizeof($rgNumbers1);
for($i=0; $i<$sizeof; $i++){
	echo $rgNumbers1[$i]."<br>";
}
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




//요소 값이 몇개인지 확인
count($rgArr); 
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
array_search();
//배열에 값이 존재하는지 점검
in_array();
//배열간의 차이를 계산
array_diff();
//배열의 키값을 반환한다, ->특정 배열값의 키값을 리턴받을 수 있다.
array_keys();
//
array_pop();
//배열 내의 값들을 계산
array_sum();
//배열에서 중복값 제거
array_unique();
//배열의 일부를 추출
array_slice();
//배열 내부 포인터가 가리키고 있는 원소의 키값을 가져온다.
key();






throw new Exception("예외처리"); 
}
catch(Exception $e){
	echo $e->getMessage()."<br>";
	echo $e->getFile()."<br>";
	echo $e->getLine()."<br>";
	exit;
}
?>


//------------------------------------------------------------------배열-------------------------------------------

<?php
 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );

$fpRqtxt="rq.txt";
//rq.txt를 쓰기 모드로 열거나 오류가 나면 파일을 열 수 없습니다가 출력
$fpTextfile = fopen('rq.txt','w') or die ('파일을 열 수 없습니다.');
//rq.txt 파일의 내용을 hi123으로 변경
$fpWrite= fwrite($fpTextfile,'hi123');
//rq.txt 파일의 내용을 hi put contents로 변경
$fpPut=file_put_contents($fpRqtxt,"hi put comtents");

//url을 입력 시 페이지가 해당 페이지 정보를 받아와서 출력 -> 사용하는게 다르기때문에 완전히 똑같지 않을 수 있음
echo file_get_contents('https://www.google.com/');



//파일의 사이즈를 바이트 값으로 리턴
echo filesize($fpRqtxt);

//파일의 끝 알아보기 끝 검사할 때 while(feof($파일))
feof();


//fgets(),fgetss(),fgetcsv()
$fpFgets = fgets($fpRqtxt,15);

//파일을 지울 때 사용 지울 수 없는 경우나 파일이 없을 때 false값 리턴
// unlink($fpRqtxt);


//포인트 위치 시작으로 옮김
rewind($fpRqtxt);
//포인트 위치를 원하는 다른 위치로 옮김 (파일,원하는 위치부터~원하는 만큼,원하는 위치) 
fseek();
//현재 파일 포인터 위치를 바이트 값으로 나타냄
ftell();

//flock()프로토타입 = (resource fp, int operation[, int &wouldblock])
//락을 얻으면 true 반환 얻지 못하면 false 반환
//LOCK_SH(혹은 1) 읽기 락. 다른 사람이 읽으려 할 때 파일 공유 가능
//LOCK_EX(혹은 2) 쓰기 락. 파일 공유 불가
//LOCK_UN(혹은 3) 걸어놓은 락을 푼다.
//LOCK_NB(혹은 4) 락을 걸기 위해 스크립트가 정지하는 것을 막는다.
?>

-------------------------------------------------------파일-----------------------------------------------

<?php
 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );


//echo와 print 차이 - print는 값 반환이 가능
//%b(이진수) $c(정수해석 문자 하나 출력) %d(숫자) %f(double실수) $s(문자열 해석 문자열 출력) 등


//explode 구분문자를 기준으로 문자열을 부분으로 나눔
//implode, join은 구분문자를 기준으로 문자열을 합쳐 저장한다 ->explode와 반대
$rgName = "Lee1,Hyeon2,Min3";
$strExplode = explode(",",$rgName);
echo "<pre>";
print_r($strExplode);
echo "<pre>";
echo "<br>";


$string = "Hello world. Beautiful day today.";
//strtok 2번째 인자에 있는 문자를 기준으로 문자열 구분
$token = strtok($string, " ");
//" " -> 공백 하나를 기준으로 하기때문에 hello/world./Beautiful/day/today로 나누어짐
while ($token !== false)
{
echo "$token<br>";
$token = strtok(" ");
}
echo "<br>";


//정규표현식
$subject = "Hello PHP is cool!";

// 첫 번째와 세 번째 문자가 영문 소문자이고, 두 번째 문자가 띄어쓰기인 경우를 검색함.
preg_match_all('/[[:lower:]][[:space:]][[:lower:]]/', $subject, $match_01);
var_dump($match_01);
echo "<br><br>";

// 첫 번째 문자가 영문 소문자이고, 두 번째 문자가 띄어쓰기, 세 번째 문자가 영문 대문자인 경우를 검색함.
preg_match('/[[:lower:]][[:space:]][[:upper:]]/', $subject, $match_02);
var_dump($match_02);

//preg_match의 첫 인수는 검색 문자열, 두번째 인수는 검색 대상
// 슬래시(/)=구획문자 -> 메타문자의 역할 따라서 슬래시를 찾기 위해서는 메타문자로서의 효력을 읽헤 해서 다른 문자로 대체
//1=true 반환
echo preg_match("/cat/","i love my cat")."<br>";
//0=false 반환
echo preg_match("/cat/","i love my dog")."<br><br>";
//preg_match_all과 preg_match의 차이 = preg_mat는 매칭되는 값을 찾게되면 그 시점에서 검색이 종료
//preg_match_all은 매칭되는 모든 값을 찾게 되면 검색 종료
preg_match("/[a-z]ol/",$subject,$match);
var_dump($match)."<br><br>";
//첫 번째가 대문자 두 번째가 소문자 ->He
preg_match_all("/[A-Z][a-z]/","$subject",$match);
var_dump($match)."<br><br>";
//											[]밖에서 사용									[]안에서	
//[[:alnum:]] 알파벳 문자,숫자					// \ 특수문자 무시								\ 특수문자 무시
//[[:alpha:]] 알파벳 문자						// ^ 문자열의 처음에서 일치되어야 함					^ 맨처음 시작되었을때만 not 의미
//[[:lower:]] 소문자							// $ 문자열의 끝에서 일치되어야 함						- 글자의 범위 지정
//[[:upper:]] 대문자							// . 줄바꿈 문자(\n)를 제외한 모든 문자와 동일한 의미
//[[:digit:]] 십진법의 숫자						// | OR, 선택의 의미
//[[:xdigit:]] 16진법의 숫자,문자					// ( 패턴 시작
//[[:punct:]] 구두점							// ) 패턴 끝
//[[:blank:]] 탭,스페이스						// * 0번 이상 반복
//[[:space:]] 공백 문자들						// + 1번 이상 반복
//[[:cntrl:]] 컨트롤 문자들						// { 횟수 지정의 시작
//[[:print:]] 모든 출력 가능한 문자들				// } 횟수 지정의 끝
//[[:graph:]] 스페이스 제외한 모든 출력 가능한 문자		// ? 하위 표션식을 옵션으로 취급

//a~l까지를 뺀 나머지 문자
preg_match_all("/[^a-l]/",$subject,$match);
var_dump($match);
//a~1까지를 뺀 나머지 문자를 찾는 것 이지만 위와 다르게 하나를 찾는 즉시 종료
preg_match("/[^a-l]/",$subject,$match);
var_dump($match);


preg_match_all("/llo/",$subject,$match);
var_dump($match);

//a-e로 해당하는 변수를 공백으로 치환
//ereg_replace가 사라지고 preg_replace로 대체
//preg_replace는 원하는 문자열을 다른 문자열로 치환하는 함수
echo preg_replace("/[A-Z]/", "A",$subject)."<br>";




$strSplit = preg_split("/ /","$subject");
for($i=0; $i<sizeof($strSplit); $i++){
	echo $strSplit[$i]."<br>";
}

?>
----------------------------------------------------문자열--------------------------------------------

