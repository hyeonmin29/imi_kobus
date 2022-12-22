
<?php
/* dbdb 2 test */

/*디비 클래스*/
///adodb.ini.php정보 가져옴
include("../adodb5/adodb.inc.php");
///DB_conn 객체 생성
class DB_conn   {
    public $db;          //db 엔진
    private $m_strHost;  //host 이름, 클래스 내부에서만 사용 private
    private $m_strUser;  //mysql 관리자
    private $m_strPw;    //user 비밀번호
    private $m_MasterDB; //db명
    var $bReturn;        //db()에서 받은 return값을 저장, 외부에서도 사용 public

    //생성자
	///생성자 함수 생성
    public function __construct()    {
		///각자 값 대입
        $this->m_strHost = 'localhost';
        $this->m_strUser = 'root';
        $this->m_strPw = 'Kdkdldpadkdl123$%^';
        $this->m_MasterDB = 'study';
        $this->bReturn = $this->db_Conn();
    }
    //db연결
	///db 연결위해 클래스 내에서만 사용 가능한 함수 생성
    private function db_Conn()    {
        //mysqli사용
		///myslq 사용
        $this->db = NewADOConnection('mysqli');
        /*true면 쿼리문 보여줌
        $this->db->debug = false;*/
        //연결실패 확인하고 밑에서 try-catch
		///만약 db연결이 안됐으면
        if (!$this->db->connect($this->m_strHost, $this->m_strUser, $this->m_strPw, $this->m_MasterDB)) {
            ///false값 반환
			return false;
        }
		///true반환
        return true;
    }

    //쿼리문 실행
	///쿼리 실행
    public function Execute($sql)
    {
		///db에 쿼리 실행
        $this->db->Execute($sql);
    }

    //가장 최근 실행 된auto_increment 값 가져오기
	///Insert_ID 함수 생성
    public function Insert_ID() {
		///Insert_ID로 auto_increment 값 가져오기
        $this->db->Insert_ID();
    }
    //트랜잭션 시작
	///트랜잭션 시작 함수 생성
    public function StartTrans()    {
		///트랜잭션 시작
        $this->db->StartTrans();
    }

    //커밋
	///커밋 함수 생성
    public function CompleteTrans()    {
		///db 트랜잭션 커밋
        $this->db->CompleteTrans();
    }
    //롤백
	///트랜잭션 롤백 함수 생성
    public function FailTrans()    {
		///db 트랜잭션 롤백
        $this->db->FailTrans();               //실패처리, 실패처리가 없으면 그냥 commit됨 있어야 rollback
		///db 트랜잭션 커밋
        $this->db->CompleteTrans();
    }

    //db 종료
	///db 연결 끊는 함수 생성
    public function Close()    {
		///db 연결 끊기
        $this->db->Close();
    }

    //소멸자
	///소멸자 함수 생성
    public function fnClose()    {
        ///소멸
		$this->fnClose();
    }
	///다음 커서로 이동하는 함수 생성
    public function MoveNext() {
		///다음 커서로 이동이 오류없이 일어나면 true 반환 eof만나면 함수 false반환
        $this->MoveNext();
    }
	///affected Rows 함수설정
    public function Affected_Rows() {
		$this ->Affected_Rows();

    }
}