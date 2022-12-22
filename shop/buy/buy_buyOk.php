<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///db연결
include('../_inc/DBconnect.php');
ini_set('display_errors', true);
error_reporting(E_ALL);
$trans_check = null;

try {
    if(!$db){
        throw new Exception("데이터 연결오류",1);
    }
    ?>

    <html>
<body>
    <h2>구매 결과 페이지</h2>
    <hr width="80%"/>
<div id="#contsRow">

    <?php

    echo "물품구매 & 마일리지 삭감 처리 시작 \n";
    $sel_id = $_POST['sel_id'];                                           // form에서 넘어온 판매 id
    $sel_price = $_POST['sel_price'];                                     // form에서 넘어온 판매 가격
    $sel_quantity = $_POST['sel_quantity'];                               // form에서 넘어온 판매 수량
    $seller_id = $_POST['author'];                                        // form에서 넘어온 판매자 아이디
    $sel_regdate = $_POST['regdate'];                                     // form에서 넘어온 판매 등록일
    $buy_tax = 0;                                                         // 물품 등록은 수수료 x
    $nQuantity = $_POST['nQuantity'];                                     // 물품 구매 수량
    $lessQuantity = $nQuantity - $sel_quantity;                           // 잔여수량 계산
    $sel_sum = ($sel_price * $sel_quantity);                              // 구매수량 * 1개당 가격
    $saving_buyPoint = $sel_sum * 0.25;                                   // 구매포인트 계싼
    $mileage_num = $_SESSION['member_Session_mileage'];                   // 멤버의 세션에서 마일리지 번호 받아오기
    $member_num = $_SESSION['member_Session_number'];                     // 멤버의 세션에서 멤버 번호 받아오기
    $mem_cash = 0;
    $mem_credit = 0;                                                      // 사용변수 초기화
    $mem_phone = 0;
    $mem_buyPoint = 0;
    $step = $_POST['step'];
    $order_number = date("YmdHis") . $mileage_num;
    $order_num = 'buy' . $order_number;                                      //신규 충전주문번호(oid)
    $account_num = 'account_book' . $order_number;                         //신규 장부코드 번호
    $sell_num = "sell" . $order_num;                                         // 구매번호

    if ($lessQuantity < 0) {                                               // 품절이라면
        echo "<script>
        alert(\"잔여수량을 다시 확인해주세요 죄송합니다!.\");
        window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
        </script>";
        throw new Exception("", 391);
        echo("<script>location.href='../sel/sel_list.php';</script>");
    }

    if ($step != 2) {
        throw new Exception("비정상적인 거래 입니다.", 9949);
    }
    if (empty($sel_price) && $sel_price <= 0) {
        throw new Exception("비정상적인 거래 입니다.", 9949);
    }
    if (empty($sel_quantity) && $sel_quantity <= 0) {
        throw new Exception("비정상적인 거래 입니다.", 9949);
    }
    if (empty($nQuantity) && $nQuantity <= 0) {
        throw new Exception("비정상적인 거래 입니다.", 9949);
    }
    if (empty($sel_id) && $sel_id == null) {
        throw new Exception("비정상적인 거래 입니다.", 9949);
    }

    $trans_check = $db->StartTrans();
    if ($trans_check == null) {
        throw new Exception("트랜젝션오류", 44);
    }

    echo "초기  sel_sum : " . $sel_sum . "\n";


    // 구매 전 구매자 회원정보 조회 & 저장 ▼ //

    echo "회원정보 조회 \n";
    $rs = $db->Execute("select member_num, member_id, member_name, member_tel from member where member_num='" . $member_num . "'");

    if ($rs == null) {
        throw new Exception ("정보조회 오류 다시 시도하세요", 81);
    }

    // 회원조회 결과
    while (!$rs->EOF) {
        $mem_num = $rs->fields[0];
        $mem_id = $rs->fields[1];
        $mem_name = $rs->fields[2];
        $mem_tel = $rs->fields[3];
        $rs->MoveNext();
    }

    echo "user_id=$mem_id" . "\n";
    if (empty($mem_num) & !isset($member_num)) {
        throw new Exception("정보조회 오류", 4956);
    }
    if (empty($mem_id) && !isset($mem_id) && $_SESSION['member_Session_id'] != $mem_id) {
        throw new Exception("정보조회 오류", 4956);
    }
    if (empty($mem_name) & !isset($member_name)) {
        throw new Exception("정보조회 오류", 4956);
    }
    if (empty($mem_tel) & !isset($member_tel)) {
        throw new Exception("정보조회 오류", 4956);
    }

    unset($rs);


    //$ShopFunction->fnSetUser($user_info);
    // 구매 전 구매자 회원정보 조회 & 저장 ▲ //

    // 구매 전 판매자 회원정보 조회 & 저장 ▼ //

    $seller_check = $db->Execute("select mem.member_num, mem.member_name,mil.mileage_id, mem.member_tel, (mil.cash_amount+mil.credit_amount+mil.phone_amount+mil.buymileage_amount) from member mem join mileage mil on mem.member_num=mil.member_num  where mem.member_id='" . $seller_id . "'");

    if ($seller_check == null || !$seller_check) {
        throw new Exception("판매자 정보 오류", 39);
    }
    while (!$seller_check->EOF) {
        $seller_num = $seller_check->fields[0];
        $seller_name = $seller_check->fields[1];
        $seller_mile = $seller_check->fields[2];
        $seller_tel = $seller_check->fields[3];
        $seller_bfmoney = $seller_check->fields[4];
        $seller_check->MoveNext();
    }

    unset($seller_check);

    echo "seller_id =" . $seller_id . "\n";

    if (empty($seller_id) || !isset($seller_id)) {
        throw new Exception("판매자 정보 오류", 4957);
    }
    if (empty($seller_name) || !isset($seller_name)) {
        throw new Exception("판매자 정보 오류", 4957);
    }
    if (empty($seller_num) || !isset($seller_num)) {
        throw new Exception("판매자 정보 오류", 4957);
    }
    // 구매 전 판매자 회원정보 조회 & 저장 ▲ //


    echo "전체 마일리지 조회 \n";
    // 사용가능한 전체 마일리지 조회
    $rs = $db->Execute("select (mil.cash_amount +mil.credit_amount+mil.phone_amount+mil.buymileage_amount) as `Amount` from mileage mil join member mem on mil.member_num=mem.member_num where mem.member_num=$mem_num for update ");
    $before_money = $rs->fields['Amount'];


    if ($before_money < 0 && $before_money < $sel_sum) {
        throw new Exception("금액오류", 4);
    }
    echo "마일리지 조회 \n";
    $rs = $db->Execute("select mileage_id, cash_amount, credit_amount, phone_amount, buymileage_amount  from mileage where member_num=$member_num ");
    while (!$rs->EOF) {

        $mileage_id = $rs->fields[0];
        $mem_cash = $rs->fields[1];
        $mem_credit = $rs->fields[2];
        $mem_phone = $rs->fields[3];
        $mem_buyPoint = $rs->fields[4];
        $rs->MoveNext();
    }
    if (!$rs) {
        throw new Exception("정보조회 오류", 49);
    }
    // buy테이블에 구매내역 저장\
    echo "구매 테이블에 기록 \n";
    $rs = $db->Execute("insert into buy(sel_id, member_num, buy_price,buy_quantity,buy_amount, buy_date) values ($sel_id,$member_num,$sel_price,$sel_quantity,$sel_sum,now())");

    if ($rs == false || $db->Affected_Rows() < 1) {
        throw new Exception("DB 작성 오류 다시 시도하세요", 494);
    }

    $pay_money = $sel_sum;

    /******************************포인트별 금액 차감 프로세스*******************************************/

    if ($sel_sum > $mem_cash && $mem_cash >= 0) {           // 현금이 총 금액보다 적고 0원보단 많이 있을 때

        $sel_sum = $sel_sum - $mem_cash;
        $recod_cash = $mem_cash;
        $mem_cash = 0;
        if ($sel_sum > $mem_credit && $mem_credit >= 0) {   // 신용카드가 총 금액보다 적고 0원보단 많이 있을 때
            $sel_sum = $sel_sum - $mem_credit;
            $recod_credit = $mem_credit;
            $mem_credit = 0;

            if ($sel_sum > $mem_phone && $mem_phone >= 0) { // 핸드폰이 총 금액보다 적고 0원보단 많이 있을 때
                $sel_sum = $sel_sum - $mem_phone;
                $recod_phone = $mem_phone;
                $mem_phone = 0;

                if ($sel_sum > $mem_buyPoint || $mem_buyPoint <= 0) {   // 구매포인트 총 금액보다 결제총액이 더 많을 때 ==  오류
                    echo "<script>
                    alert(\"no balance!!  go charging\");
                    window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
                    </script>";
                    throw new Exception("cancel your order", 77889988);
                    echo("<script>location.href='../mileage/mileage_charging.php';</script>");
                } else {                                   // 구매포인트가 총 금액보다 클 때
                    $mem_buyPoint = $mem_buyPoint - $sel_sum;
                    $recod_buyPoint = $sel_sum;
                    $sel_sum = 0;
                }
            } else {                                      // 핸드폰이 총 금액보다 클 때
                $mem_phone = $mem_phone - $sel_sum;
                $recod_phone = $sel_sum;
				$recod_buyPoint = 0;
                $sel_sum = 0;
            }
        } else {                                          // 신용카드가 총 금액보다 클 때
            $mem_credit = $mem_credit - $sel_sum;
            $recod_credit = $sel_sum;
			$recod_phone = 0;
			$recod_buyPoint = 0;
            $sel_sum = 0;
        }
    } else {                                              // 현금이 총 금액보다 클 때
        $mem_cash = $mem_cash - $sel_sum;
        $recod_cash = $sel_sum;
		///변수 오류로 인해 카드,폰,총 사용포인트 값 지정
		$recod_credit = 0;
		$recod_phone = 0;
		$recod_buyPoint = 0;
		$sel_sum = 0;
    }

    echo "// 캐쉬 :" . $mem_cash;
    echo "// 카드:" . $mem_credit;
    echo "// 폰:" . $mem_phone;
    echo "// 구포:" . $mem_buyPoint;
    echo "// 썸:" . $mem_cash + $mem_credit + $mem_phone + $mem_buyPoint . "<br/>" . "<br/>";


    echo $recod_cash . "<br/>";
    echo $recod_credit . "<br/>";
    echo $recod_phone . "<br/>";
    echo $recod_buyPoint . "<br/>";
    /******************************포인트별 금액 차감 프로세스*******************************************/

    echo "구매자 마일리지 삭감  \n";
    /*******************마일리지 사용 쿼리문작성*********************/
    //수정하기.. 조건
    if (isset($recod_cash) && $recod_cash > 0) {
        $rs = $db->Execute("insert into cash_mileage(mileage_id,member_num, cash_type, cash_price, cash_amount, cash_tax) values ($mileage_num, $member_num, 101, $recod_cash, (select ifnull((select  A.cash_amount from cash_mileage A where A.member_num=$member_num order by A.cash_regdate desc limit 1 ),0)-$recod_cash), $buy_tax )");

        if (isset($recod_credit) && $recod_credit > 0) {
            $rs = $db->Execute("insert into credit_mileage(mileage_id, member_num, credit_type, credit_price, credit_amount, credit_tax) values ($mileage_num, $member_num, 201, $recod_credit, (select ifnull((select  A.credit_amount from credit_mileage A where A.member_num=$member_num order by A.credit_regdate desc limit 1 ),0)-$recod_credit), $buy_tax )");
        }
        if (isset($recod_phone) && $recod_phone > 0) {
            $rs = $db->Execute("insert into phone_mileage(mileage_id, member_num, phone_type, phone_price, phone_amount, phone_tax) values ($mileage_num, $member_num, 301, $recod_phone, (select ifnull((select  A.phone_amount from phone_mileage A where A.member_num=$member_num order by A.phone_regdate desc limit 1 ),0)-$recod_phone), $buy_tax )");
        }
        if (isset($recod_buyPoint) && $recod_buyPoint > 0) {
            $rs = $db->Execute("insert into buy_mileage(mileage_id, member_num, buymileage_type, buymileage_price, buymileage_amount, buymileage_tax) values ($mileage_num, $member_num, 401, $recod_buyPoint, (select ifnull((select  A.buymileage_amount from buy_mileage A where A.member_num=$member_num order by A.buymileage_regdate desc limit 1 ),0)-$recod_buyPoint), $buy_tax )");
        }
        if (!$rs) {
            throw new Exception("마일리지 삭감 오류", 4000);
        }

        unset($rs);

        /*******************마일리지 사용 쿼리문작성*********************/

        echo "판매자 마일리지 입금  \n";
        /***** 판매자 마일리지 입금 S ******/

        if (isset($recod_cash) && $recod_cash > 0) {
            $rs = $db->Execute("insert into cash_mileage(mileage_id,member_num, cash_type, cash_price, cash_amount, cash_tax) values ($seller_mile, $seller_num, 105, $recod_cash, (select ifnull((select  A.cash_amount from cash_mileage A where A.member_num=$seller_num order by A.cash_regdate desc limit 1 ),0)+$recod_cash), $buy_tax )");
        }
        if (isset($recod_credit) && $recod_credit > 0) {
            $rs = $db->Execute("insert into credit_mileage(mileage_id, member_num, credit_type, credit_price, credit_amount, credit_tax) values ($seller_mile, $member_num, 205, $recod_credit, (select ifnull((select  A.credit_amount from credit_mileage A where A.member_num=$seller_num order by A.credit_regdate desc limit 1 ),0)+$recod_credit), $buy_tax )");
        }
        if (isset($recod_phone) && $recod_phone > 0) {
            $rs = $db->Execute("insert into phone_mileage(mileage_id, member_num, phone_type, phone_price, phone_amount, phone_tax) values ($seller_mile, $member_num, 305, $recod_phone, (select ifnull((select  A.phone_amount from phone_mileage A where A.member_num=$seller_num order by A.phone_regdate desc limit 1 ),0)+$recod_phone), $buy_tax )");
        }
        if (isset($recod_buyPoint) && $recod_buyPoint > 0) {
            $rs = $db->Execute("insert into buy_mileage(mileage_id, member_num, buymileage_type, buymileage_price, buymileage_amount, buymileage_tax) values ($seller_mile, $member_num, 406, $recod_buyPoint, (select ifnull((select  A.buymileage_amount from buy_mileage A where A.member_num=$seller_num order by A.buymileage_regdate desc limit 1 ),0)+recod_buyPoint), $buy_tax )");
        }
        /***** 판매자 마일리지 입금 E ******/

        /***** 판매자 디테일 로그 작성 S ******/
        echo "디테일 리스트 작성 \n";

        $detail_list = "insert into mile_detail_list set user_no='" . $seller_num . "', user_id='" . $seller_id . "', account_code='" . $account_num . "', mile_code='" . $seller_mile . "',payment_money='" . $pay_money . "',remain_money='" . $seller_bfmoney . "', payment_date=now()";
        $db->Execute($detail_list);
        if ($db->Affected_Rows() < 0) {
            throw new Exception(" 판매자 디테일리스트 작성오류", 305);
        }
        $detail_id = $db->Insert_id();

        // 디테일 리스트 작성
        echo "디테일 로그 작성 \n";

        $detail_list = "insert into mile_detail_log set detail_id='" . $detail_id . "', user_no='" . $seller_num . "', user_id='" . $mem_id . "', mile_code='" . $seller_mile . "',mile_money='" . $pay_money . "', trade_id='" . $order_num . "', ins_type='s',ins_result='1',ins_date=now(),mile_state='continue'";
        $db->Execute($detail_list);
        if ($db->Affected_Rows() < 0) {
            throw new Exception("판매자 디테일로그 작성오류", 305);
        }
        unset($detail_list);
        /***** 판매자 디테일 로그 작성 E ******/


        unset($recod_credit);
        unset($recod_cash);
        unset($recod_phone);
        unset($recod_buyPoint);
        /**업데이트 / 트리거 사용
         *
         *
         * create trigger cashamount_update after insert on cash_mileage for each row
         * begin
         * update mileage set cash_amount=new.cash_amount where member_num=new.member_num order by new.cash_regdate desc limit 1;
         * end ;;
         *
         * create trigger creditamount_update after insert on credit_mileage for each row
         * begin
         * update mileage set credit_amount=new.credit_amount where member_num=new.member_num order by new.credit_regdate desc limit 1;
         * end ;;
         *
         * create trigger phoneamount_update after insert on phone_mileage for each row
         * begin
         * update mileage set phone_amount=new.phone_amount where member_num=new.member_num order by new.phone_regdate desc limit 1;
         * end ;;
         *
         * create trigger buypointamount_update after insert on buypoint for each row
         * begin
         * update mileage set buypoint_amount = new.buypoint_amount where member_num=new.member_num order by new.buypoint_regdate desc limit 1;
         * end ;;
         *
         * create trigger buymileageamount_update after insert on buy_mileage for each row
         * begin
         * update mileage set buymileage_amount = new.buymileage_amount where member_num=new.member_num order by new.buymileage_regdate desc limit 1;
         * end ;;
         *
         ***/

        /**업데이트**/


        // 디테일 리스트 작성
        echo "디테일 리스트 작성 \n";

        $detail_list = "insert into mile_detail_list set user_no='" . $mem_num . "', user_id='" . $mem_id . "', account_code='" . $account_num . "', mile_code='" . $mileage_num . "',payment_money='" . $pay_money . "',remain_money='" . ($before_money - $sel_sum) . "', payment_date=now()";
        $db->Execute($detail_list);
        if ($db->Affected_Rows() < 0) {


        }
        $detail_id = $db->Insert_id();

        // 디테일 리스트 작성
        echo "디테일 로그 작성 \n";

        $detail_list = "insert into mile_detail_log set detail_id='" . $detail_id . "', user_no='" . $mem_num . "', user_id='" . $mem_id . "', mile_code='" . $mileage_num . "',mile_money='" . $pay_money . "', trade_id='" . $order_num . "', ins_type='b',ins_result='1',ins_date=now(),mile_state='continue'";
        $db->Execute($detail_list);
        if ($db->Affected_Rows() < 0) {

        }

        unset($detail_list);
        unset($detail_id);


        echo "잔여수량 업데이트  \n";
        $db->Execute("update sel set sel_quantity = $lessQuantity where sel_id=$sel_id");

        if (!$db) {
            throw new Exception("수량 업데이트 오류", 4000);
        }

        echo "구매포인트 적립  \n";

        $db->Execute("insert into buypoint (buy_id, member_num,mileage_id,buypoint_type,buypoint_price,buypoint_amount, buypoint_regdate, buypoint_deldate) values  ($sel_id, $member_num, $mileage_num,500,$saving_buyPoint,$saving_buyPoint,now(),date_add(now(), interval 7 day))");
        if (!$db) {
            throw new Exception("구매포인트 적립 오류", 4000);
        }
        echo "구매포인트 로그 기록  \n";
        $db->Execute("insert into buypoint_log (mileage_id, buypoint_type,buypoint_price,buypoint_regdate) values ($member_num,500,$saving_buyPoint,now())");

        if (!$db) {
            throw new Exception("로그 기록 오류", 4000);
        }
        echo "마일리지 업데이트  \n";
        $db->Execute("update mileage set buypoint_amount=(select sum(buypoint_amount) from buypoint where buypoint_type=500 or buypoint_type= 502) where mileage_id=$mileage_num");

        if (!$db) {
            throw new Exception("마일리지 업데이트 오류", 4000);
        }


        echo "거래완료 테이블 등록  \n";
        /*** 거래 완료 정보 저장 S **/

        $perfect_list = " insert into trade_perfect_list set 
                          trade_id = '" . $order_num . "',
                          trade_date= '" . date('Ymd') . "',
                          trade_type='trade',
                          seller_id = '" . $seller_id . "',
                          seller_name = '" . $seller_name . "',
                          seller_contact = '" . $seller_tel . "',
                          buyer_id='" . $mem_id . "',
                          buyer_name = '" . $mem_name . "',
                          buyer_contact = '" . $mem_tel . "',
                          trade_money= '" . $pay_money . "',
                          trade_quantity = '" . $sel_quantity . "',
                          trade_reg_date = '" . $sel_regdate . "',
                          payment_mileage = '" . $pay_money . "',
                          trade_complete_time =now()";

        $db->Execute($perfect_list);

        if (!$db) {
            throw new Exception("거래 완료 테이블 등록 오류", 10393);
        }
        unset($perfect_list);
        unset($pay_money);


        /*** 거래 완료 정보 저장 E **/

        echo "종료  \n";

        $db->CompleteTrans();
        ?>
        <button type='button' onclick="location.href='../sel/sel_list.php'">확인</button>

        </div>
        </body>
        </html>
        <?php
        include("../_inc/footer.php");
    }
}catch (Exception $e) {
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>
        alert(\" $error_msg \");
        </script>";
    echo("<script>location.href='../index.php';</script>");

    if (isset($db) && $db->IsConnected() == true) {
        if ($trans_check == true) {
            $db->FailTrans();
            $db->CompleteTrans();
            unset($trans_check);
        }
        $db->Close();
        unset($db);
    }
    exit;
}

