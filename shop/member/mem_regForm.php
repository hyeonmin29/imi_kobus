<?php
include("../_inc/header.php");


?>
    <html>
    <body>
    <h2>회원 등록</h2>
    <hr width="80%"/>
    <div id="#contsRow">
		<!--이름은 mem reg form / 메소드는 post형식 / 전달페이지는 mem_regOK.php / 클래스는 formtag
			memRegForm이 action에 해당하는 페이지에 전달할 수 있는 파라미터로 사용-->
        <form name="memRegForm" method="post" action="mem_regOk.php" class="formtag">
			<!--UI는 사각형-->
            <ul style="list-style-type:square">
				<!--id를 클릭하면 strId를 입력하는 칸으로 이동 텍스트 타입이고, strId를 입력받고 오토포커스를 사용해 페이지로 왔을 때 자동으로 커서가 옮겨진다 -->
                <li><label for="strId">id</label> <input type="text" name="strId"
                                                         class="strId" autofocus/></li>
				<!--오토포커스 제외 위와 동일 -->
                <li><label for="strName">이름</label> <input type="text"
                                                           name="strName" class="strName"/></li>
				<!--오토포커스 제외 위와 동일 -->
                <li><label for="strPassword">비밀번호</label> <input type="password"
                                                                 name="strPassword" class="strPassword"/></li> &nbsp;&nbsp;&nbsp;
				<!--오토포커스 제외 위와 동일 -->
				<li><label for="strPassword">비밀번호확인</label> <input type="password"
                                                                   name="strPassword_check"/></li>
				<!---->
                <li><label for="nSex">성별</label>
					<!--라디오 버튼을 생성하고 성별값을 받음-->
                    남<input type="radio" name="nSex" value="1"/>
                    여<input type="radio" name="nSex" value="2"/>
                    ???<input type="radio" name="nSex" value="3"/>
                </li>
				<!--전화번호를 누르면 strTel을 입력하는 칸으로 커서 이동 타입은 번호 / strTel로 전달할 값을 입력-->
                <li><label for="strTel">전화번호</label> <input type="number" name="strTel" class="strTel"/></li>
				<!--위와 동일-->
                <li><label for="strAdd">주소</label> <input type="text" name="strAdd" class="strAdd" size="50"/></li>
            </ul>
            <ul>
                <hr width="80%"/>
				<!-- 중앙 정렬, 보내는 형식이고 등록하기라는 버튼이 생성  / 초기화버튼에 다시작성하기라는 글이 입력됨 -->
                <li align="center"><input type="submit" value="등록하기"/><input type="reset" value="다시작성하기"/></li>
            </ul>

        </form>
    </div>


    </body>
    </html>
<?php
///footer.php 정보 가져옴
include("../_inc/footer.php");