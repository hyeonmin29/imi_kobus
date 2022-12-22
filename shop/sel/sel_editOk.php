<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');
error_reporting(E_ALL);
ini_set("display_errors", 1);


?>
<html>
<body>
<h2>물품 정보 수정결과</h2>
<hr width="80%"/>
<div id="#contsRow">

    <?php


    /******************************   물품 수정관련 코드 Start   ***************************************************/
    $sel_id = $_POST['sel_id'];
    $sel_title = $_POST['strTitle'];
    $sel_price = $_POST['nPrice'];
    $sel_quantity = $_POST['nQuantity'];
    $sel_contents = $_POST['strConts'];
    $sel_imgname = $_POST['sel_img'];
    $real_name = "";

    $strAuthor = $_SESSION['member_Session_id'];

    $rs = $db->Execute("update sel set sel_title='$sel_title', sel_price='$sel_price', sel_quantity='$sel_quantity', sel_contents='$sel_contents' where sel_id=$sel_id ");


    /******************************   물품 수정 관련 코드 End  ***************************************************/

	///이미지를 등록하지 않았으면 4라는 오류코드를 이용해 업데이트를 하지 않았다고 뜬다.
    if (($_FILES['strImg']['error']) != 4) {             // 이미지를 등록하지 않으면 업데이트를 하지 않는다.

        // 이미지를 등록했으면

        /******************************   이미지 등록 관련 코드 Start  ***************************************************/
        $image_dir = "./image/";
        $error_checker = 1;     // 에러 감지 코드


        /*** 파일명 변경 ***/

        $filename = $_FILES['strImg']['name'];
		
		///strrchr을 사용해 .을 마지막으로 사용하는 위치를 찾고 substr를 이용해 "."다음부터 문자를 추출
        $ext = substr(strrchr($filename, "."), 1);
		///위에서 추출한 문자에서 대문자를 소문자로 변환
        $ext = strtolower($ext);            // 확장자 추출
		///microtime은 한 자리의 공백을 두고 초 미만단위 초 단위로 현재 시각이 출력되는데
		///함수 결과를 공백으로 구분하고 배열에 담는다
        $tmp_file = explode(' ', microtime());
		///초 미만단위의 배열에서 배열1번~ 6개의 값을 배열 0번 자리에 배치한다 
        $tmp_file[0] = substr($tmp_file[0], 2, 6);
		///파일 이름을 위에서 가져온 배열값.확장자명으로 저장
        $filename = $tmp_file[1] . $tmp_file[0] . '.' . $ext;


        //$_FILES['strImg']['name'] = $filename;
        $tmp_name = $filename;
        /*** 파일명 변경 ***/
		
		///타겟파일은 /image/파일이름으로 지정 
        $target_file = $image_dir . $filename;

		///submit값이 존재하면
        if (isset($_POST["submit"])) {
			///전송받은 파일의 정보를 체크변수에 담는다
            $check = getimagesize($_FILES["strImg"]["tmp_name"]);
			///만약 파일 사이즈가 true라면
            if ($check !== false) {
				//출력
                echo "File is an image - " . $check["mime"] . ".";
                $error_checker = 1;
			///false이면 이미지를 체크하지 않고 에러체커 0
            } else {
                echo "File is not an image.";           // 파일 형식 제한.
                $error_checker = 0;
            }
        }
        // Check if file already exists
		///타겟 파일이 이미 존재하면
        if (file_exists($target_file)) {
			///출력하고 체커값 0
            echo "exists error.";
			$error_checker = 0;                         // 중복이미지 등록 제한
        }
        // Check file size
		///만약 파일의 사이즈가 3000000이 넘으면
        if ($_FILES["strImg"]["size"] > 3000000) {      // 이미지의 크기 제한
            ///출력하고 체커값 0
			echo "over size.";
            $error_checker = 0;
        }
		///만약 체커값이 0이면
        if ($error_checker == 0) {
			///에러출력
            echo "E R R O R !!!!!!!!!!!!!!!!!!";        // 오류안내

		///1이라면
        } else {// 문제없음 .
			///업로드된 파일을 target_file로 이동해서 등록
            if (move_uploaded_file($_FILES["strImg"]["tmp_name"], $target_file)) {  // 임시파일을 실제 경로로 이동
				///파일이름
                $real_name = $_FILES["strImg"]["name"];
				///실제 경로
                $imgurl = "http://192.168.56.13/sel/image/" . $tmp_name;
                ///사이즈
				$size = $_FILES["strImg"]["size"];


                // DB정보 수정 -> sel_image테이블에 파일이름,url,사이즈를 수정하는데 조건은 sel_id와 $sel_id가 같아야함
                $rs = $db->Execute("update sel_image set image_filename='$real_name', image_url='$imgurl', image_size='$size' where sel_id=$sel_id ");


            }
        }
        echo "<p>Sorry, there was an error uploading your file.</p>";
        echo "<br><button type='button' onclick=\"location.href='sel_list.php'\">돌아가기</button>";
    }
    /******************************   이미지 등록 관련 코드 End  ***************************************************/

    ?>


    <hr width="80%"/>

    <ul>

        <li>title :<?= $sel_title ?></li>
        <br/>
        <li>price :<?= $sel_price ?></li>
        <br/>
        <li>quantity :<?= $sel_quantity ?></li>
        <br/>
        <li>contents :<?= $sel_contents ?></li>
        <br/>

        <?php if (($_FILES['strImg']['error']) != 4) { ?>
            <li>image :<?= $real_name ?></li><br/>
        <?php } else { ?>
            <li>image :<?= $sel_imgname ?></li><br/>


            <?php
            echo "<p>The file " . basename($_FILES["strImg"]["name"]) . " has been uploaded.</p>";
            echo "<br><button type='button' onclick=\"location.href='sel_list.php'\">돌아가기</button>";
        } ?>


    </ul>


</div>


</body>
</html>
<?php
include("../_inc/footer.php");