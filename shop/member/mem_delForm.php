<?php
	///header.php 정보 가져옴
	include("../_inc/header.php");
?>
<html>
<body>
<h2>회원 탈퇴</h2>
<hr width="80%" />
<div id="#contsRow">
<!--memEditForm변수로 입력받고 메소드는 post / memEditForm값을 mem_EditOk.php로 전달 / 폼 클래스는 formtag-->
<form name="memEditForm" method="post" action="mem_EditOk.php" class="formtag">
	<!--UI 사각으로 생성-->
	<ul style="list-style-type:square">

	
		
		<li> 정말 탈퇴하시겠습니까? 남은 잔액은 : ********이며 ,  절대 환불되어지지 않습니다.</li>

        <?php


        ?>

	</ul>
<ul>
<hr width="80%" />
<li align="center">
<!--Yes! bye bye~ 버튼 생성-->
<input type="submit" value="Yes! bye bye~" />
<!--sorry .. go Home 버튼 생성 클릭하면 regForm으로 이동-->
<input type="button" value="Sorry .. go Home" onclick="window.location='./mem_regForm.php'"

>
		</li>
</ul>

</form>
</div>
<?php
///pooter 정보 가져옴
include("../_inc/footer.php");