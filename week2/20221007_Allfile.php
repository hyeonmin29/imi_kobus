<?php
try 
{

 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );


//1~10까지 증가하는 배열 //문자열도 가능 range(1,10,2)==>1~10까지 2씩 증가하는 배열
$rgRange = range(1,10); 
if(sizeof($rgRange)<5 || sizeof($rgRange)>10){
	throw new Exception("배열 요소가 부족합니다.");
}
//1~10까지 순차적으로 출력됩니다.
for($i=0; $i<sizeof($rgRange); $i++){
	echo $rgRange[$i]." ";
	
}
echo "<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//Tires : 100
//Oil : 10
//Spark Plugs : 4로 출력됩니다.
$rgPrice = array('Tires'=>100,'Oil'=>10,'Spark Plugs'=>4);
foreach($rgPrice as $rgKey => $rgValue){
	echo $rgKey." : ".$rgValue."<br>";
}
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";


$rgDrink = array('water',"1.0",'coffee');
$rgDrink2 = array('water',"1.0",'coffee');

//동일한 요소 가지고 있어도 순서가 바뀌면 true 안됨
//
if($rgDrink == $rgDrink2){
	echo "true<br>";
}else{
	throw new Exception("값이 같지 않습니다.");
}
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

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
echo "라인 번호 - ";
echo __LINE__;
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
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";




$rgArray = array(100,'나',10,4,'a','b','가','W');
$sizeof=sizeof($rgArray);
//배열 정렬 숫자 맨 앞, 영어는 대문자가 먼저
sort($rgArray);
//결과값은 숫자/영어대문자/영어소문자/한글 순으로 출력
//4 10 100 W a b 가 나
for($i=0; $i<$sizeof; $i++){
	echo $rgArray[$i].' ';
}
echo "<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//정렬
// sort, asort, ksort는 오름차순으로 정렬하지만
// rsort,arsort, krsort는 내림차순 정렬

$rgPrice = array('Tires'=>100,'Oil'=>10,'Spark Plugs'=>4);
//숫자 값에 따라 정렬
asort($rgPrice);
//Spark Plugs = 4  /  Oil = 10  /  Tires = 100 순서
foreach($rgPrice as $rgKey=>$rgValue){
	echo $rgKey." = ".$rgValue."<br>";
}
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";


//알파벳 순서로 정렬
ksort($rgPrice);
//Oil => 10  /  Spark Plugs => 4  /  Tires => 100 순서
foreach($rgPrice as $rgKey=>$rgValue){ 
	echo $rgKey." => ".$rgValue."<br>";
}
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";


//array_@@@@함수
$rgNumbers = array();
for($i=0; $i<10; $i++){
	//0~9까지 순차적으로 rgNumbers에 요소 추가
	array_push($rgNumbers, $i);
	echo $rgNumbers[$i]." ";
}
echo "<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";


//push와 반대로 pop이 있는데, 배열 끝에서 한 요소를 리턴한 후 배열에서 없앤다.
$rgNumbers2 = array(1,2,3,4,5,6,7);
//array_pop은 배열 마지막 인수 삭제 (7)
array_pop($rgNumbers2);
//1~6까지 출력
for($i=0; $i<sizeof($rgNumbers2); $i++){
	echo $rgNumbers2[$i]." ";
}
echo "<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";



$rgNumbers1 = array();
//range(10,0,-1)이면 10~1까지 1씩 줄어드는 내림차순 배열
$rgNumbers1 = range(1,10);
$rgNumbers1 = array_reverse($rgNumbers1);
$sizeof=sizeof($rgNumbers1);
for($i=0; $i<$sizeof; $i++){
	echo $rgNumbers1[$i]." ";
}
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";



//배열탐색함수
$rgArray = array(1,2,3,4,5,6,7);
//포인터가 가리키는 요소 리턴
$rgCurrent = current($rgArray);
echo $rgCurrent." current 값<br>";
//포인터를 앞으로 한칸 이동
$rgNext = next($rgArray);
echo $rgNext." next 값<br>";
//포인터를 뒤로 한칸 이동
$rgPrev = prev($rgArray);
echo $rgPrev." prev 값<br>";
//포인터를 맨 뒤로 이동
$rgEnd = end($rgArray);
echo $rgEnd." end 값<br>";
//배열 현재 요소 리턴
$rgPos = pos($rgArray);
echo $rgPos." pos 값<br>";
//배열 첫번째 요소 리턴
$rgReset = reset($rgArray);
echo $rgReset." reset 값<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//extract 배열을 스칼라 변수로 변환하는 함수
//extract는 배열의 키가 변수 이름인 스칼라 변수로 만든다.
$rgExtract = array('i1' => '1', 'i2' => '2', 'i3' => '3', 'i4' => '4', 'i5' => '5');
extract($rgExtract);
//1 ,2 ,3 ,4, 5 출력
echo "$i1 $i2 $i3 $i4 $i5 <br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";




