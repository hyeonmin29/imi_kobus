
<?php
/* dbdb 2 test */

/*디비 클래스*/
include("../adodb5/adodb.inc.php");

class DB_conn extends ADOConnection {
    public $db;          //db 엔진
    private $m_strHost;  //host 이름, 클래스 내부에서만 사용 private
    private $m_strUser;  //mysql 관리자
    private $m_strPw;    //user 비밀번호
    private $m_MasterDB; //db명
    var $bReturn;        //db()에서 받은 return값을 저장, 외부에서도 사용 public


    //생성자
    public function __construct()    {
        $this->m_strHost = 'localhost';
        $this->m_strUser = 'root';
        $this->m_strPw = 'Kdkdldpadkdl123$%^';
        $this->m_MasterDB = 'study';
        $this->bReturn = $this->db_Conn();
    }
    //db연결
    private function db_Conn()    {
        //mysqli사용
        $this->db = NewADOConnection('mysqli');
        /*true면 쿼리문 보여줌*/
        $this->db->debug = true;
        //연결실패 확인하고 밑에서 try-catch
        if (!$this->db->connect($this->m_strHost, $this->m_strUser, $this->m_strPw, $this->m_MasterDB)) {
            return false;
        }
        return true;
    }

    //쿼리문 실행
    public function Execute($sql)
    {
        $result=null;
        $result=$this->db->Execute($sql);

        return $result;

    }

    //가장 최근 실행 된auto_increment 값 가져오기
    public function Insert_ID() {
        $this->db->Insert_ID();
    }
    //트랜잭션 시작
    public function StartTrans()    {
        $this->db->StartTrans();
    }

    //커밋
    public function CompleteTrans()    {
        $this->db->CompleteTrans();
    }
    //롤백
    public function FailTrans()    {
        $this->db->FailTrans();               //실패처리, 실패처리가 없으면 그냥 commit됨 있어야 rollback
        $this->db->CompleteTrans();
    }

    //db 종료
    public function Close()    {
        $this->db->Close();
    }

    //소멸자
    public function fnClose()    {
        $this->fnClose();
    }
    public function MoveNext() {
        $this->MoveNext();
    }
    public function Affected_Rows() {
        $this ->Affected_Rows();

    }
    public function EOF(){
        $this->EOF();
    }
    public function fields(){
        $this->fields();
    }

}