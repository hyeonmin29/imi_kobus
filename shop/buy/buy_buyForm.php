<?php

/*일반구매 페이지*/
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///db연결
include('../_inc/DBconnect.php');
ini_set('display_errors', true);
error_reporting(E_ALL);

$trans_check=null;
$sel_id = $_POST['sel_id'];    // 폼에서 넘어온 판매 번호
$step = $_POST['step'];        // 스텝 체크
$member_num = $_SESSION['member_Session_number'];

try {
    if (!isset($sel_id) & empty($sel_id)) {   // 판매번호 체크

        throw new Exception("판매번호 오류", 99);
    }
    if(!isset($step) & empty($step) && $step != 1){
        throw new Exception("비정상적인 접근", 99);

    }

    // 구매자 정보조회
    $sql = "select member_num, member_id, member_name, member_tel, member_address from member where member_num=".$member_num." ";

    $user_check= $db->Execute($sql);

    if(!$user_check)
    {
        throw new Exception("정보조회 오류",999);
    }
    

    if (isset($_SESSION['member_Session_number'])) {  // 로그인에 성공한 유저만 사용할 수 있도록.
        $rs = $db->Execute("select sel_title, sel_price, sel_quantity, sel_id, sel_author, sel_regdate from sel where sel_id=$sel_id for update ");

        while (!$rs->EOF) {


//  print_r($rs);
        $sel_title = $rs->fields[0];
        $sel_price = $rs->fields[1];
        $sel_quantity = $rs->fields[2];
        $sel_id = $rs->fields[3];
        $sel_auth = $rs->fields[4];
        $sel_regdate = $rs->fields[5];

        $rs->MoveNext();
    }

    if($rs==null)
    {
        throw new Exception("정보 조회 오류 다시 시도하세요",4395);
    }
    if($sel_quantity <=0 && $sel_price <=0 )
    {
        throw new Exception("정보 조회 오류 다시 시도하세요",4395);
    }
    if(empty($sel_title)){
        throw new Exception("정보 조회 오류 다시 시도하세요",4395);
    }


        ?>
        <html>
        <body>
        <h2><?= $sel_title ?>의 구매페이지</h2>
        <hr width="80%"/>
        <div id="#contsRow">

            <form name="memRegForm" method="post" action="buy_buyOk.php" class="formtag">

                <ul style="list-style-type:square">

                    <li><label for="nSelNum">판매 번호 : <?= $sel_id ?></label>
                        <input type="hidden" name="sel_id" value="<?= $sel_id ?>"/></li>
                    <br/>


                    <li><label for="nPrice">개당 가격 : <?= $sel_price ?></label>
                        <input type="hidden" name="sel_price" value="<?= $sel_price ?>"/></li>
                    <br/>

                    <li><label for="nQuantity">구매 수량:</label>
                        <input type="number" name="sel_quantity" min="1" max="100"/>[min 1 / max 100]
                    </li>
                    <br/>
                    <input type="hidden" name="nQuantity" value="<?= $sel_quantity ?>"/>
                    <input type="hidden" name="author" value="<?= $sel_auth ?>"/>
                    <input type="hidden" name="step" value="2"/>
                    <input type="hidden" name="regdate" value="<?= $sel_regdate ?>"


                </ul>
                <ul>
                    <hr width="80%"/>

                    <li align="center"><input type="submit" value="구매하기"/><input type="reset" value="다시작성하기"/></li>
                </ul>

            </form>
        </div>


        </body>
        </html>
        <?php
    } else {
        echo "<script>
        alert(\"Login first.\");
        </script>";


        echo("<script>location.href='../member/mem_login.php';</script>");

    }

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
include("../_inc/footer.php");