<?php
 error_reporting( E_ALL );
  ini_set( "display_errors", 1 );



class Cparam
{
	function __construct($strParam)
	{
		echo "constructor called with parameter ".$strParam."<br>";
	}
}
$Cparam = new Cparam("first");
$Cparam = new Cparam("second");
$Cparam = new Cparam("third");
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";




Class Cclassname
{
	public $attribute;
	function operation($nParam)
	{
		$this->attribute = $nParam;
		echo $this->attribute;
	}
}


//클래스 밖에서 바로 함수 접근 좋은 방법 아님
Class Classname
{
	public $attribute;
}
$Cclassname = new Cclassname();
$Cclassname -> attribute = "nValue";
echo $Cclassname->attribute;

echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";




Class Cclassname1
{
	public $attribute;
	function __get($strName)
	{
		return $this->strName;
	}
	function __set($strName, $value)
	{
		//attribute 값이 0~100
		if(($strName="attribute")&&(nValue>=0)&&(nValue<=100))
		//attribute = nValue
		$this->attribute = $nValue;
		$this->strName = $nValue;
	}
}

$Cclassname1 = new Cclassname1();
$Cclassname1->attribute = 5;
var_dump ($Cclassname1);
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


//모든 클래스 멤버 앞에 접근 제한자가 붙어야 좋음(기본값 public)
//다른 접근 제한자와 구분위해서
Class Cclassname2
{
	public $attribute;
	public function __get($strName)
	{
		return $this->strName;
	}
	public function __set($strName, $nValue)
	{
		$this->strName = $nValue;
	}
}



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

$fnOperationX = $fnOperationA->fnOperation1();
$fnOperationY = $fnOperationA->fnOperation2(12, "test");
echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


//상속구현
Class CclassB extends CclassA
{
	public $attribute2;
	function operation2()
	{
	}
}
Class CclassA
{
	//final function ~ 을 사용하면 상속과 오버라이딩을 막을 수 있다.
	public $attribute1;
	function operation1()
	{
	}
}
$b = new CclassB();
$b-> operation1();
$b-> attribute1 = 10;
$b-> operation2();
$b-> attribute2 = 10;

//class B가 A를 상속받았기 때문에 operation1,attribute1을 사용 할 수 있으나
//부모 클래스에서 자식 클래스를 상속받지 못함 //operation2,attribute2 사용 불가
//$a = new A();
//$a-> operation1();
//$a-> attribute1 = 10;
//$a-> operation2();		오류
//$a-> attribute2 = 10;		오류




//변수 범위 제어
//private = 자식클래스에게 상속 불가
//protected = 외부에서 사용 불가/ 자식 클래스에서는 사용 가능
Class Cprivate1
{
	public function operation1()
	{
		echo "operation called";
	}
	protected function operation2()
	{
		echo "operation2 called";
	}
	public function operation3()
	{
		echo "operation3 called";
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
//$Cprivate2 = new Cprivate2;






echo "<br>--------------------------------";
echo "<br>";

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
    // member variable
    private $strName;
    private $nAge;

    // constructor
    public function __construct()
    {
        $this->strName = "yse";
        $this->nAge = "10";
    }

    // method
    public function strTell()
    {
        echo "my name is {$this->strName} .";
        echo " and my age is {$this->nAge} .";
    }

    // method. return $this
    public function fnAdd_age($nAge)
    {
        $this->nAge += $nAge;
        return $this;
    }

    // static method
    public static function fnFactory()
    {
        return new CPlus();
    }

    public static function fnFactory2()
    {
        return self::fnFactory();
    }    
}

//
$CPlus = new CPlus();
$CPlus->fnAdd_age(3);
$CPlus->strTell();


//static을 calss 내에서 변수나 함수에 적용 시 다른 곳에서도 호출 가능
//클래스 밖에서 해당하는 함수나 변수에 접근하기 위해서는 인스턴스를 생성해서 접근 해야하는데
//static을 사용하면 클래스명::접근변수,함수 작성시 접근 가능
echo "<br />";
CPlus::fnFactory()->fnAdd_age(2)->strTell();
CPlus::fnFactory2()->fnAdd_age(3)->strTell();

echo "<br>";
echo "Line - ";
echo __LINE__;
echo "<br><br>";


// instanceof는 클래스인지 판단하는 함수
if($CPlus instanceof CPlus == true){
	echo "true";
}else{
	echo " 트라이캐치 예외처리로 바꿀예정";
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



//__autoload, iterator, toString,









?>