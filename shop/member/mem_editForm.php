<?php
///header정보 받아오기
include("../_inc/header.php");
///adodb정보 받아오기
include("../adodb5/adodb.inc.php");

///DB연결 (db연결 include로 수정)
include('../_inc/DBconnect.php');

ini_set('display_errors', true);
error_reporting(E_ALL);


$member_id = "";
$member_tel = 0;
$member_name = "";
$member_address = "";
$member_gender = 0;

///세션 값 대입
$mem_num = $_SESSION['member_Session_number'];


	///member테이블에서 아이디, 이름, 성별, 전화번호 가져오는데 mem_num의 값과 일치하는 정보 가져옴
    $rs = $db->Execute("select member_id, member_name, member_gender, member_tel, member_address from member where member_num='$mem_num'");
    ///rs 정보 배열에 다 들어가지 않으면 계속 반복하고 정보 대입 끝나면 루프 탈출
	while (!$rs->EOF) {
        //  print_r($rs);
        $member_id = $rs->fields[0];
        $member_name = $rs->fields[1];
        $member_gender = $rs->fields[2];
        $member_tel = $rs->fields[3];
        $member_address = $rs->fields[4];
        $rs->MoveNext();
    }
	///만약 성별이 1이면
    if ($member_gender == 1) {
		///성별 변수값은 남자
        $member_gender = "남";
	///성별이 2이면
    } else if ($member_gender == 2) {
		///성별 변수값은 여자
		$member_gender = "여";
    ///나머지는
	} else
		///성별 변수값은 ??
        $member_gender = "??";


    ?>

    <html>
<body>
<h2>회원 수정</h2>
<!--넓이 80%-->
<hr width="80%"/>

<div id="#contsRow">
	<!--입력받는 변수값은 memEditForm/ 메소드 형식은 post/ 받은 값은 mem_editOk.php로 보내고 /Class는 form에 formtag부여-->
    <form name="memEditForm" method="post" action="mem_editOk.php" class="formtag">
		<!--UI는 사각형-->
        <ul style="list-style-type:square">
			<!--id를 클릭하면 strId 입력하는 곳으로 자동으로 커서가 이동되고 타입은 텍스트, 입력받는 변수값은 strId class는 li값에 strId 부여-->
			<!--disabled를 사용해서 입력하지 못하게 막고 readonly로 읽을 수만 있게 만든다. -->
            <li><label for="strId">id</label> <input type="text" name="strId"
                                                     class="strId" value="<?php echo $member_id; ?>"
                                                     disabled="readonly"/></li>
			<!--php,disabled 제외 위와 동일-->
            <li><label for="strId">password</label> <input type="text" name="strId"
                                                           class="strId"/></li>
			<!--위와 동일-->
            <li><label for="strName">이름</label> <input type="text"
                                                       name="strName" class="strName"
                                                       value="<?php echo $member_name; ?>"/></li>

			<!--동그라미 라디오 버튼을 만들고 성별에 체크할 수 있게 만들어준다. -->
            <li><label for="nSex">성별</label> 남<input type="radio" name="nSex" value="1"/>
											 여<input type="radio" name="nSex" value="2"/>
											 ???<input type="radio" name="nSex" value="3"/>
											 기존 성별 :<?php echo $member_gender; ?></li>
			<!--숫자 적는 빈칸이 생기고 nTel변수를 입력하고 li에 class nTel를 적용시킨다-->
            <li><label for="nTel">전화번호</label> <input type="number"
                                                      name="nTel" class="nTel" value="<?php echo $member_tel; ?>"/></li>

			<!--위와 동일-->
            <li><label for="strAdd">주소</label> <input type="text"
                                                      name="strAdd" class="strAdd" size="50"
                                                      value="<?php echo $member_address; ?>"/></li>


        </ul>
        <ul>
            <hr width="80%"/>
            <li align="center">
				<!--수정하기 버튼 생성-->
                <input type="submit" value="수정하기"/>
				<!--리셋 버튼 생성-->
                <input type="reset" value="다시작성하기"/>
				<!--탈퇴하기 버튼 생성, 누르면 mem_regForm으로 이동-->
                <input type="button" value="탈퇴하기" onclick="window.location='./mem_regForm.php'"

                >
            </li>
        </ul>

    </form>
</div>
<?php
///footer정보 받아오기
include("../_inc/footer.php");