<?php

/// interface는 메서드 선언만 가능하고, 기능 구현은 클래스에서 해야함.
/// interface 상속받고 메서드 사용하지 않으면 에러 발생
interface DbInterface {
    function getDbType();
    function getDbHost();
    function getDbName();
    function getDbUser();
    function getDbPassword();
}

interface DbInterface2 {
    function getDbType();
    function getDbHost();
    function getDbName();
    function getDbUser();
    function getDbPassword();
}

///ADODB_mysqli는 DbConnection을 상속받는다
class DbConnection extends ADODB_mysqli {
    public $databaseType = NULL;
    public $database = NULL;
    public $host = NULL;
    public $user = NULL;
    public $password = NULL;
	
	///생성자 함수 생성
    function __construct(&$objDb)	{
		///만약 DbInterface가 인트턴스화된 객체이면
        if($objDb instanceof DbInterface) {
			///objDb값 대입
            $this->databaseType = $objDb->getDbType();
            $this->database = $objDb->getDbName();
            $this->host = $objDb->getDbHost();
            $this->user = $objDb->getDbUser();
            $this->password = $objDb->getDbPassword();
			///objDb값 빈값으로 만들고
            $objDb = null;
			///변수 삭제
            unset($objDb);
		///DbInterface2가 인스턴스화된 객체이면
        } else if ($objDb instanceof DbInterface2) {
			///objDb값 대입
            $this->databaseType = $objDb->getDbType();
            $this->database = $objDb->getDbName();
            $this->host = $objDb->getDbHost();
            $this->user = $objDb->getDbUser();
            $this->password = $objDb->getDbPassword();
			///objDb값 빈값으로 만들고
            $objDb = null;
			///변수 삭제
            unset($objDb);
        }
    }
	///소멸자 함수 생성
    function __destruct() {
		만약 연결이되면
        if($this->IsConnected())
			///연결을 끊는다.
            $this->Close();
    }
	///db연결 끊는 함수 생성
    function dbClose(&$objDb) {
		///연결 끊기
        $objDb->Close();
		///db는 빈값
        $objDb = NULL;
    }
}
///connectionInfo 객체 생성
class ConnectionInfo {
	///
    private function parse_ini_string_m( $string  ) {
		///배열 생성
        $array = Array();
		///줄바꿈을 기준으로 $string 문자열을 분할
        $lines = explode("\n",  $string );
		///lines배열의 line원소만 가져옴
        foreach( $lines  as $line  ) {
			///검색 대상 문자열은$string, 저장될 배열은 $match
            $statement  = preg_match("/^(?!;)(?P<key>[\w+\.\-]+?)\s*=\s*(?P<value>.+?)\s*$/", $line, $match  );
			///만약 정규식이 되면
            if( $statement  ) {
				///key값은 $key에 value값은 $value에 저장
                $key     = $match[ 'key'  ];
                $value     = $match[ 'value'  ];
				
                if(  preg_match(  "/^\".*\"$/", $value ) || preg_match( "/^'.*'$/", $value  ) ) {
                    $value  = mb_substr( $value, 1, mb_strlen( $value  ) - 2  );
                }
				///
                $array[ $key ] =  $value;
            }
        }
		///array값 반환
        return $array;
    }

    public function getDbInfo($ConnectionName) {
        // 특정 connection 을 다른 connection으로 변경해야 하는 경우 아래와 같이 getDbInfo 함수에서만 $ConnectionName 을 변경해주면 된다
        if($ConnectionName == "centerDB_SlaveConnection") {
            $ConnectionName = "centerDbConnection";
        }

        $dbPath = "/var/itemmania/".$ConnectionName;

        $enc_string = file_get_contents($dbPath);

        $ini_string = itm_decrypt($enc_string);

        $rgInfo = $this->parse_ini_string_m($ini_string);
		
        return $rgInfo;

    }
}


$rgConnection = scandir("/var/itemmania/");
foreach($rgConnection as $key => $connection) {
    if(in_array($connection, array('.', '..', 'enc.php'))) {
        continue;
    }

    eval("
	class ".$connection." implements DbInterface {
		public \$strDbType = '';
		public \$strDbName = '';
		public \$strDbHost = '';
		public \$strDbUser = '';
		public \$strDbPassword = '';

		function __construct()	{
            \$connectionInfo = new ConnectionInfo();
            \$rgInfo =  \$connectionInfo->getDbInfo( '".$connection."');
			\$this->strDbType        = \$rgInfo['dbtype'];
			\$this->strDbName        = \$rgInfo['dbname'];
			\$this->strDbHost        = \$rgInfo['dbhost'];
			\$this->strDbUser        = \$rgInfo['dbuser'];
			\$this->strDbPassword    = \$rgInfo['dbpass'];

		}

		public function getDbType() {
			return \$this->strDbType;
		}

		public function getDbHost() {
			return \$this->strDbHost;
		}

		public function getDbName() {
			return \$this->strDbName;
		}

		public function getDbUser() {
			return \$this->strDbUser;
		}

		public function getDbPassword() {
			return \$this->strDbPassword;
		}
	}
	");
}


?>