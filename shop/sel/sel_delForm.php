<?php 
	include("../_inc/header.php");

$sel_id = $_GET['sel_id'];


?>
<html>
<body>
<h2>물품 삭제</h2>
<hr width="80%" />
<div id="#contsRow">
    <form name="delproduct" method="post" action="../sel/sel_delOk.php" class="formtag">

   <p> <h2> 물품을 정말 삭제하시겠습니까?
    다시 되둘릴 수 없습니다.</h2></p>
        <input type="hidden" name="sel_id" value="<?=$sel_id?>"/>

<ul>
<hr width="80%" />

		<li align="center"><input type="submit" value="삭제하기" />
        <button type='button' onclick="location.href='sel_list.php'">리스트로 돌아가기</button>

</ul>

</form>
</div>
	
	

</body>
</html>
<?php
include("../_inc/footer.php");