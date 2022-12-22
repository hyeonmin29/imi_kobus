<?php

/*물품 등록 페이지 */

include("../_inc/header.php");

if ($_SESSION['member_Session_admin'] == 1) {     // 등록 권한이 있는지 체크  // 관리자
    ?>
    <html>
    <body>
    <h2>물품등록 페이지</h2>
    <hr width="80%"/>
    <div id="#contsRow">

        <form name="selregform" method="post" action="sel_regOk.php" class="formtag" enctype="multipart/form-data">

            <ul style="list-style-type:square">

                <li><label for="strTitle">제목</label> <input type="text" name="strTitle"
                                                            class="strTitle"/></li>
                <br/>

                <li><label for="nPrice">가격</label> <input type="number"
                                                          name="nPrice" class="nPrice"/></li>
                <br/>

                <li><label for="nQuantity">수량</label> <input type="number"
                                                             name="nQuantity" class="nQuantity"/></li>
                <br/>

				<!--원래 코드 숫자4 및 textarea 위치변경
			    <li style="list-style-type:squrae"><label for="strConts">내용</label>
                <textarea name="strConts" cols="5" rows="10"></textarea></li> -->
				<li><label for="strConts">내용</label></li> <textarea name="strConts" cols="5" rows="10"></textarea>

                <br/>

                <li><label for="strImg">이미지 추가</label>
                    <input type="file" name="strImg" class="strImg" accept=".jpg,.gif,.png,.jpeg"/></li>
                <br/>
                <!--multiple-->
            </ul>
    </div>
    <ul>
        <hr width="80%"/>

        <li align="center">
            <input type="submit" value="등록하기"/>
            <input type="reset" value="다시작성하기"/></li>
        <br/>
     
    </ul>

    </form>
    </body>
    </html>
    <?php
} else {
    // 관리자 권한이 없는 경우 물품 등록을 하지 못하게 한다.
    echo "<script>
    alert(\"[ 물품을 등록할 수 있는 권한이 없습니다. ]  go index.\");
    window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
</script>";
    echo("<script>location.href='../index.php';</script>");
    
}

include("../_inc/footer.php");