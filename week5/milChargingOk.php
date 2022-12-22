<?php
/*마일리지 충전*/
include("../_inc/header.php");
include_once("../adodb5/adodb.inc.php");
///DB연결
include('../_inc/DBconnect.php');
//include("../db/dbconn.php");
//include_once ("../class/ShopFunction.php");
?>

<html>
<body>
<h2>충전 결과 확인 페이지</h2>
<hr width="80%"/>
<div id="#contsRow">


<?php
ini_set('display_errors', true);
	error_reporting(E_ALL);

    //$ShopFunction = new ShopFunction();


    //    if($ShopFunction==null){
    //        throw new Exception("클래스 호출 오류",3993);
    //    }

    // 기본 변수 설정 ▼ //
    $nType = $_POST['mileage_type'];
    $nMilprice = $_POST['mileage_price'];
    $mile_id = $_SESSION['member_Session_mileage'];
    $member_num = $_SESSION['member_Session_number'];
    $step = $_POST['step'];
    $mil_tax = ($nMilprice) * 0.15;
    $mil_sum = $nMilprice - $mil_tax;
    $order_number = date("YmdHis") . $mile_id;
    $order_num = 'charge' . $order_number;                    //신규 충전주문번호(oid)
    $account_num = 'account_book' . $order_number;             //신규 장부코드 번호
    $trans_check = null;                                          // 트랜젝션 체크
    $before_money = 0;                                             // 현재 보유 마일리지


    // 기본 변수 설정 ▲ //
    echo $nMilprice . "원 충전 프로세스";
/**************************************정리*************
try{
    //스텝체크
	/// step값이 1이 아니고 $step이 null이고 $step변수가 존재하지 않으면
    if ($step != 1 & empty($step) & !isset($step)) {
		///예외처리
        throw new Exception("비 정상적인 접근입니다.", 999);
    }
    // 충전금액 체크
	///만약 nMilprice가 0이하이거나 $_POST[mileage_price]변수가 존재하지 않거나 nMilprice변수가 존재하지 않으면 
    if ($nMilprice <= 0 || empty($_POST['mileage_price']) || !isset($nMilprice)) {
		///예외처리
        throw new Exception("충전금액 오류가 발생했습니다.", 999);
    }
    // 충전타입 체크
	///만약 nType값이 >보다 크거나 nType가 null값이거나 nType변수가 존재하지 않으면
    if ($nType > 3 || empty($nType) || !isset($nType)) {
		///예외처리
        throw new Exception("충전방법 오류가 발생했습니다.", 999);
    }
    // 충전방식 체크
	///만약 $_SERVER형식이 POST가 아니면
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
		///예외처리
        throw new Exception("충전오류가 발생했습니다.", 999);
    }
***********************************************************/
    // 충전 전 회원정보 조회 & 저장 ▼ //
    echo "회원 정보 조회 \n";
	///member테이블에서 조회하고 member_num이 세션값으로 가져온 $member_num값과 같을 때 member_num,id,name,tel을 조회한다.
    $rs = $db->Execute("select member_num, member_id, member_name, member_tel from member where member_num='" . $member_num . "'");
	///rs가 빈값이면
    if ($rs == null) {
		///예외처리
        throw new Exception ("정보조회 오류 다시 시도하세요", 81);
    }
/************************movelast,close
    // 회원조회 결과
	///rs가 끝날때까지 반복하는데
    while (!$rs->EOF) {
		///fields배열함수에 데이터 삽입
        $mem_num = $rs->fields[0];
        $mem_id = $rs->fields[1];
        $mem_name = $rs->fields[2];
        $mem_tel = $rs->fields[3];
        $rs->MoveNext();
    }
	///변수 삭제
    unset($rs);
*********************************************/


    echo "user_id=$mem_id";
/**********************************************정리******
	///만약 mem_num이 null값이고 mem_num변수가 존재하지 않으면
    if (empty($mem_num) & !isset($member_num)) {
		///예외처리
        throw new Exception("정보조회 오류", 4956);
    }
	/// mem_id가 null값이고 mem_id변수가 존재하지 않고 member_Session_id와 $mem_id가 같지 않으면
    if (empty($mem_id) && !isset($mem_id) && $_SESSION['member_Session_id'] != $mem_id) {
		///예외처리
        throw new Exception("정보조회 오류", 4956);
    }
	///mem_name값이 null이고 member_name이 존재하지 않으면
    if (empty($mem_name) & !isset($member_name)) {
		///예외처리
        throw new Exception("정보조회 오류", 4956);
    }
	///mem_tel값이 null이고 member_tel변수가 존재하지 않으면
    if (empty($mem_tel) & !isset($member_tel)) {
		///예외처리
        throw new Exception("정보조회 오류", 4956);
    }
******************************************************/

    //$ShopFunction->fnSetUser($user_info);
    // 충전 전 회원정보 조회 & 저장 ▲ //


    // 중복충전 체크 ▼ //
    echo "중복충전 체크 \n";
	///mileage_fill.succ테이블에서 oid가 order_num과 같을 때 oid를 조회
    $duplicated_check = $db->Execute("select oid from mileage_fill_succ where oid = '" . $order_num . "'");
	
