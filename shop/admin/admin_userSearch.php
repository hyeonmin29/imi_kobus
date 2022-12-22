<?php
include("../_inc/header.php");
?>
<html>
<body>
<h2>유저 마일리지 변경 페이지 step 1</h2>
<hr width="80%"/>
<div id="#contsRow">

    <form name="admineditmile" method="post" action="admin_editMile.php" class="formtag">

        <ul style="list-style-type:square">

            <li><label for="nSelNum">userid : </label>
                <input type="text" name="user_id"/>>
            </li>
            <br/>
        </ul>
        <ul>
            <hr width="80%"/>

            <li align="center"><input type="submit" value="다음단계"/></li>
        </ul>

    </form>
</div>


</body>
</html>
<?php
include("../_inc/footer.php");