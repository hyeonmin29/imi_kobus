<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");
///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');
ini_set('display_errors', true);
error_reporting(E_ALL);
$img_url="";

///sel과 image테이블을 조인하여 조회하는데 sel_id=22인 행에서 sel.id와,img.id가 같은 값을 가져온다 조회 컬럼은 title, author, price, quantity, contents, url
$rs = $db->Execute("select sel.sel_title, sel.sel_author, sel.sel_price, sel.sel_quantity, sel.sel_contents, img.image_url from sel sel join sel_image img on sel.sel_id = img.sel_id where img.sel_id=22");

///rs가 아닌 값이 있을때까지 movenext를 사용해 쿼리문 실행
while (!$rs->EOF) {
//  print_r($rs);
    $sel_title = $rs->fields[0];
    $sel_author = $rs->fields[1];
    $sel_price = $rs->fields[2];
    $sel_quantity = $rs->fields[3];
    $sel_contents = $rs->fields[4];
    $img_url = $rs->fields[5];
    $rs->MoveNext();

}
/******************************* 이미지 리사이징 Start **********************************************/


$img_w = 50;                //width

$img_h = 30;                // height


///사이즈 추출
$imgsize=GetImageSize($img_url);

$img_width = $imgsize[0];       // 가로사이즈 선언
$img_height = $imgsize[1];     // 세로사이즈 선언

if($img_width > $img_w){
    $re_w_size = $img_w; // 가로사이즈가 지정사이즈보다 크면 지정사이즈로 고정
}else{
    $re_w_size = $img_width; // 가로사이즈가 지정사이즈보다 작거나 같으면 기존사이즈 유지
}
if($img_height > $img_h){
    $re_h_size = $img_h; // 세로사이즈가 지정사이즈보다 크면 지정사이즈로 고정
}else {
    $re_h_size = $img_height; // 세로사이즈가 지정사이즈보다 작거나 같으면 기존사이즈 유지}
}

/******************************* 이미지 리사이징 end **********************************************/




?>
<img src="<?php echo $img_url; ?>" width="<?php echo  $re_w_size ?>" height="<?php echo $re_h_size ?>" border=0>

<img src="<?php echo $img_url; ?>" width="<?php echo  $re_w_size ?>" height="<?php echo $re_h_size ?>" border=0>

<img src="<?php echo $img_url; ?>" width="<?php echo  $re_w_size ?>" height="<?php echo $re_h_size ?>" border=0>

<img src="<?php echo $img_url; ?>" width="<?php echo  $re_w_size ?>" height="<?php echo $re_h_size ?>" border=0>