$rgArr1 = array('아','이','엠','아','이','1');
$rgArr2 = array('I','T','E','M','1'); 

//요소 값이 몇개인지 확인
echo count($rgArr1)." count 값<br>"; 
echo sizeof($rgArr1)." sizeof 값<br>"; 
// 요소 개수가 몇개인지 확인 "아"2번 / "이"2번 / "엠"1번
$rgACV = array_count_values($rgArr1);
echo var_dump($rgACV)."<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";



//rgArr1배열과 rgArr2배열을 합함
$rgMerge = array_merge($rgArr1, $rgArr2);
for($i=0; $i<sizeof($rgMerge); $i++){
	echo $rgMerge[$i];
}
echo "<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";



//배열에 값이 몇번째 인덱스에 존재하는지 출력 없으면 null
$rgSearch = array_search('이',$rgArr1);
echo $rgSearch."<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

if(in_array('오',$rgArr1)){
	echo "true";
}else{
	echo "false";
}
echo "<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";


//배열간의 차이를 계산 (첫 번째 배열이 기준)
//첫 번째에 넣은 배열 중에서 두 번째에 넣은 배열 값과 중복되지 않는 것만 출력
$rgDiff = array_diff($rgArr1, $rgArr2);
print_r($rgDiff);
echo "<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";


//배열의 키값을 반환한다, ->특정 배열값의 키값을 리턴받을 수 있다.
$rgKeys = array_keys($rgArr1);
//배열 값을 반환한다.
$rgValues = array_values($rgArr1);
echo $rgKeys[1].", ";
echo $rgValues[1]."<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";


$rgArr3 = array(100,20,300,'아이엠아이');
//배열 내의 값들을 계산  숫자가 아닌 경우는 0으로 계산
$rgSum = array_sum($rgArr3);
echo $rgSum."<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//배열에서 중복값 제거 아,이 하나씩 제거
$rgUnique = array_unique($rgArr1);
print_r ($rgUnique);
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//배열의 일부를 추출
//3번 인덱스부터 2개를 추출 //뒤에 true값은 인덱스 번호를 초기화 하지 않게함
$rgSlice = array_slice($rgArr1,3,2,true);
print_r ($rgSlice);
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//배열 내부 포인터가 가리키고 있는 원소의 키값을 가져온다.
next($rgArr1);
$rgKey = key($rgArr1);
//next 함수를 사용해서 포인터를 한칸 옮겨 1이 출력
echo $rgKey;
echo "<br>라인 번호 - ";
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




----------------------------------------------------------------------------------------------------------

<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );


//echo와 print 차이 - print는 값 반환이 가능
//%b(이진수) $c(정수해석 문자 하나 출력) %d(숫자) %f(double실수) $s(문자열 해석 문자열 출력) 등


//explode 구분문자를 기준으로 문자열을 배열로 나눔
$rgName = "Lee1,Hyeon2,Min3";
$strExplode = explode(",",$rgName);
echo "<pre>";
print_r($strExplode);
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";







//implode, join은 구분문자를 기준으로 문자열을 합쳐 저장한다 ->explode와 반대
$rgArray = array('Lee1','Hyeon2','Min3');
$strJoin = join('-',$rgArray);
//출력값 = Lee1-Hyeon2-Min3
echo $strJoin;
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";


$string = "Hello world. Beautiful day today. Hello hi";

//strtok 2번째 파라미터에 있는 문자를 기준으로 문자열 구분
$token = strtok($string, " ");

//" " -> 공백 하나를 기준으로 하기때문에 hello/world./Beautiful/day/today로 나누어짐
//while문을 사용해서 다시 하는 이유는 구분문자로 나누고 첫 문자열만 출력하고 끝나기 때문에
//다음 문자열들도 출력하기 위해 사용  -->explode와 차이점
while ($token !== false)
{
	echo "$token / ";
	$token = strtok(" ");
}
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";


