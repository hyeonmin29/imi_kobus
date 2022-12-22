<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///db연결
include('../_inc/DBconnect.php');

$sel_id = $_POST['sel_id'];
$step = $_POST['step'];
$img_url = "";
$sel_status="판매중";



try{
	///step이 빈값, 1, 변수가 아니면
	if(!empty($step) & $step !=1 & !isset($step) ){
		///예외처리
	  throw new Exception("비정상적인 접근 입니다",999);
	}///만약 sel_id가 빈값, 변수가 아니면
	if(!empty($sel_id) & !isset($sel_id)){
		///예외처리
	  throw new Exception("판매정보 오류",999);
	}



///만약 세션값이 존재하지 않으면
if (isset($_SESSION['member_Session_number'])) {  // 로그인에 성공한 유저만 사용할 수 있도록.
///sel과 sel_image테이블을 조인하고 sel.sel_id와 img.sel_id가 같은값을 모두 가져오고 sel_id와 $sel_id가 같은 값에서 조회하고 레코드 락
$rs = $db->Execute("select sel.sel_title, sel.sel_author, sel.sel_price, sel.sel_quantity, sel.sel_contents, img.image_url, sel.sel_id from sel sel join sel_image img on sel.sel_id = img.sel_id where sel.sel_id='$sel_id' for update");
///rs 쿼리문이 실행되지 않으면
if(!$rs){
	///예외처리
    throw new Exception("정보조회 오류",44);
}
///rs가 아닌값이 eof를 만날때까지 루프
while (!$rs->EOF) {


//  print_r($rs);
    $sel_title = $rs->fields[0];
    $sel_author = $rs->fields[1];
    $sel_price = $rs->fields[2];
    $sel_quantity = $rs->fields[3];
    $sel_contents = $rs->fields[4];
    $img_url = $rs->fields[5];
    $sel_id = $rs->fields[6];
	///다음 쿼리 진행
    $rs->MoveNext();

	///만약 sel_id가 빈값, 변수 아니면
    if(!empty($sel_id) & !isset($sel_id)){
		///예외처리
        throw new DBException("판매정보 오류",999);
    }///만약 sel_title 빈값, 변수 아니면
    if(!empty($sel_title) & !isset($sel_title)){
		///예외처리
        throw new DBException("판매정보 오류",999);
    }///sel_price 빈값, 변수 존재하지않고 0보다 적으면
    if(!empty($sel_price) & !isset($sel_price) & $sel_price<0){
        ///예외처리
		throw new DBException("판매정보 오류",999);
    }///img_url 빈값, 변수 존재하지 않으면
    if(!empty($img_url) & !isset($img_url)){
		///예외처리
        throw new DBException("판매정보 오류",999);
    }///sel_author 빈값, 변수 존재하지 않으면
    if(!empty($sel_author) & !isset($sel_author)){
		///예외처리
        throw new DBException("판매정보 오류",999);
    }

    /******************************* 이미지 리사이징 Start **********************************************/
    $img_w = 150;                //width

    $img_h = 150;                // height

	///배열로 이미지 넓이,세로,이미지 구분번호, 스타일, 비트값,이미지타입을 추출
    $imgsize = GetImageSize($img_url);
	///넓이 대입
    $img_width = $imgsize[0];       // 가로사이즈 선언
	///세로 사이즈 대입
    $img_height = $imgsize[1];     // 세로사이즈 선언
	///만약 넓이가 150보다 크면
    if ($img_width > $img_w) {
		///re_w_size는 150으로 지정
        $re_w_size = $img_w; // 가로사이즈가 지정사이즈보다 크면 지정사이즈로 고정
    ///아닐경우
	} else {
		///원래 사이즈 지정
        $re_w_size = $img_width; // 가로사이즈가 지정사이즈보다 작거나 같으면 기존사이즈 유지
    }///만약 세로 사이즈가 150보다 크면
    if ($img_height > $img_h) {
		///150으로 고정
        $re_h_size = $img_h; // 세로사이즈가 지정사이즈보다 크면 지정사이즈로 고정
    ///아닐경우
	} else {
		///원래 사이즈 지정
        $re_h_size = $img_height; // 세로사이즈가 지정사이즈보다 작거나 같으면 기존사이즈 유지}
    }

    /******************************* 이미지 리사이징 end **********************************************/

//판매상태 체크
	///남은 개수가 0 이하이면
    if($sel_quantity<=0){
		///sel_status는 품절입니다 데이터 추가
        $sel_status="품절입니다";
    }
}



?>
<html>
<body>
<h2>[ <?= $sel_title ?> ]의 상세정보</h2>
<hr width="80%"/>
<div id="#contsRow">
	
    <form name="memRegForm" method="post" action="../buy/buy_buyForm.php" class="formtag">

        <ul style="list-style-type:none">

            <li><label for="nSelNum">제품사진 : </label><img src="<?= $img_url ?>" width="<?= $re_w_size ?>"
                                                         height="<?= $re_h_size ?>" border=0/></li>
            <br/>


            <li><label for="nPrice">가 격 : </label> <?= $sel_price ?>(원/개당)</li>
            <br/>

            <li><label for="nQuantity">잔여 수량 : </label> <?= $sel_quantity ?></li>
            <br/>

            <li><label for="nQuantity">내 용 : </label> <?= $sel_contents ?></li>
            <br/>
            <li><label for="nQuantity">판매상태 </label> 분할거래 //  <?= $sel_status ?></li>
            <br/>
            <input type="hidden" name="sel_id" value="<?= $sel_id ?>"/>
            <input type="hidden" name="step" value="2"/>


        </ul>
        <ul>
            <hr width="80%"/>

            <li align="center"><input type="submit" value="구매하기"/>
                <button type='button' onclick="location.href='sel_list.php'">돌아가기</button>
            </li>
            <?php // admin전용 메뉴
			///member_admin에 admin세션 대입
            $member_admin = $_SESSION['member_Session_admin'];
            if ($member_admin == 1) {
                ?>
                <li> - for admin menu - <br/>
                    <button type='button' onclick="location.href='sel_editForm.php?sel_id=<?= $sel_id ?>'">수정하기</button>
                    <button type='button' onclick="location.href='sel_delForm.php?sel_id=<?= $sel_id ?>'">삭제하기</button>
                </li>
            <?php } ?>
        </ul>

    </form>
</div>


</body>
</html>
<?php
} else {
	///경고창 띄우고
    echo "<script>alert(\"Login first.\");</script>";
	///mem_login.php로 이동
    echo("<script>location.href='../member/mem_login.php';</script>");
}

}catch (Exception $e) {
	///예외처리 발생 시 처리
    $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
    echo "<script>alert(\" $error_msg \");</script>";
    echo("<script>location.href='../index.php';</script>");
	///db가 변수이고 연결되어있으면
    if (isset($db) && $DB->IsConnected() === true) {
        ///롤백
		$db->FailTrans();
        ///연결종료
		$db->Close();
        ///변수삭제
		unset($db);
    }
	///종료
    exit;
}
include("../_inc/footer.php");