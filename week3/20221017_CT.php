<?php

error_reporting( E_ALL );
ini_set( "display_errors", 1 );
try
{

class Cparam
{
	function __construct($strParam)
	{
		//인스턴스가 생성될 때 자동으로 호출해주는 함수  = __construct
		echo "생성자 __construct -> ".$strParam."<br>";
	}
}
//
$strParam = new Cparam("first");
$strParam = new Cparam("second");
$strParam = new Cparam("third");
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


//class는 클래스를 정의하는데 사용 (변수와 상수를 정의하기도 하고 함수도 정의)
//instance는 클래스를 가져와서 사용
Class Cclassname
{
	public $strAttribute;
}
//Cclassname을 가져와서 instance인 strName을 생성
$strName = new Cclassname();
$strName -> strAttribute = "홍길동";
echo $strName->strAttribute;

echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";



/////////////
Class Cclassname1
{
	public $nAttribute;
	public function __get($strName)
	{
		return $this->strName;
	}
	public function __set($strName, $nValue)
	{
		//strName이 strAttribue이고 attribute 값이 0~100일 때
		if(($strName="strAttribute")&&(nValue>=0)&&(nValue<=100))
		$this->nAttribute = $nValue;
		$this->strName = $nValue;
	}
}
//객체는 3, nAttribute값은 5
$Cclassname1 = new Cclassname1();
$Cclassname1->nAttribute = 5;
var_dump ($Cclassname1);
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


//연산 호출
Class Cclassname3
{
	function fnOperation1()
	{
		echo "fnOperation1<br>";
	}
	function fnOperation2($nParam1, $strParam2)
	{
		echo "fnOperation2 $nParam1 : $strParam2";
	}
}
$fnOperationA = new Cclassname3();
$fnOperationA->fnOperation1();
$fnOperationA->fnOperation2(12, "test");
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


//상속구현 A가 부모클래스 B가 자식클래스
Class CclassA
{
	//final function ~ 을 사용하면 상속과 오버라이딩을 막을 수 있다.
	public $attribute1;
	function operation1()
	{
	}
}
Class CclassB extends CclassA
{
	public $attribute2;
	function operation2()
	{
	}
}
//B가 A함수를 상속
$CclassB = new CclassB();
$CclassB-> operation1();
$CclassB-> attribute1 = 10;
$CclassB-> operation2();
$CclassB-> attribute2 = 10;

//class B가 A를 상속받았기 때문에 operation1,attribute1을 사용 할 수 있으나
//부모 클래스에서 자식 클래스를 상속받지 못함 //operation2,attribute2 사용 불가
$CclassA = new CclassA();
$CclassA-> operation1();
$CclassA-> attribute1 = 10;
//$CclassA-> operation2();		오류
//$CclaasA-> attribute2 = 10;		오류


//변수 범위 제어
Class Cprivate1
{
	public function operation1()
	{
		echo "operation called";
	}
	//private = 클래스 내부에서만 접근 가능, 상속 불가
	private function operation2()
	{
		echo "private operation2 called";
	}
	//protected = 클래스 내부에서만 접근 가능, 상속 가능
	protected function operation3()
	{
		echo "protected operation3 called";
	}
}
//Class Cprivate2 extends Cprivate1
//{
//	function __construct()
//	{
//		$this->operation1();
//		$this->operation2();
//		$this->operation3();
//	}
//}


//클래스 상수 사용
Class Math{
	//const -> Class에서 상수 정의할 때 사용 조건문에서 정의 불가
	//const는 대소문자 구별하지 않게 못함
	const nPi = 3.14159;
}
//클래스당 상수에 접근하려면 ::연산자를 사용해 명시하면 됨
echo " Math::nPi = ".Math::nPi."\n";
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";



//정적 메소드 구현
//static 사용하게 되면 인스턴스 없이 호출 가능
Class Cstatic
{
	//정적 메소드 작성
	static function fnSquared($nInput)
	{
		return $nInput*$nInput;
	}
}
echo Cstatic::fnSquared(8);
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";



class CPlus
{
	private $strName;
    private $nAge;
    public function __construct()
    {
        $this->strName = "홍길동";
        $this->nAge = "10";
    }
    public function strTell()
    {
        echo "my name is {$this->strName} .";
        echo " and my age is {$this->nAge} .";
    }
    public function fnAdd_age($nAge)
    {
        $this->nAge += $nAge;
        return $this;
    }
    // static 
    public static function fnFactory()
    {
        return new CPlus();
    }

    public static function fnFactory2()
    {
        return self::fnFactory();
    }    
}
$CPlus = new CPlus();
$CPlus->fnAdd_age(3);
$CPlus->strTell();
//static을 calss 내에서 변수나 함수에 적용 시 다른 곳에서도 호출 가능
//클래스 밖에서 해당하는 함수나 변수에 접근하기 위해서는 인스턴스를 생성해서 접근 해야하는데
//static을 사용하면 클래스명::접근변수,함수 작성시 접근 가능
echo "<br />";
CPlus::fnFactory()->fnAdd_age(2)->strTell();
echo "<br />";
CPlus::fnFactory2()->fnAdd_age(3)->strTell();
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


// instanceof는 클래스인지 판단하는 함수
if($CPlus instanceof CPlus == true){
	echo "true";
}else{
	throw new Exception ("클래스가 아닙니다.");
}
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


//CloneCPlus는 CPlus와 같은 속성값을 가짐 (복제본)
$CloneCPlus = Clone $CPlus;
$CloneCPlus= new CPlus();
$CloneCPlus->fnAdd_age(3);
$CloneCPlus->strTell();



echo "<br>";
class Cprintable
{
	public $nTestone =1;
	public $nTesttwo;
	//__tostring을 사용하게 되면 인스턴스의 현재 상태를 나타내준다.
	public function __tostring()
	{
		return(var_export($this, TRUE));
	}
}
$Cprintable = new Cprintable();
echo $Cprintable."<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


//추상 클래스 사용 (인스턴스 만들 수 없음)
//프로토타입은 가지고 있으나 구현되어 있지 않은 추상 메소드 제공
abstract class Cabstract 
{
    abstract public function fnFoo();
}
class Cclass1 extends Cabstract
{
    public function fnFoo() {
        echo "ClassB <br>";
    }
}
class Cclass2 extends Cabstract
{
	public function fnFoo() {
			echo "ClassC <br>";	
	}
}
function fnBar(Cabstract $strfnFoo){
	$strfnFoo->fnFoo();
}
$Cclass1 = new Cclass1();
fnBar($Cclass1);
$Cclass2 = new Cclass2();
fnBar($Cclass2);
echo "Line - ";
echo __LINE__;
echo "<br><br>";



/////////////////////??
class Ccall
{
	public function __call($method, $p)
	{
		if($method=="display"){
			if(is_object($p[0])){
				$this->displayObject($p[0]);
			}else if(is_array($p[0])){
				$this->displayArray($p[0]);
			}else{
				$this->displayScalar($p[0]);
			}
		}
	}
}
$ov = new Ccall;
$ov -> display(array(1,2,3));
$ov -> display('cat');


}
catch(Exception $e){
	echo $e->getMessage()."<br>";
	echo $e->getFile()."<br>";
	echo $e->getLine()."<br>";

}

//require로 프로시저 php파일을 가져와서 보여줌
require_once("processorder.php");
echo "Line - ";
echo __LINE__;
echo "<br><br>";




?>