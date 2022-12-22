<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include("../_inc/header.php");
///db연결
include('../_inc/DBconnect.php');

try {
	if ($_SESSION['member_Session_admin'] != 1) {     // 등록 권한이 있는지 체크  회원 and 관리자
        throw new Exception("물품을 등록할 권한이 없습니다.",4);
    }
?>
<html>
<body>
<h2>물품 등록 결과</h2>
<hr width="80%"/>
<div id="#contsRow">
    <?php
    /******************************   물품 등록 관련 코드 Start   ***************************************************/
    /* 변수설정 S*/
    $strTitle = $_POST['strTitle'];					 // 물품판매 제목
    $nPrice = $_POST['nPrice'];						 // 물품가격
    $nQuantity = $_POST['nQuantity'];				 // 판매 수량
    $strConts = $_POST['strConts'];					 // 판매 내용
    $strAuthor = $_SESSION['member_Session_admin'];  // 세션에서 얻은 멤버의 아이디
    /* 변수설정 E*/

    if(empty($strTitle)){
        throw new Exception("제목을 적어주세요");
    }
    if(empty($nPrice) || $nPrice<=0){
        throw new Exception("판매가를 다시 확인하세요");
    }
    if(empty($nQuantity) || $nQuantity <=0){
        throw new Exception("수량을 확인하세요 ");
    }
    if(empty($strAuthor)){
        throw new Exception("오류가 발생하였습니다 다시 시도해 주세요.");
    }
	//내용 if
	if ($_FILES['strImg']['error'] == 4) {         // 이미지를 등록하지 않으면 list로 돌려보낸다.
		throw new Exception("이미지 등록 오류",94);
    }


	///트랜잭션 스타트
    $trans_check = $db->StartTrans();
    if ($trans_check == false){
        throw new Exception("트랜젝션오류", 44);
    }
    $rs = $db->Execute("insert into sel(sel_title, sel_author, sel_price, sel_quantity, sel_contents, sel_regdate, sel_editdate) values ('$strTitle','$strAuthor', $nPrice, $nQuantity,'$strConts',now(),now())");
	
	$sel_num = $db->Insert_ID();        // 상품등록을 하면 auto_increment 값(sel_num)을 저장
    if($sel_num==null){
        throw new Exception("상품등록오류",9e134);
    }

    echo "lastid=" . $sel_num;
    /******************************   물품 등록 관련 코드 End  ******************************************************/
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



    $check = getimagesize($_FILES["strImg"]["tmp_name"]);
    if (empty($check)) {
        echo "File is an image - " . $check["mime"] . ".";
    }
	if($check == false){
		$error_checker = 0;
	}
    if (file_exists($target_file)) {
        echo "already exist .";
        $error_checker = 0;                         // 중복이미지 등록 제한
    }
    if ($_FILES["strImg"]["size"] > 3000000) {      // 이미지의 크기 제한
        echo "over size !.";
        $error_checker = 0;
    }
    if ($error_checker == 0) {
		throw new exception ('파일 등록을 할 수 없습니다.');
    }
	/****************************************************
	image파일에서 게스트 쓰기 권한이 없어서 넘기지 못하는 오류 발생 -> chmod 707로변경하여 게스트에 읽기 쓰기 실행권한 부여
	****************************************************/
    if (move_uploaded_file($_FILES["strImg"]["tmp_name"], $target_file)) {  // 임시파일을 실제 경로로 이동
		$real_name = $_FILES["strImg"]["name"];
        $imgurl = "http://192.168.56.116/shop/sel/image/" . $tmp_name;
        $size = $_FILES["strImg"]["size"];
        // DB에 image 정보를 입력
        $rs = $db->Execute("insert into sel_image(image_filename, image_url, image_size, sel_id) values('$real_name','$imgurl','$size','$sel_num')");
        if ($rs=$db->Affected_Rows() <1){
	        throw new Exception("이미지 등록 오류",93394);
        }
	}else {
		echo "<p>Sorry, there was an error uploading your file.</p>";
        echo "<br><button type='button' onclick=\"location.href='sel_list.php'\">돌아가기</button>";
    }
	echo "<p>The file " . basename($_FILES["strImg"]["name"]) . " has been uploaded.</p>";
    echo "<br><button type='button' onclick=\"location.href='sel_list.php'\">돌아가기</button>";
    unset($rs);
	$db->CompleteTrans();
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
		///trans_check 값이 true 이면
		if (isset($db) && $db->IsConnected() == true) {
			if ($error_check == 0) {
				///롤백
				$db->FailTrans();
				///커밋
				$db->CompleteTrans();
				///변수 삭제
				unset($error_check);
			}
			unset($trans_check);
			$db->close();
			unset($db);
		}
	exit;
}
?>