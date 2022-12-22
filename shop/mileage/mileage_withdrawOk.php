<?php
/** 출금 완료 페이지**/
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
include('../_inc/DBconnect.php');
ini_set('display_errors', true);
error_reporting(E_ALL);
try {
	if(empty($_SESSION['member_Session_id'])){
		echo "<script>alert(\"세션 정보 오류\"); window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');</script>";
		echo("<script>location.href='../mileage/mileage_withdrawForm.php';</script>");
	}
	if($_POST['bank_type'] == 0){
		echo "<script>alert(\"은행 선택 오류\"); window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');</script>";
		echo("<script>location.href='../mileage/mileage_withdrawForm.php';</script>");
	}
	if(empty($_POST['nbankNum'])){
		echo "<script>alert(\"계좌번호 오류\"); window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');</script>";
		echo("<script>location.href='../mileage/mileage_withdrawForm.php';</script>");
	}
	if(empty($_POST['pointPrice'])){
		echo "<script>alert(\"금액 입력 오류\"); window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');</script>";
		echo("<script>location.href='../mileage/mileage_withdrawForm.php';</script>");
	}

?>
	<html>
	<body>
	 <h2>출금 결과 페이지</h2>
	    <hr width="80%"/>
	<div id="#contsRow">
	<?php
	echo "출금 프로세스 시작\n\n";
    if(!$db){
		///예외처리
        throw new Exception("데이터 연결오류",1);
    }
    /** 사용 변수  **/
    $pointPrice = $_POST['pointPrice'];     // 유저가 입력한 금액
    $member_bank = $_POST['bank_type'];    //유저가 입력한 은행타입
    $member_bankNum = $_POST['nbankNum'];    // 유저가 입력한 계좌번호
    $member_num = $_SESSION['member_Session_number'];    //로그인되어있는 멤버의 멤버번호
    $mileage_num = $_SESSION['member_Session_mileage'];    //로그인되어있는 멤버의 마일리지번호
    $withdraw_price = 900;    //출금 수수료
    $sel_sum = $pointPrice + $withdraw_price;    //사용자 입력금액+수수료
    $mem_cash = 0;
    $mem_credit = 0;                                                      // 사용변수 초기화
    $mem_phone = 0;
    $order_number = date("YmdHis").$mileage_num;        // 지금시간 + 회원 마일리지 번호
    $order_num = 'withdraw'.$order_number;                    //신규 충전주문번호(oid)// (상태명+주문번호)
    $account_num = 'account_book' . $order_number;             //신규 장부코드 번호
    $step = $_POST['step'];                                     // 충전 스텝 체크


    // 충전 전 회원정보 조회 & 저장 ▼ //
    echo"회원 정보 조회 \n";
	///member테이블에서 num,id,name,tel을 조회하는데 조건은 member_num과 폼에서 받은 member_num값이 같을 때
    $rs = $db->Execute("select member_num, member_id, member_name, member_tel from member where member_num='".$member_num."'");

	$rstCount = $rs->recordCount();
	if($rstCount < 1){	
		throw new Exception('정보조회 오류 다시 시도하세요', 81);
	}

	while (!$rs->EOF) {
        $mem_num = $rs->fields[0];
        $mem_id = $rs->fields[1];
        $mem_name = $rs->fields[2];
        $mem_tel= $rs->fields[3];
		///다음 커서로 이동
        $rs->MoveNext();
    }
	$rs -> MoveLast();
	$rs -> Close();

    echo "user_id=$mem_id";
    if ($_SESSION['member_Session_id']!=$mem_id) {
        throw new Exception("정보조회 오류",4956);
    }

    //$ShopFunction->fnSetUser($user_info);
    // 충전 전 회원정보 조회 & 저장 ▲ //

    echo "트랜젝션 시작\n";
	///트랜잭션 시작
    $trans_check = $db->StartTrans();
    if ($trans_check == false){
        throw new Exception("트랜젝션오류", 44);
    }

    echo "보유 마일리지 조회\n";
	// 기존에 멤버가 가지고 있는 마일리지 조회
	///mileage테이블에서 id,cash_amount,credit_amount,phone_amount를 조회하는데 조건은 meeber_num이 $member_num과 같고 레코드 락을 걸어 다른 사람이 이 행을 건들지 못하도록 막음
    $rs = $db->Execute("select mileage_id, cash_amount, credit_amount, phone_amount from mileage where member_num=$member_num  for update");
	///rs가 아닐때까지 루프를 돌린다
    while (!$rs->EOF) {
        $mileage_id = $rs->fields[0];
        $mem_cash = $rs->fields[1];
        $mem_credit = $rs->fields[2];
        $mem_phone = $rs->fields[3];
        $rs->MoveNext();
    }
	///만약 db에서 변경된 횟수가 1이이면
    if ($db->Affected_Rows() <1){
        throw new Exception("정보조회 오류",5490);
    }



    //출금 거래번호 입력 (trade_id)

    echo "order_num=".$order_num ."\n";

    $sql = "insert into trade_id (oid,reg_date) values('".$order_num."', now())";
    $db->Execute($sql);

    if($db->Affected_Rows() <1){
        throw new Exception("거래번호 등록 오류",557);
    }

    echo "출금내역 입력\n";
    /******************************출금 db 테이블 입력 S******************************************/
    $rs = $db->Execute("insert into withdraw(member_num,withdraw_price,withdraw_banknum,withdraw_bank,withdraw_regdate) values($member_num,$sel_sum,$member_bankNum,$member_bank,now())");
    /******************************출금 db 테이블 입력 E******************************************/

    if($db->Affected_Rows() <1){
        throw new Exception("출금정보 입력 오류",4445);
    }

    echo "금액차감 프로세스 시작\n";
    /******************************포인트별 출금 금액 차감 프로세스*******************************************/

    if ($sel_sum > $mem_cash && $mem_cash >= 0) {           // 현금이 총 금액보다 적고 0원보단 많이 있을 때
        $sel_sum = $sel_sum - $mem_cash;
        $recod_cash = $mem_cash;
        $mem_cash = 0;
        if ($sel_sum > $mem_credit && $mem_credit >= 0) {   // 신용카드가 총 금액보다 적고 0원보단 많이 있을 때
            $sel_sum = $sel_sum - $mem_credit;
            $recod_credit = $mem_credit;
            $mem_credit = 0;
            if ($sel_sum > $mem_phone || $mem_phone <= 0) { // 핸드폰 총 금액보다 결제 총액이 더 많을 때 ==> 오류발생
                throw new Exception("잔액부족",1588);
            } else {                                      // 핸드폰이 총 금액으로 마무리 계산,
                $mem_phone = $mem_phone - $sel_sum;
                $recod_phone = $sel_sum;
           
            }
        } else {                                          // 신용카드가 총 금액보다 클 때
            $mem_credit = $mem_credit - $sel_sum;
            $recod_credit = $sel_sum;
			///recod_phone변수를 정해주지 않으면 변수가 없다는 오류 출력
			$recod_phone = 0;
            $sel_sum=0;

			/**************************************
			$mem_credit = $mem_credit - $sel_sum;
            $recod_credit = $sel_sum;
			///recod_phone변수를 정해주지 않으면 변수가 없다는 오류 출력
			$recod_phone = 0;
            $sel_sum = 0;
			**************************************/
        }
    } else {
		$mem_cash = $mem_cash - $sel_sum;
		$recod_cash = $sel_sum;
		///credit,phone부분 변수 정해주지 않았을 때 변수 없다는 오류 출력
		$recod_credit = 0;
		$recod_phone = 0;
		$sel_sum=0;
		
		
		/**************************************
		// 현금이 총 금액보다 클 때
        $mem_cash = $mem_cash - $sel_sum;
        $recod_cash = $sel_sum;
        $sel_sum = 0;
		********************/
    }

    echo "// 캐쉬 :" . $mem_cash;
    echo "// 카드:" . $mem_credit;
    echo "// 폰:" . $mem_phone;
	///sel_sum으로 하게되면 초기화된 값으로 0이 나오게 돼서 총 출금 값을 알 수 없음
	echo "// 출금 값:" . $recod_cash + $recod_credit + $recod_phone . "<br/>" . "<br/>";
	/***********************************************
    echo "// 출금 값:" . $sel_sum . "<br/>" . "<br/>";
	************************************************/



    echo $recod_cash . "<br/>";
    echo $recod_credit . "<br/>";
    echo $recod_phone . "<br/>";
    /******************************포인트별 금액 차감 프로세스 E*******************************************/

    echo "마일리지 사용 내역 입력\n";
    /*******************마일리지 사용 쿼리문작성*********************/
    if (isset($recod_cash) && $recod_cash > 0) {
        $rs = $db->Execute("insert into cash_mileage(mileage_id,member_num, cash_type, cash_price, cash_amount) values ($mileage_num, $member_num, 102, $recod_cash, (select ifnull((select  A.cash_amount from cash_mileage A where A.member_num=$member_num order by A.cash_regdate desc limit 1 ),0)-$recod_cash))");
    }
    if (isset($recod_credit) && $recod_credit > 0) {
        $rs = $db->Execute("insert into credit_mileage(mileage_id, member_num, credit_type, credit_price, credit_amount) values ($mileage_num, $member_num, 202, $recod_credit, (select ifnull((select  A.credit_amount from credit_mileage A where A.member_num=$member_num order by A.credit_regdate desc limit 1 ),0)-$recod_credit))");
    }
    if (isset($recod_phone) && $recod_phone > 0) {
        $rs = $db->Execute("insert into phone_mileage(mileage_id, member_num, phone_type, phone_price, phone_amount) values ($mileage_num, $member_num, 302, $recod_phone, (select ifnull((select  A.phone_amount from phone_mileage A where A.member_num=$member_num order by A.phone_regdate desc limit 1 ),0)-$recod_phone))");
    }

    if (!$rs) {
        throw new Exception("쿼리입력 오류",4);
    }

    // 사용가능한 전체 마일리지 조회
    echo"모든 마일리지 조회 \n";
    $rs = $db->Execute("select (mil.cash_amount +mil.credit_amount+mil.phone_amount+mil.buymileage_amount) as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$mem_num for update ");
    $after_money=$rs->fields['Amount'];
    if($after_money <=0){
        throw new Exception("충전금액오류",4);
    }

    // 디테일 리스트 작성
    echo"디테일 리스트 작성 \n";

    $detail_list = "insert into mile_detail_list set user_no='".$mem_num."', user_id='".$mem_id."', account_code='".$account_num."', mile_code='".$mileage_num."',payment_money='".$sel_sum."',remain_money='".$after_money."', payment_date=now()";
    $db->Execute($detail_list);
    if ($db->Affected_Rows() < 0){
        throw new Exception("디테일리스트 작성오류",305);
    }
    $detail_id = $db->Insert_id();

    // 디테일 리스트 작성
    echo"디테일 로그 작성 \n";

    $detail_list = "insert into mile_detail_log set detail_id='".$detail_id."', user_no='".$mem_num."', user_id='".$mem_id."', mile_code='".$mileage_num."',mile_money='".$sel_sum."', trade_id='".$order_num."', ins_type='w',ins_result='1',ins_date=now(),mile_state='sucess'";
    $db->Execute($detail_list);
    if ($db->Affected_Rows() < 0){
        throw new Exception("디테일리스트 작성오류",305);
    }


    $db->CompleteTrans();
    /*******************마일리지 사용 쿼리문작성 E*********************/

    echo "출금처리 끝\n";
    ?>


    </div><input type="button" value="go home" onclick="window.location='../index.php'">
    </body>
    </html>
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