/***********************************affected -> recordCount로 수정*****
	///만약 변경된 횟수가 0보다 크면
    if ($db->Affected_Rows() > 0) {
		///예외처리
        throw new Exception("중복충전 발생", 41);
    }
    // 중복충전 체크 ▲ //
******************************************/

/********************************trasncheck -> true or false*********
    echo "트랜젝션 시작 \n";
	///트랜잭션 시작
    $trans_check = $db->StartTrans();
	///trans_check가 null값이면
    if ($trans_check == null) {
		///예외처리
        throw new Exception("트랜젝션오류", 44);
    }
*******************************************************************/
    //$ShopFunction->fnGetMileage(1);
    // 사용가능한 전체 마일리지 조회
    echo "모든 마일리지 조회 \n";
	
    $rs = $db->Execute("select (mil.cash_amount +mil.credit_amount+mil.phone_amount+mil.buymileage_amount) as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$mem_num for update ");
	///fields배열 함수에 rs쿼리문사용하여 amount값 대입
    $before_money = $rs->fields['Amount'];
    $after_money = $before_money + $nMilprice;
    echo "before_money=$before_money";
    echo "after_money=$after_money \n";
	///변수삭제
    unset($rs);
	///만약 after_money가 0이하이고 after_money가 before_money이면
    if ($after_money <= 0 && $after_money < $before_money) {
		///예외처리
        throw new Exception("충전금액오류", 4);
    }

    //충전 거래번호 입력 (trade_id)

    echo "order_num=" . $order_num . "\n";
	///trade_id에 데이터를 추가하는데 oid,reg_date에 순차적으로 대입 값은(order_num값과 현재시간)
    $sql = "insert into trade_id (oid,reg_date) values('" . $order_num . "', now())";
	///쿼리문 실행
    $db->Execute($sql);
	///변수삭제
    unset($sql);
	///만약 db의 변경된 횟수가 1보다 작으면
    if ($db->Affected_Rows() < 1) {
		///예외처리
        throw new Exception("거래번호 등록 오류", 557);
    }


