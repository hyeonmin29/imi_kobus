<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$sel_sum= 555555;
$mem_cash=1000;
$mem_credit=2000;
$mem_phone=3000;
$mem_buypoint=500;



if($sel_sum > $mem_cash && $mem_cash>=0){           // 현금이 총 금액보다 적고 0원보단 많이 있을 때

    $sel_sum = $sel_sum-$mem_cash;
    $recod_cash = $mem_cash;
    $mem_cash=0;
    if($sel_sum > $mem_credit && $mem_credit>=0){   // 신용카드가 총 금액보다 적고 0원보단 많이 있을 때
        $sel_sum = $sel_sum-$mem_credit;
        $recod_credit = $mem_credit;
        $mem_credit=0;

        if($sel_sum > $mem_phone && $mem_phone>=0) { // 핸드폰이 총 금액보다 적고 0원보단 많이 있을 때
            $sel_sum = $sel_sum - $mem_phone;
            $recod_phone = $mem_phone;
            $mem_phone = 0;

            if($sel_sum > $mem_buypoint && $mem_buypoint>=0){   // 구매포인트 총 금액보다 결재총액이 더 많을 때 ==  오류

                echo "<script>
                    alert(\"no balance!!  go charging\");
                    window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
                    </script>";
                echo("<script>location.href='../mileage/mileage_charging.php';</script>");
            }else{                                   // 구매포인트가 총 금액보다 클 때
                $mem_buypoint = $mem_buypoint-$sel_sum;
                $recode_buypoint = $sel_sum;
                $sel_sum=0;
            }

        }else{                                      // 핸드폰이 총 금액보다 클 때
            $mem_phone = $mem_phone-$sel_sum;
            $recod_phone = $sel_sum;
            $sel_sum=0;
        }

    }else{                                          // 신용카드가 총 금액보다 클 때
        $mem_credit = $mem_credit-$sel_sum;
        $recod_credit=$sel_sum;
        $sel_sum=0;
    }
}else{                                              // 현금이 총 금액보다 클 때
    $mem_cash = $mem_cash- $sel_sum;
    $recod_cash=$sel_sum;
    $sel_sum=0;
}





echo  "// 캐쉬 :".$mem_cash;
echo "// 카드:".$mem_credit;
echo "// 폰:".$mem_phone;
echo "// 구포:".$mem_buypoint;
echo "// 썸:".$sel_sum."<br/>"."<br/>";


echo $recod_cash ."<br/>";
echo $recod_credit."<br/>";
echo $recod_phone."<br/>";
echo $recode_buypoint."<br/>";


?>