<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///DB연결 (db연결 include로 수정)

include('../_inc/DBconnect.php');

$trans_check=null;
$img_url = "";
$num=1;
?>
<html>
<body>
<h2>판매물품 리스트</h2>

<hr width="80%"/>
<div id='contsRow'>

    <ul id="headerRow">
        <li id="thCol">번호</li>
        <li id="thCol">제품 사진</li>
        <li id="thCol">제목</li>
        <li id="thCol">가격</li>
        <li id="thCol">잔여 수량</li>

    </ul>

    <?php


    $rs = $db->Execute("select sel.sel_title, sel.sel_author, sel.sel_price, sel.sel_quantity, sel.sel_contents, img.image_url, sel.sel_id from sel sel join sel_image img on sel.sel_id = img.sel_id where sel.sel_quantity>0  for update ");

try{
    while (!$rs->EOF) {

            $sel_title = $rs->fields[0];
            $sel_author = $rs->fields[1];
            $sel_price = $rs->fields[2];
            $sel_quantity = $rs->fields[3];
            $sel_contents = $rs->fields[4];
            $img_url = $rs->fields[5];
            $sel_id = $rs->fields[6];

        $rs->MoveNext();
        /******************************* 이미지 리사이징 Start **********************************************/
        $img_w = 30;                //width

        $img_h = 30;                // height

	
        $imgsize = GetImageSize($img_url);
        $img_width = $imgsize[0];       // 가로사이즈 선언
        $img_height = $imgsize[1];     // 세로사이즈 선언

        if ($img_width > $img_w) {
            $re_w_size = $img_w; // 가로사이즈가 지정사이즈보다 크면 지정사이즈로 고정
        } else {
            $re_w_size = $img_width; // 가로사이즈가 지정사이즈보다 작거나 같으면 기존사이즈 유지
        }
        if ($img_height > $img_h) {
            $re_h_size = $img_h; // 세로사이즈가 지정사이즈보다 크면 지정사이즈로 고정
        } else {
            $re_h_size = $img_height; // 세로사이즈가 지정사이즈보다 작거나 같으면 기존사이즈 유지
        }

        /******************************* 이미지 리사이징 end **********************************************/

        /********************* 조회 데이터 오류 체크 start *******************/
        if($rs==null)
        {
            throw new Exception("물품조회 오류 입니다 다시 접속해 보세요", 4053);
        }
        if(empty($sel_title) && !isset($sel_title)){
            throw  new Exception("물품정보가 잘못되었습니다.",4043);
        }
        if(empty($sel_author) && !isset($sel_author)){
            throw  new Exception("물품정보가 잘못되었습니다.",4043);
        }
        if(empty($sel_price) && !isset($sel_price) && $sel_price <= 0){
            throw  new Exception("물품정보가 잘못되었습니다.",4043);
        }
        if(empty($sel_quantity) && !isset($sel_quantity) && $sel_quantity <=0){
            throw  new Exception("물품정보가 잘못되었습니다.",4043);
        }
        if(empty($img_url) && !isset($img_url)){
            throw  new Exception("물품정보가 잘못되었습니다.",4043);
        }
        if(empty($sel_id) && !isset($sel_id)){
            throw new Exception("물품조회 오류 입니다 다시 접속해 보세요", 4853);
        }


        /********************* 조회 데이터 오류 체크  ********************/
        ?>



        <ul id="tdRow">
            <li id="tdCol"><?= $num++ ?></li>
            <li id="tdCol"><img src="<?= $img_url ?>" width="<?= $re_w_size ?>" height="<?= $re_h_size ?>" border=0/></li>
            <li id="tdCol"><?= $sel_title ?></li>
            <li id="tdCol"><?= $sel_price ?></li>
            <li id="tdCol"><?= $sel_quantity ?></li>

            <form name="viewitem" method="post" action="sel_view.php">
                <input type="hidden" name="sel_id" value="<?= $sel_id ?>"/>
                <input type="hidden" name="step" value="1"/>
                <input type="submit" value="제품 상세보기"/>


            </form>

        </ul>

    <?php }
    unset($rs);
    ?>
    <?php
    if (($_SESSION['member_Session_admin']===1)) {
        ?>

        <div>
            <form name="btnReg" method="post" action="sel_regForm.php">
                <input type="submit" value="등록하기">
            </form>
        </div>
    <?php }?>

</div>

<hr width="80%"/>

<?php
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
?>
</body>
</html>