/******************************fail fill -> 위에있음
    // 충전 시작 등록

    echo " 충전시작 등록 \n";
	///milrage_fill테이블에 데이터 추가(회원 충전시작)
    $sql = "insert into mileage_fill 
                        (oid,state,account_code,mile_code,price,before_money,after_money,user_id,user_name,message,response_date)
            values('" . $order_num . "','connect','" . $account_num . "','" . $mile_id . "','" . $nMilprice . "','" . $before_money . "','" . $after_money . "','" . $mem_id . "','" . $mem_name . "','회원 충전시작',now())";
	///sql쿼리문 실행한 값 rs에 대입
    $rs = $db->Execute($sql);
	///만약 db에서 변경된 정보가 1개 이하이면
    if ($db->Affected_Rows() < 1) {
		///데이터 추가 (회원 충전실패)
        $sql = "insert into mileage_fill 
                        (oid,state,account_code,mile_code,price,before_money,after_money,user_id,user_name,message,response_date)
            values('" . $order_num . "','fail','" . $account_num . "','" . $mile_id . "','" . $nMilprice . "','" . $before_money . "','" . $after_money . "','" . $mem_id . "','" . $mem_name . "','회원 충전실패',now())";
			///fail_fill에 sql쿼리문 실행한 값 대입
        $fail_fill = $db->Execute($sql);
    }
	///변수삭제
    unset($sql);
**************************************************/

    echo "충전 타입별 코드 설정 \n";
    switch ($nType)              // 충전방식에 따라 3가지의 경우로 나눠짐
    {
        case 1:  //현금 충전
			///충전 타입 선택
            $mil_type = 100;
			///충전방식 현금
            $mil_name = 'cash';
			///데이터 cash테이블에 추가
            $sql = "insert into cash_mileage (mileage_id, member_num, cash_type, cash_price, cash_amount, cash_tax) values ($mile_id,$member_num,$mil_type,$mil_sum,(select ifnull((select A.cash_amount from cash_mileage A where A.member_num=$member_num order by A.cash_regdate desc limit 1  ),0)+$mil_sum),$mil_tax )";
            break;
        case 2: //카드 충전
            $mil_type = 200;
			///충전방식 카드
            $mil_name = 'credit';
			///데이터 credit테이블에 추가
            $sql = "insert into credit_mileage (mileage_id, member_num, credit_type, credit_price, credit_amount, credit_tax) values ($mile_id,$member_num,$mil_type,$mil_sum,(select ifnull((select  A.credit_amount from credit_mileage A where A.member_num=$member_num order by A.credit_regdate desc limit 1 ),0)+$mil_sum),$mil_tax )";
            break;
        case 3: //휴대폰 충전
			///충전 타입 선택
            $mil_type = 300;
			///충전방식 휴대폰
            $mil_name = 'phone';
			///데이터 phone테이블에 추가
            $sql = "insert into phone_mileage (mileage_id, member_num, phone_type, phone_price, phone_amount, phone_tax) values ($mile_id,$member_num,$mil_type,$mil_sum,(select ifnull((select  A.phone_amount from phone_mileage A where A.member_num=$member_num order by A.phone_regdate desc limit 1 ),0)+$mil_sum),$mil_tax )";
            break;
		///모두 해당하지 않을 경우
        default:
            // 충전 방법을 설정하지 않으면 전화면으로 돌려보냄
			///경고창에 choose method. go back 띄우고
            echo "<script> alert(\"please choose method. go back.\");
			window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
			</script>";
			///mileage_charging.php로 돌아감
            echo("<script>location.href='mileage_charging.php';</script>");
            break;
    }
    // 충전금액 입력 //

    $db->Execute($sql);
	///변수 삭제
    unset($sql);
    if ($db->Affected_Rows() < 1) {
        throw new Exception("충전 입력 실패 ", 9999);
    }
    // 충전금액 입력 //

    // 디테일 리스트 작성
    echo "디테일 리스트 작성 \n";
	///mile_detail_list테이블에 데이터 추가
    $detail_list = "insert into mile_detail_list set user_no='" . $mem_num . "', user_id='" . $mem_id . "', account_code='" . $account_num . "', mile_code='" . $mile_id . "',payment_money='" . $mil_sum . "',remain_money='" . $after_money . "', payment_date=now()";
	///db에 detail_list쿼리문 작성 결과 반영
    $db->Execute($detail_list);
	///만약 변경된 데이터값이 0보다 작으면
    if ($db->Affected_Rows() < 0) {
		///예외처리
        throw new Exception("디테일리스트 작성오류", 305);
    }
	///detail_id에서 auto_increment값 가져옴
    $detail_id = $db->Insert_id();
	
