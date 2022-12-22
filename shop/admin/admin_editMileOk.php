<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
error_reporting(E_ALL);
ini_set("display_errors", 1);
try {
	$driver = 'mysqli';
    $db = newAdoConnection($driver);
    $db->debug = false;
    $db->socket = '/var/run/mysqld/mysql_3306.sock';
	///db 연결
    $db->connect('localhost', 'root', 'Itemmania1324%^', 'study');
} catch (Exception $e) {
    die($e->getMessage());   // 에러메세지 출력
} ?>

    <html>
    <body>
    <h2>유저 마일리지 변경 결과</h2>
    <hr width="80%"/>
    <div id="#contsRow">
        <?php

        $nCash = $_POST['nCash'];
        $nCredit = $_POST['nCredit'];
        $nPhone = $_POST['nPhone'];
        $nBuyMileage = $_POST['nBuyMileage'];
        $nBuyPoint = $_POST['nBuyPoint'];
        $nMember_num = $_POST['nNum'];
        $member_num = $_SESSION['member_Session_number'];
        $mileage_num = $_SESSION['member_Session_mileage'];
        $nResult = 0;
        $nType = 0;

        $db->StartTrans();

        // 금액차이 계산을 위해 데이터 불러옴

        $rs = $db->Execute("select cash_amount, credit_amount, phone_amount, buymileage_amount, buypoint_amount, mileage_id from mileage where member_num = $nMember_num");


        while (!$rs->EOF) {
            $user_cash = $rs->fields[0];
            $user_credit = $rs->fields[1];
            $user_phone = $rs->fields[2];
            $user_buyMileage = $rs->fields[3];
            $user_buyPoint = $rs->fields[4];
            $mileage_id = $rs->fields[5];

            $rs->MoveNext();
        }
        if (!$rs) {
            $db->FailTrans();
        }


        // 변경사항 입력
        if ($nCash != $user_cash) {
            // 금액차이 계산
            if ($user_cash > $nCash) {
                $nPrice = ($user_cash - $nCash);
                $nResult = $nPrice * (-1);
                $nType = 104;
            } else {
                $nPrice = ($nCash - $user_cash);
                $nResult = $nPrice;
                $nType = 103;
            }
            $rs = $db->Execute("insert into cash_mileage(mileage_id,member_num, cash_type, cash_price, cash_amount) values ($mileage_id, $nMember_num, $nType, $nPrice, (select ifnull((select  A.cash_amount from cash_mileage A where A.member_num=$nMember_num order by A.cash_regdate desc limit 1 ),0)+$nResult))");
        }
        if ($nCredit != $user_credit) {
            // 금액차이 계산
            if ($user_credit > $nCredit) {
                $nPrice = ($user_credit - $nCredit);
                $nResult = $nPrice * (-1);
                $nType = 204;
            } else {
                $nPrice = ($nCredit - $user_credit);
                $nResult = $nPrice;
                $nType = 203;
            }
            $rs = $db->Execute("insert into credit_mileage(mileage_id, member_num, credit_type, credit_price, credit_amount) values ($mileage_id, $nMember_num, $nType, $nPrice, (select ifnull((select  A.credit_amount from credit_mileage A where A.member_num=$nMember_num order by A.credit_regdate desc limit 1 ),0)+$nResult))");
        }
        if ($nPhone != $user_phone) {
            // 금액차이 계산
            if ($user_phone > $nPhone) {
                $nPrice = ($user_phone - $nPhone);
                $nResult = $nPrice * (-1);
                $nType = 304;
            } else {
                $nPrice = ($nPhone - $user_phone);
                $nResult = $nPrice;
                $nType = 303;
            }
            $rs = $db->Execute("insert into phone_mileage(mileage_id, member_num, phone_type, phone_price, phone_amount) values ($mileage_id, $nMember_num, $nType, $nPrice, (select ifnull((select  A.phone_amount from phone_mileage A where A.member_num=$nMember_num order by A.phone_regdate desc limit 1 ),0)+$nResult))");
        }
        if ($nBuyMileage != $user_buyMileage) {
            // 금액차이 계산
            if ($user_buyMileage > $nBuyMileage) {
                $nPrice = ($user_buyMileage - $nBuyMileage);
                $nResult = $nPrice * (-1);
                $nType = 404;
            } else {
                $nPrice = ($nBuyMileage - $user_buyMileage);
                $nResult = $nPrice;
                $nType = 403;
            }
            $rs = $db->Execute("insert into buy_mileage(mileage_id, member_num, buymileage_type, buymileage_price, buymileage_amount) values ($mileage_id, $nMember_num, $nType, $nPrice, (select ifnull((select  A.buymileage_amount from buy_mileage A where A.member_num=$nMember_num order by A.buymileage_regdate desc limit 1 ),0)+$nResult))");
        }
        if ($nBuyPoint != $user_buyPoint) {
            // 금액차이 계산
            if ($user_buyPoint > $nBuyPoint) {
                $nPrice = ($user_buyPoint - $nBuyPoint);
                $nResult = $nPrice * (-1);
                $nType = 503;
                $count_array = 0;
// buypoint 테이블에서 사용가능한 값들만 불러와 삭제일자가 가장 빠른 순으로 배열에 넣는다.
                $rs = $db->Execute("select buypoint_id, buypoint_amount from buypoint where buypoint_type=500 or buypoint_type=502 order by buypoint_deldate");
                while (!$rs->EOF) { // 배열에 데이터 삽입
                    $buypoint_id[] = $rs->fields[0];
                    $buypoint_amount[] = $rs->fields[1];
                    $rs->MoveNext();
                    $count_array++;    // 카운트를 더해준다.
                }

                // 구매포인트 차감 프로세스
                for ($i = 0; $i <= $count_array; $i++) {

                    // 해당 컬럼의 구매포인트가 변경값보다 크다면 바로 변경을 하고 break를 이용해 for문을 빠져나온다.
                    if (($buypoint_amount[$i] - $nPrice) > 0) {
                        $buyPoint_result = $buypoint_amount[$i] - $nPrice;
                        $nPrice = 0;    // 변경값을 0으로 만든다.
                        $rs = $db->Execute("update buypoint set buypoint_amount=$buyPoint_result where buypoint_id=$buypoint_id[$i]");
                        break;
                        // 해당컬럼이 0원이라면 넘어간다.
                    } else if ($buypoint_amount[$i] == 0) {
                        continue;
                        // 해당 컬럼 값이 변경값 보다 작다면, 변경값=변경값-해당컬럼 계산을 한 후  해당 컬럼의 총액을 0으로 바꾼다.
                    } else {
                        $buyPoint_result = $nPrice - $buypoint_amount[$i];
                        $nPrice = $buyPoint_result;
                        $rs = $db->Execute("update buypoint set buypoint_amount=0 where buypoint_id=$buypoint_id[$i]");
                    }
                    if (!$rs) {
                        $db->FailTrans();
                    }
                }
                if (!$rs) {
                    $db->FailTrans();
                }
            } else {
                $nPrice = ($nBuyPoint - $user_buyPoint);
                $nResult = $nPrice;
                $nType = 502;
                $db->Execute("insert into buypoint (buy_id, member_num,mileage_id,buypoint_type,buypoint_price,buypoint_amount, buypoint_regdate, buypoint_deldate) values  (000, $member_num, $mileage_num,$nType,$nResult,$nResult,now(),date_add(now(), interval 7 day))");

            }
        }
        $db->Execute("insert into buypoint_log (mileage_id, buypoint_type,buypoint_price,buypoint_regdate) values ($member_num,502,$nResult,now())");

        // 문제가 없다면 구매포인트 중 사용가능한 포인트의 합을 mileage 테이블에 업데이트 한다.
        $db->Execute("update mileage set buypoint_amount=(select sum(buypoint_amount) from buypoint where buypoint_type=500 or buypoint_type=502) where mileage_id=$mileage_id");
        if (!$rs) {
            $db->FailTrans();
        }

        $db->CompleteTrans();

        ?>

        <ul>
            <hr width="80%"/>


        </ul>
        <button type='button' onclick="location.href='../index.php">home</button>

    </div>
    </body>
    </html>
<?php
include("../_inc/footer.php");