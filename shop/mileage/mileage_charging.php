<?php
/************
 * 회원 포인트 충전 페이지
 *
 ************/


define("_STATE_","charge");

	///header.php 정보 가져옴
    include("../_inc/header.php");
	///만약 세션[member_Session_id]존재하지 않으면
    if(!isset($_SESSION['member_Session_id'])){          // 로그인을 하지 않았다면 충전 페이지 진입 금지.
    ///경고창 login first go back 띄우고
	///index.php로 이동
	echo "<script>
    alert(\"Login first.  go back.\");
    window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
</script>";
    echo("<script>location.href='../index.php';</script>");
    }
    ?>
    <html>
<body>
<h2>마일리지 충전소</h2>
<!--넓이 100%-->
<hr width="100%" />
<div id="#contsRow">
	<!--폼 이름은 milchargingform / 메소드는 post형식 / 입력값 mileage_charingOK.php로 이동 / 클래스 형식은 formtag-->
    <form name="milchargingform" method="post" action="mileage_chargingOk.php" class="formtag">
			<!--UI스타일은 사격형-->
            <ul style="list-style-type:square">



                <p> 원하시는 충전 방법을 고르세요 마일리지의 사용 우선 순위는 아래와 같습니다.</p>
                <p>1) 현금          2) 신용카드          3) 휴대폰       4) 구매포인트 마일리지 // 모든 구매는 100만원 한도입니다.</p><br/>
                <p>휴대폰으로 충전한 마일리지는 사용만 가능하고 출금은 불가능한 마일리지로 충전됩니다.</p></br>
                <li> 충전 방법을 정해주세요 :
					<!--mileage type이라는 드롭박스 생성-->
                    <SELECT name="mileage_type">
						<!--value값 순서대로 choose method/현금/신용카드/휴대폰을 고를 수 있음-->
                        <option value="0">choose method</option>
                        <option value="1">현금</option>
                        <option value="2">신용카드</option>
                        <option value="3">휴대폰</option>

                    </select></li><br/>
					<!--금액을 입력하세요를 누르면 mileage_price로 이동하고 숫자를 입력할 수 있는 곳(placegolder)으로 이동, 최대 값=1000000 -->
		<li>금액을 입력하세요 : <input type="number"  name="mileage_price" max=1000000  placeholder="충전금액 입력" "/></li>
                <br/>
              


        <li><!--클릭하면 mileage_PointChange.php로 이동하는 구매포인트 전환하기버튼 생성-->
            <input type="button" value="구매포인트 전환하기" onclick="window.location='/mileage/mileage_PointChange.php'">
			<!--클릭하면 mem_mymil.php로 이동하는 보유 마일리지 버튼 생성-->
            <input type="button" value="보유 마일리지 확인" onclick="window.location='/member/mem_mymil.php'">
            <!--hidden타입 생성하면 웹 개발자가 양식 제출 시 사용자가 보거나 수정할 수 없는 데이터 포함 가능-->
			<input type="hidden" name="step" value="1"/>

        </li>



	</ul>
<ul>
<!--너비 100%-->
<hr width="100%" />
		<!--중앙 정렬-->
		<li align="center">
		<!--Charging!보내기 버튼 생성-->
		<input type="submit" value="Charging!!" />
		<!--클릭 시 mem_regFormd으로 이동하는 go home 버튼 생성-->
		<input type="button" value="go home" onclick="window.location='/mem_regForm.php'">
		</li>
</ul>
</form>
</div>
<?php
///footer 정보 가져오기
include("../_inc/footer.php");