<?php
include("../_inc/header.php");
require("../adodb5/adodb.inc.php");

?>

<html>
<body>
<h2>로그인 페이지</h2>
<hr width="80%"/>
<div id="#contsRow">
    <form name="memlogin" method="post" action="mem_loginresult.php" class="formtag">

        <ul style="list-style-type:square">


            <?php

            /***************세션체크***************/
            if (isset($_SESSION['member_Session_id'])) {

                echo $_SESSION['member_Session_id'] . '세션살음';

            } else {
                echo "세션 죽음";
            }
            /***************세션체크***************/

            if (isset($_SESSION['member_Session_id']))
            {
                ?>

                <li><label for="strId">you aready login. go back!</label></li>


                <?php
            }else{
            ?>
            <!--아이디 / 비밀번호 입력 폼-->

            <li><label for="strId">id</label> <input type="text" name="strId"
                                                     class="strId"/></li>

            <li><label for="strPw">pw</label> <input type="password"
                                                     name="strPw" class="strPw"/></li>


        </ul>
        <ul>
            <hr width="80%"/>

            <li align="center">


                <input type="submit" value="로그인하기"/>
                <input type="button" value="회원가입" onclick="window.location='./mem_regForm.php'">
            </li>
            <?php } ?>
        </ul>

    </form>

</div>
<?php
include("../_inc/footer.php");