//strstr은 찾는 문자열을 인자에 넣으면 해당 문자가 시작되는 부분부터 출력
//출력값은 day today. hello hi
echo strstr($string,'day');
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//strchr은  처음 검색 문자를 찾은 문자열부터 마지막 문자열까지 출력  값 = Hello~today. Hello~
//strrchr은 검색 문자를 마지막으로 찾은 부분부터 마지막 문자열까지 출력  값 = Hello hi
echo strchr($string, 'Hello');
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

echo strrchr($string, 'Hello');
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//stristr은 대소문자를 구분하지 않아서 h를 찾았을 때 맨 앞 Hello~부터 출력된다.
echo stristr($string, 'h');
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//strpos는 e가 몇 바이트에 있는지 위치를 찾거나 숫자8을 넣어
//8바이트 이상부터 e를 찾게 합니다. 출력값 = 14
echo strpos($string,'e',8);
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//strrpos는 3번째 파라미터 값 이후부터 두번째 파라미터 값을 찾기 시작해서
//마지막에 있는 o문자가 어디에 있는지 바이트값으로 표현해줍니다. 출력 값 = 38
echo strrpos($string,'o',5);
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//H문자열을 a로 치환
//출력값 aello world~~. 대소문자 구분
echo str_replace('H','a',$string);
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//substr_replace는 2번째 파라미터 값을 3바이트 이후부터  3번째 파라미터 값 만큼 삽입
// 출력값은 Hel13world 3바이트 이후부터 3바이트만큼 ->lo(공백)삭제
echo substr_replace($string,'13',3,3);
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";




//정규표현식
$subject = "Hello PHP is cool!";

// 첫 번째와 세 번째 문자가 영문 소문자이고, 두 번째 문자가 띄어쓰기인 경우를 검색함.
preg_match_all('/[[:lower:]][[:space:]][[:lower:]]/', $subject, $match_01);
var_dump($match_01);
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

// 첫 번째 문자가 영문 소문자이고, 두 번째 문자가 띄어쓰기, 세 번째 문자가 영문 대문자인 경우를 검색함.
preg_match('/[[:lower:]][[:space:]][[:upper:]]/', $subject, $match_02);
var_dump($match_02);
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//preg_match의 첫 인수는 검색 문자열, 두번째 인수는 검색 대상
// 슬래시(/)=구획문자 -> 메타문자의 역할 따라서 슬래시를 찾기 위해서는 메타문자로서의 효력을 잃게 해서 다른 문자로 대체
//1=true 반환
echo preg_match("/cat/","i love my cat")."<br>";

echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//0=false 반환
echo preg_match("/cat/","i love my dog")."<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//preg_match_all과 preg_match의 차이 = preg_mat는 매칭되는 값을 찾게되면 그 시점에서 검색이 종료
//preg_match_all은 매칭되는 모든 값을 찾게 되면 검색 종료
//(소문자ol)인 문자 찾아서 출력 
preg_match("/[a-z]ol/",$subject,$match);
var_dump($match)."<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//첫 번째가 대문자 두 번째가 소문자인 문자를 찾아 출력
preg_match_all("/[A-Z][a-z]/","$subject",$match);
var_dump($match)."<br>";
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

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
//Ho PHP s ool만 출력
preg_match_all("/[^a-l]/",$subject,$match);
var_dump($match);
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//a~1까지를 뺀 나머지 문자를 찾는 것 이지만 위와 다르게 하나를 찾는 즉시 종료
//H만 출력
preg_match("/[^a-l]/",$subject,$match);
var_dump($match);
echo "라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//ereg_replace가 사라지고 preg_replace로 대체
//preg_replace는 원하는 문자열을 다른 문자열로 치환하는 함수
//모든 대문자를 A로 치환
//출력값은 Aello AAA is cool!
echo preg_replace("/[A-Z]/", "A",$subject);
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

//출력값은(ello P)와
//(P is cool!)이 된다.
$strSplit = preg_split("/H/","$subject");
echo $strSplit[0];
for($i=0; $i<sizeof($strSplit); $i++){
	echo $strSplit[$i];
}
echo "<br>라인 번호 - ";
echo __LINE__;
echo "<br><br>";

?>