<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

include("../_inc/header.php");
///db연결
include('../_inc/DBconnect.php');

/***************************trans_chect ->true or false /admin 세션*********************
$trans_check=null;
try {
	///regForm에서 admin을 제외한 회원 접근을 차단했기 때문에 여기서 차단을 안해도 될 것 같아서 삭제
	/************************************************************************************/
	if ($_SESSION['member_Session_admin'] != 1) {     // 등록 권한이 있는지 체크  회원 and 관리자
        throw new Exception("물품을 등록할 권한이 없습니다.",4);
    }
	*************************************************************************************/
?>
<html>
<body>
<h2>물품 등록 결과</h2>
<hr width="80%"/>
<div id="#contsRow">

    <?php


    if ($_FILES['strImg']['error'] == 4) {             // 이미지를 등록하지 않으면 list로 돌려보낸다.

       throw new Exception("이미지 등록 오류",94);
    }


    /******************************   물품 등록 관련 코드 Start   ***************************************************/


    /* 변수설정 S*/
    $strTitle = $_POST['strTitle'];                   // 물품판매 제목
    $nPrice = $_POST['nPrice'];                      // 물품가격
    $nQuantity = $_POST['nQuantity'];               // 판매 수량
    $strConts = $_POST['strConts'];                // 판매 내용
    $strAuthor = $_SESSION['member_Session_id'];  // 세션에서 얻은 멤버의 아이디
    /* 변수설정 E*/
/****************************************************수정
    if(!isset($strTitle) || empty($strTitle)){
        throw new Exception("제목을 적어주세요");
    }
    if(!isset($nPrice) || empty($nPrice) || $nPrice<=0){
        throw new Exception("판매가를 다시 확인하세요");
    }
    if(!isset($nQuantity) || empty($nQuantity) || $nQuantity <=0){
        throw new Exception("수량을 확인하세요 ");
    }
    if(!isset($strAuthor) || empty($strAuthor)){
        throw new Exception("오류가 발생하였습니다 다시 시도해 주세요.");
    }

****************************************************/


/********************트랜잭션 스타트 생성***********************

------------------------------------------------------------

*********************************************************/
    $rs = $db->Execute("insert into sel(sel_title, sel_author, sel_price, sel_quantity, sel_contents, sel_regdate, sel_editdate) values ('$strTitle','$strAuthor', $nPrice, $nQuantity,'$strConts',now(),now())");

	

    $sel_num = $db->Insert_ID();        // 상품등록을 하면 auto_increment 값(sel_num)을 저장

    if($sel_num==null){
        throw new Exception("상품등록오류",9e134);
    }
    echo "lastid=" . $sel_num;
    /******************************   물품 등록 관련 코드 End  ***************************************************/

    /******************************   이미지 등록 관련 코드 Start  ***************************************************/
    $image_dir = "./image/";
    $error_checker = 1;     // 에러 감지 코드


    /*** 파일명 변경 ***/

    $filename = $_FILES['strImg']['name'];

    $ext = substr(strrchr($filename, "."), 1);

    $ext = strtolower($ext);            // 확장자 추출

    $tmp_file = explode(' ', microtime());

    $tmp_file[0] = substr($tmp_file[0], 2, 6);

    $filename = $tmp_file[1] . $tmp_file[0] . '.' . $ext;


    //$_FILES['strImg']['name'] = $filename;
    $tmp_name = $filename;
    /*** 파일명 변경 ***/

    $target_file = $image_dir . $filename;

/**************************에러체커 수정***********
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["strImg"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $error_checker = 1;
        } else {
            echo "File is not an image.";           // 파일 형식 제한.
            $error_checker = 0;
        }
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "already exist .";
        $error_checker = 0;                         // 중복이미지 등록 제한
    }
    // Check file size
    if ($_FILES["strImg"]["size"] > 3000000) {      // 이미지의 크기 제한
        echo "over size !.";
        $error_checker = 0;
    }

    if ($error_checker == 0) {
        echo "E R R O R !!!!!!!!!!!!!!!!!!";        // 오류안내

    }
******************************************************/


/*******************else 삭제, 코드 위치 수정*********** unset error_checker 위치수정 -> 예외처리****
						151번줄 rs ->affected_row로 수정
	else {// 문제없음 .
		

		/****************************************************
		image파일에서 게스트 쓰기 권한이 없어서 넘기지 못하는 오류 발생 -> chmod 707로변경하여 게스트에 읽기 쓰기 실행권한 부여
		****************************************************
        if (move_uploaded_file($_FILES["strImg"]["tmp_name"], $target_file)) {  // 임시파일을 실제 경로로 이동

            $real_name = $_FILES["strImg"]["name"];
            $imgurl = "http://192.168.56.116/shop/sel/image/" . $tmp_name;
            $size = $_FILES["strImg"]["size"];


            // DB에 image 정보를 입력
            $rs = $db->Execute("insert into sel_image(image_filename, image_url, image_size, sel_id) values('$real_name','$imgurl','$size','$sel_num')");

            if(!$rs){
                throw new Exception("이미지 등록 오류",93394);
            }


            echo "<p>The file " . basename($_FILES["strImg"]["name"]) . " has been uploaded.</p>";
            echo "<br><button type='button' onclick=\"location.href='sel_list.php'\">돌아가기</button>";
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
            echo "<br><button type='button' onclick=\"location.href='sel_list.php'\">돌아가기</button>";
        }
    }

    unset($rs);
    unset($error_checker);

****************************************************************/
/*************트랜잭션 커밋 생성****************************************************

----------------------------------------------------------------------------

********************************************************************************/

    /******************************   이미지 등록 관련 코드 End  ***************************************************/
    ?>
</div>
</body>
</html>
<?php

include("../_inc/footer.php");

} catch (Exception $e) {
            $error_msg = '에러발생 : ' . $e->getMessage() . $e->getCode();
            echo "<script>alert(\" $error_msg \");</script>";
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