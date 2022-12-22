<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
ini_set('display_errors', true);
error_reporting(E_ALL);

try {
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
    ///db가 연결되지 않으면
	if(!$db){
		///예외처리
        throw new Exception("DB연결 오류",1);
    }

    $member_milId = $_SESSION['member_Session_mileage'];    // 멤버의 마일리지 번호를 저장
    $member_num = $_SESSION['member_Session_number'];    // 멤버의 멤버 번호를 저장
    $nPoint = $_POST['pointPrice'] * (1000);    // 1000원 단위로 받았기 때문에 1000곱해준다.
    $change_tax = $nPoint * 0.15;    // 포인트 충전의 경우 15%의 수수료가 추가된다.
    $nPoint_sum = $nPoint - $change_tax;    // 실제 충전 포인트는 구매금액-수수료
    $buyPoint_result = 0;    // 계산결과
    $count_array = 0;    // 배열 카운트
    $buypoint_id = array();    // 구매 포인트 id를 저장하는 배열
    $buypoint_amount = array();    // 구매 포인트 보유 가격을 저장하는 배열
	
	///member_num변수가 존재하지 않으면
    if(!isset($member_num)){
		///예외처리
        throw new Exception("회원정보 오류",243);
    }
	///member_milId가 존재하지 않으면
    if(!isset($member_milId)){
		///예외처리
        throw new Exception("회원정보 오류",9531);
    }
	///만약 nPoint가 0보다 작거나 nPoint_sum이 nPoint보다 작으면 true값 반환
    if($nPoint<0 || $nPoint_sum < $nPoint){
    }

	///트랜잭션 시작
    $trans_check=$db->StartTrans();
	// buypoint 테이블에서 사용가능한 값들만 불러와 삭제일자가 가장 빠른 순으로 배열에 넣는다.
	///buypoint테이블에서 id,amount값을 조회하는데 조건은 type=500이거나 502이고 mileage_id값과 $member_milId값이 같아야하고 buypoint_deldate정렬 순서로 조회 
    $rs = $db->Execute("select buypoint_id, buypoint_amount from buypoint where buypoint_type=500 or buypoint_type=502 and mileage_id=$member_milId order by buypoint_deldate");
	///deldate제일 빠른 순서대로 필드 함수에 데이터 삽입
    while (!$rs->EOF) { // 배열에 데이터 삽입
        $buypoint_id[] = $rs->fields[0];
        $buypoint_amount[] = $rs->fields[1];
        $rs->MoveNext();
        $count_array++;    // 카운트를 더해준다.
    }

	// 구매 마일리지로 변동 사항을 입력한다.
	/// buy_mileage테이블에 데이터를 삽입하는데 
    $rs = $db->Execute("insert into buy_mileage (mileage_id, buymileage_type, member_num, buymileage_price, buymileage_amount, buymileage_tax, buymileage_regdate) values($member_milId,400,$member_num,$nPoint_sum,(select ifnull((select A.buymileage_amount from buy_mileage A where A.member_num=$member_num order by A.buymileage_regdate desc limit 1 ),0)+$nPoint_sum), $change_tax, now() )");
	///rs가 되지 않으면
    if (!$rs) {
		///예외처리
        throw new Exception("변동사항 입력 오류",993);
    }
	// 구매포인트 차감 프로세스
    ///i값이 0부터 count_array까지 증가
	for ($i = 0; $i <= $count_array; $i++) {
        // 해당 컬럼의 구매포인트가 변경값보다 크다면 바로 변경을 하고 break를 이용해 for문을 빠져나온다.
		///만약 buypoint_amount-nPoint_sum값이 0보다 크면
        if (($buypoint_amount[$i] - $nPoint_sum) > 0) {
			///결과값은 buypoint_amount-nPoint_sum값 대입
            $buyPoint_result = $buypoint_amount[$i] - $nPoint_sum;
            ///nPoint_sum값 0으로 만든다
			$nPoint_sum = 0;    // 변경값을 0으로 만든다.
			///buypoint테이블에 amount값을 $buyPoint_result값을 대입하고 대입하는 컬럼 조건은 buypoint_id는 buypoint_id와 값이 일치해야한다.
            $rs = $db->Execute("update buypoint set buypoint_amount=$buyPoint_result where buypoint_id=$buypoint_id[$i]");
            //만약 변경된 데이터가 1개 이하이면
			if ($rs=$db->Affected_Rows() < 1){
				///예외처리
                throw new Exception("정보갱신 오류",305);
            }
			///for문에서 빠져나온다
            break;
        }
        // 해당컬럼이 0원이라면 넘어간다.
		///buypoint_amount값이 0이면
        else if ($buypoint_amount[$i] == 0) {
			///다음 코드로 건너뛴다
			continue;
			
		///위의 조건과 일치하는게 없으면
		}else {
			///buyPoint_result값은 nPoint_sum - buypoint_amount값 대입
            $buyPoint_result = $nPoint_sum - $buypoint_amount[$i];
            $nPoint_sum = $buyPoint_result;
			///buypoint테이블에 amount값을 0으로 세팅하는데 조건은 buypoint_id의 값이 $buypoint_id값과 일치해야 한다. 
            $rs = $db->Execute("update buypoint set buypoint_amount=0 where buypoint_id=$buypoint_id[$i]");
			///변경된 db정보가 1개이하이면
            if ($rs=$db->Affected_Rows() < 1){
				///예외처리
                throw new Exception("정보갱신 오류",305);
            }
        }

    }
	// 문제가 없다면 구매포인트 중 사용가능한 포인트의 합을 mileage 테이블에 업데이트 한다.
	///mileage테이블에서 amount값은(구매포인트 중 buypoint_amount합을 가져온다 조건 type 500이나 502) 조건은 mileage_id와 member_milId는 같은 곳에 수정
	$db->Execute("update mileage set buypoint_amount=(select sum(buypoint_amount) from buypoint where buypoint_type=500 or buypoint_type= 502) where mileage_id=$member_milId");
	///만약 db에서 변경된 값이 1개 이하일 경우
    if ($db->Affected_Rows() < 1){
		///예외처리
        throw new Exception("마일리지 갱신 오류",306);
    }
	/// buypoint_log테이블에 데이터 추가
    $db->Execute("insert into buypoint_log (mileage_id, buypoint_type,buypoint_price,buypoint_regdate) values ($member_num,501,$nPoint,now())");
	///만약 db에서 변경된 값이 1개 이하일 경우
    if ($db->Affected_Rows() < 1){
		///예외처리
        throw new Exception("테이블 등록 오륲",308);
    }
	///커밋
    $db->CompleteTrans();
    ?>

    <button type='button' onclick="location.href='../mileage_View/view_myMileage.php'"> 보유 마일리지 확인</button>

    <?php
    include("../_inc/footer.php");

} catch (Exception $e) {
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>
        alert(\" $error_msg \");
        </script>";
    echo("<script>location.href='../index.php';</script>");

    if (isset($db) && $db->IsConnected() == true) {
        if($trans_check==true){
            $db->FailTrans();
            $db->CompleteTrans();
        }
        $db->Close();
        unset($db);
    }
    exit;
}