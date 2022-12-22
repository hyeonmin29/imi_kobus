<?php

/* 물품 수정페이지 */

include("../_inc/header.php");
include("../adodb5/adodb.inc.php");
///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');

ini_set('display_errors', true);
error_reporting(E_ALL);
///sel_id를 get메소드로 가져옴 url에 아이디 흔적이 남음
$sel_id = $_GET['sel_id'];

///admin 세션 가져오기
$admin_checker = $_SESSION['member_Session_admin'];
///admin_checker가 1이면
if ($admin_checker == 1){     // 등록 권한이 있는지 체크
	///sel테이블과 sel image테이블을 조인하여 title,price,quantity,contents,filename,url을 조회하는데 sle_id=$sel_id여야하고 sel.id와 img.id가 같은 데이터 조회한다.
	$rs = $db->Execute("select sel.sel_title, sel.sel_price, sel.sel_quantity, sel.sel_contents, img.image_filename, img.image_url from sel sel join sel_image img on sel.sel_id = img.sel_id where sel.sel_id=$sel_id");
	///rs가 아닌 값이 EOF를 만날때까지 movenext를 통해 다음 쿼리진행
	while (!$rs->EOF) {
		$sel_title = $rs->fields[0];
		$sel_price = $rs->fields[1];
	    $sel_quantity = $rs->fields[2];
	    $sel_contents = $rs->fields[3];
	    $sel_img = $rs->fields[4];
	    $img_url = $rs->fields[5];
	    $rs->MoveNext();
}


?>

<html>
<body>
<h2>등록상품 수정</h2>
<hr width="80%"/>
<div id="#contsRow">

    <form name="seleditForm" method="post" action="sel_editOk.php" class="formtag" enctype="multipart/form-data">

        <ul style="list-style-type:none">


            <input type="hidden" name="sel_id" value="<?= $sel_id ?>"/>
            <input type="hidden" name="sel_img" value="<?= $sel_img ?>"/>
            <br/>
            <li><label for="strTitle">제목</label> <input type="text" name="strTitle"
                                                        class="strTitle" value="<?= $sel_title ?>"/></li>
            <br/>

            <li><label for="nPrice">가격</label> <input type="number"
                                                      name="nPrice" class="nPrice" value="<?= $sel_price ?>"/></li>
            <br/>

            <li><label for="nQuantity">수량</label> <input type="number"
                                                         name="nQuantity" class="nQuantity"
                                                         value="<?= $sel_quantity ?>"/></li>
            <br/>

            <li><label for="strConts">내용</label>
                <textarea name="strConts" cols="5" rows="10"><?= $sel_contents ?></textarea></li>
            <br/>

            <li><label for="strImg">수정 이미지 선택</label> <input type="file" name="strImg" class="strAdd"
                                                             accept=".jpg,.gif,.png"/><br/> 현재 등록 이미지 <?= $sel_img ?>

                <?php
                /******************************* 이미지 리사이징 Start **********************************************/


                $img_w = 100;                //width

                $img_h = 100;                // height

                $imgsize = GetImageSize($img_url);

                $img_width = $imgsize[0];       // 가로사이즈 선언
                $img_height = $imgsize[1];     // 세로사이즈 선언
				///넓이가 100보다 클 때
                if ($img_width > $img_w) {
                    ///지정넓이는 100
					$re_w_size = $img_w; // 가로사이즈가 지정사이즈보다 크면 지정사이즈로 고정
				///넓이가 100보다 작거나 같을 때
				} else {
					///지정 넓이는 이미지 사이즈 유지
                    $re_w_size = $img_width; // 가로사이즈가 지정사이즈보다 작거나 같으면 기존사이즈 유지
                }
				///세로사이즈가 100보다 클 때
                if ($img_height > $img_h) {
					///세로 사이즈는 100
                    $re_h_size = $img_h; // 세로사이즈가 지정사이즈보다 크면 지정사이즈로 고정
                ///세로 사이즈가 100보다 작거나 같을 때
				} else {
					///세로 사이즈는 이미지 사이즈 유지
                    $re_h_size = $img_height; // 세로사이즈가 지정사이즈보다 작거나 같으면 기존사이즈 유지}
                }

                /******************************* 이미지 리사이징 end **********************************************/
                ?>
                <img src="<?= $img_url ?>" width="<?= $re_w_size ?>" height="<?= $re_h_size ?>" border=0/></li>
            <br/>
            <ul>
                <hr width="80%"/>

                <li align="center"><input type="submit" value="수정하기"/><input type="reset"
                                                                             value="다시작성하기"/></li>
            </ul>

    </form>
    <?php

    } else {
        // 관리자 권한이 없는 경우 물품 수정을 하지 못하게 한다.
        echo "<script>
    alert(\"[ your not admin ]  go index.\");
    window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
</script>";
        echo("<script>location.href='../index.php';</script>");
    }
    ?>
</div>
</body>
</html>
<?php
include("../_inc/footer.php");