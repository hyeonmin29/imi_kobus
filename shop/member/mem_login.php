<?php
include('../_inc/header.php');
require('../adodb5/adodb.inc.php');
?>
<html>
<body>
<h2>로그인 페이지</h2>
<hr width="80%"/>
<div id="#contsRow">
	<!-- 페이지 이름은 memlogin이고 메서드 방식은 post, loginresult.php로 정보 전송 클래스는 formtag-->
    <form name="memlogin" method="post" action="mem_loginresult.php" class="formtag">
		<!--사각형 UI로 생성-->
        <ul style="list-style-type:square">
				<?php
				/***************세션체크***************/
				///만약 member_Session_id가 존재하면
				if (!empty($_SESSION['member_Session_id']))
				{
			    ?>
                <li><label for="strId">이미 로그인 되어있습니다.</label></li>
				<?php
				}
				if(empty($_SESSION['member_Session_id'])){
				?>
					<!--아이디 / 비밀번호 입력 폼-->
					<li><label for="strId">id</label> <input type="text" name="strId" class="strId"/></li>
					<li><label for="strPw">pw</label> <input type="password" name="strPw" class="strPw"/></li>
        </ul>
		<ul>
					<hr width="80%"/>
					<!--가운데 정렬-->
					<li align="center">
					<!--로그인하기-->
					<input type="submit" value="로그인하기"/>
					<!--회원 가입 버튼이 생기고, 누르게 되면 mem_regForm으로 이동-->
					<input type="button" value="회원가입" onclick="window.location='./mem_regForm.php'">
					</li>
            <?php 
			}
			?>
		</ul>
    </form>
</div>
<?php
/// footer정보 가져옴
include("../_inc/footer.php");