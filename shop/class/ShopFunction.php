<?php
/*****************************************
 *
 * 사용되는 모든 기능...
 * 클래스
 *****************************************/

ini_set('display_errors', true);
error_reporting(E_ALL);

//require("../adodb5/adodb.inc.php");
//include_once ("../db/dbconn.php");





///shopFunction을 클래스 객체로 설정
Class ShopFunction
{
	/// 이 클래스 내에서만 db,user_id,user_num 변수 사용 가능
    private $db;
    private $user_id = null;
    private $user_num = null;
	
	///어디에서라도 사용가능 인스턴스 생성시 자동으로 호출
    public function __construct()
    {
//        //$this->db= new DB_conn();
//        $this->db=new Mysqli_conn();
    }



    //회원정보 저장
	///fnSetUser함수 작성 변수로user_info
    public function fnSetUser($user_info){
		///user_id에 세션에서 받아온 값 대입
        $this ->user_id = $user_info['user_id'];
		///user_num에 세션에서 받아온 값 대입
        $this ->user_num = $user_info['user_num'];
    }

    // 타입별 마일리지 조회
    ///fnGetMileage 함수 생성(변수는 get_type)
	public function fnGetMileage($get_type)
    {
        $sql = "";
        $result="";
        $ResultMoney =0;
        ///만약 타입이 빈값이 아니면
		if($get_type!=null){
			///결과값 출력
            var_dump($result);
		///만약 타입이 1이면 전체마일리지를 조회한다. mileage mil과 member mem을 조인 후 mil.member_num과 mem.member_num값이 같은 행에서 가져오는데 mem.member_num값이 1인 곳에서 가져온다. 
        if ($get_type == 1) {                // 사용가능한 전체 마일리지 조회
            $sql ="select (mil.cash_amount +mil.credit_amount+mil.phone_amount+mil.buymileage_amount) as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=1";
        }
		///만약 타입이 2이면 cash칼럼을 조회한다. mileage mil과 member mem을 조인하여 mil.member_num과 mem.member_num값이 같은 행에서 가져오는데 mem.membeR_num값이 user_num값인 행에서 가져온다
        if ($get_type == 2) {                // 사용가능한 전체 현금충전 마일리지 조회
            $sql = "select mil.cash_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }
		///만약 타입이 3이면 credit칼럼을 조회한다. mileage mil과 member mem을 조인하여 mil.member_num과 mem.member_num값이 같은 행에서 가져오는데 mem.membeR_num값이 user_num값인 행에서 가져온다
        if ($get_type == 3) {                // 사용가능한 전체 신용카드충전 마일리지 조회
            $sql = "select mil.credit_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }
		///만약 타입이 4이면 phone칼럼을 조회한다. mileage mil과 member mem을 조인하여 mil.member_num과 mem.member_num값이 같은 행에서 가져오는데 mem.membeR_num값이 user_num값인 행에서 가져온다
        if ($get_type == 4) {                // 사용가능한 전체 휴대폰충전 마일리지 조회
            $sql = "select mil.phone_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }
		///만약 타입이 5이면 buypoint 칼럼을 조회한다. mileage mil과 member mem을 조인하여 mil.member_num과 mem.member_num값이 같은 행에서 가져오는데 mem.membeR_num값이 user_num값인 행에서 가져온다
        if ($get_type == 5) {                // 사용가능한 전체 쇼핑포인트 조회
            $sql = "select mil.buypoint_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }
		///만약 타입이 6이면 buymileage칼럼을 조회한다. mileage mil과 member mem을 조인하여 mil.member_num과 mem.member_num값이 같은 행에서 가져오는데 mem.membeR_num값이 user_num값인 행에서 가져온다
        if ($get_type == 6) {                // 사용가능한 전체 쇼핑마일리지 조회
            $sql = "select mil.buymileage_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }
		///만약 타입이 7이면 칼럼을 조회한다. mileage mil과 member mem을 조인하여 mil.member_num과 mem.member_num값이 같은 행에서 가져오는데 mem.membeR_num값이 user_num값인 행에서 가져온다
        if ($get_type == 7) {                // $etc 코드의 포인트 조회
            $sql = "SELECT mil.cash_amount as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$this->user_num";
        }

        }
		///위 조건들이 아니면
        else{
			///타입에러 출력하고
            echo "타입에러";
			///0반환
            return 0;
           }
		///만약 db값이 null이면
        if($this->db==null) {
			///db연결 안되어있음 출력
            echo "db연결 안되어있음";
		///만약 db가 연결되어 있으면
        }else{
			///db연결됨 출력
            echo "db 연결됨";
        }
		///result값 출력
        var_dump($result);
		///result는 sql문을 실행
        $result=$this->db->Execute($sql);
		///result가 아닌값이 eof만날때까지 반복
        while(!$result->EOF)
        {
			///fields함수에 
            $ResultMoney= $result->fields['Amount'];
			///다음 result 쿼리문 실행
            $result->MoveNext();
        }
		///result결과값 출력
        var_dump($result);
        echo "test_result =>";
		///만약 result값이 빈값이면
        if($result == null){
			///쿼리 동작 실패 출력
            echo "쿼리 동작 실패";
			/// 0값 반환
            return 0;
        }
		/// resultMoney값 반환 (fields amount값)
        return $ResultMoney;
    }

	
    public function testInsert($num){
        $result=null;
		///만약 $num이 1이면
        if($num===1){
		///new_table에 데이터를 추가하는 쿼리
        $result=$this->db->Execute("insert into new_table (data1,data2,data3,data4) values(1,2,3,4)");
		///result값 반환
        return $result;
        }
    }




    // 마일리지 충전

     public function fnAddMileage(){



    }

    public function fnSubtract(){

    }


}