/*****************************************detail_list 실행문 affected rows 0 -> 1로 변경
    // 디테일 리스트 작성
    echo "디테일 로그 작성 \n";
	///mile_detail_log에 데이터 추가(sucess)
    $detail_list = "insert into mile_detail_log set detail_id='" . $detail_id . "', user_no='" . $mem_num . "', user_id='" . $mem_id . "', mile_code='" . $mile_id . "',mile_money='" . $mil_sum . "', trade_id='" . $order_num . "', ins_type='s',ins_result='2',ins_date=now(),mile_state='sucess'";
    ///detail._list쿼리문 실행
	$db->Execute($detail_list);
	///실행하는데 db변경 결과가 0보다 작으면
	-------------------------------------------------
    if ($db->Affected_Rows() < 0) {
	-------------------------------------------------
		///mile_detail_log에 데이터 추가 (fail)
        $detail_list = "insert into mile_detail_log set detail_id='" . $detail_id . "', user_no='" . $mem_num . "', user_id='" . $mem_id . "', mile_code='" . $mile_id . "',mile_money='" . $mil_sum . "', trade_id='" . $order_num . "', ins_type='s',ins_result='2',ins_date=now(),mile_state='fail'";
    
	------------------------------------------
	}
	///변수 삭제
    unset($detail_list);
**********************************************************/

    // 충전 중 상태 등록

    echo "충전 상태 등록 \n";
    $after_money = $before_money + $nMilprice;
	///mileage_fill데이터 수정
    $sql = "update mileage_fill set oid = '" . $order_num . "', state='process', message='충전중',response_date=now() where oid='" . $order_num . "'";
	///sql값 db에 반영
    $db->Execute($sql);
	///만약 db에 반영된 횟수가 1보다 작고
    if ($db->Affected_Rows() < 1) {
		///null이 아니라면
        if (!empty($fail_fill)) {
			///mileage_fill테이블 정보 수정
            $db->Execute("update mileage_fill set state='fail', message='충전 중 갱신 실패', response_date=now() where oid='" . $order_num . "'");
        }
    }


    //장부작성
    // type c = 충전
    echo "장부등록 \n";
	///account book에 (account_code/trade_type/seller_id/seller_mile_before/seller_mile_after/total_money/mile_money/admin_memo/ins_date) 데이터 추가
    $account_book = $db->Execute("insert into account_book set account_code='" . $account_num . "',trade_type='c',seller_id='" . $mem_id . "', seller_mile_before='" . $before_money . "',seller_mile_after='" . $after_money . "',total_money='" . $mil_sum . "',mile_money='" . $nMilprice . "',admin_memo='회원 마일리지 충전' ,ins_date=now() ");
	///만약 account_book이 null이거나 db에 반영된 횟수가 1 이하이면
    if ($account_book == null || $db->Affected_Rows() < 1) {
		///예외처리
		throw new Exception("장부 입력오류", 4624);
    }
	///변수삭제
    unset($account_book);


    echo "충전 테이블에 입력 \n";
    ///charge데이터 추가
	$db->Execute("insert into charge (member_num, charge_price, charge_type,charge_regdate) values ($member_num,$nMilprice,$mil_type,now())");
    echo '당신은 ' . $nType . '번을 선택 하여서 ' . $mil_type . '(으)로' . $mil_sum . '원 입금 되었습니다 감사합니다.';
	///db에 반영된 횟수 1 이하이면
    if ($db->Affected_Rows() < 1) {

        //throw new Exception("충전 중 오류발생");
		///mileage_fill테이블 수정 조건은 oid와$order_num값이 같은 행 수정
        $sql = "update mileage_fill set state='fail', message='회원 충전 실패', response_date=now() where oid='" . $order_num . "'";
		///sql조회 결과 반영
        $rs = $db->Execute($sql);
	///아니면
	} else {
        // 충전 성공 상태 등록


        echo "충전 성공정보저장 \n";
		///데이터 수정 mileage_fill테이블 state=success/response_date=now/message=충전완료/response_date=now로 변경 -> 조건은 oid와 order_num값이 동일
        $sql = "update mileage_fill set state='success',response_date=now() ,message='충전완료', response_date=now() where oid='" . $order_num . "'";
		///sql쿼리문 db에 적용
		$db->Execute($sql);

        // 충전성공 테이블 등록
		///mileage_fill_succ데이터 추가(mileage_fill테이블에서 oid와 order_num이 같고 state값이 success인 행 1개)
        $rs = $db->Execute("insert into mileage_fill_succ (select * from mileage_fill where oid='" . $order_num . "' and state='success' limit 1)");  // 성공한 기록만..
		///만약 rs가 되지 않으면
        if (!$rs) {
			///예외처리
            throw new Exception("충전 테이블 등록 실패", 5959);
        }
    }
	///변수 삭제
    unset($sql);
	
    echo "디테일로그 상태변경";
	///mile_detail_log테이블에 mile_state값을 sucess로 수정 조건은 detail_id가 $detail_id와 같아야 함

	///*************************************수정**********************************************///
	///$db->Execute("update mile_detail_log set mile_state='sucess' where detail_id='" . $detail_id . "' ");
	///mile_state 자료형과 일치하지 않음 enum(continue,success,cancel)만 가능해서 오타로 인한 오류
    $db->Execute("update mile_detail_log set mile_state='success' where detail_id='" . $detail_id . "' ");
	///만약 db에서 변경된 횟수가 1보다 작으면
    if ($db->Affected_Rows() <1){
		///예외처리
        throw new Exception("상태변경 오류",5490);
    }
	///커밋	
    $db->CompleteTrans();
    echo "충전  끝 \n";

    ?>
    <ul>
		<!--넓이 80%-->
        <hr width="80%"/>
			<!--중앙정렬-->
            <li align="center">
			<!--클릭하면 charging.php로 이동하는 더 충전하기 버튼 생성-->
            <button type='button' onclick="location.href='mileage_charging.php'">더 충전하기</button>
			<!--클릭하면 view_myMileage.php로 이동하는 버튼생성-->
            <button type='button' onclick="location.href='../mileage_View/view_myMileage.php'"> 보유 마일리지 확인</button>
        </li>
    </ul>
</div>
    <?php
	///footer정보 가져오기
    include("../_inc/footer.php");
} catch (Exception $e) {
			///예외처리시 메시지,코드 발생 /알림창에 에러메시지 띄우고/ index.php로 이동
            $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
            echo "<script> alert(\" $error_msg \");</script>";
            echo("<script>location.href='../index.php';</script>");
			///만약 db변수가 존재하고, 연결되어있을 때
            if (isset($db) && $db->IsConnected() == true) {
                ///trans_check 값이 true 이면
				if ($trans_check == true) {
					///롤백
                    $db->FailTrans();
                    ///커밋
					$db->CompleteTrans();
			///변수 삭제
			unset($trans_check);
        }
		///db 연결해제
        $db->Close();
		///db변수삭제
        unset($db);
    }
	///종료
    exit;
}