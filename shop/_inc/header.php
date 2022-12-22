<?php

/********************************************
admin 세션을 받을 수 없어서 세션 시작(sel_regform부분 연동)
*********************************************/
session_start();
//include('./css/style.css');
$basepath = "http://192.168.56.116/shop";
$admin_checker = $_SESSION['member_Session_admin'];
?>

<!DOCTYPE HTML>
<html>
<link rel="stylesheet" href="../css/style.css" type="text/css"/>
<head>
    <title>home</title>

</head>

<body>


<h2>Shoes Shop</h2>


<div id='header'>
    <div class="menubar">
        <ul>
			
            <li><a href="<?= $basepath ?>/index.php" id="current">Home</a></li>
            <li><a href="#" id="current">sell</a>
                <ul>

                    <li><a href="<?= $basepath ?>/sel/sel_list.php" id="current">sell_list</a></li>
                    <li><a href="<?= $basepath ?>/sel/sel_regForm.php" id="current">sell_reg</a></li>
                 </ul>
            </li>

            <li><a href="#" id="current">Mileage</a>
                <ul>

                    <li><a href="<?= $basepath ?>/mileage/mileage_charging.php" id="current">Charging</a></li>
                    <li><a href="<?= $basepath ?>/mileage/mileage_PointChange.php"
                           id="current">buypoint->buymileage</a></li>
                    <li><a href="<?= $basepath ?>/mileage/mileage_withdrawForm.php" id="current">withdraw</a></li>

                </ul>

            </li>
            <li><a href="###" id="current"></a></li>
            <li><a href="###" id="current">&nbsp; </a></li>
            <li><a href="###" id="current">&nbsp; </a></li>
            <li><a href="###" id="current">&nbsp;</a></li>          <!--blank-->
            <li><a href="###" id="current">&nbsp;</a></li>
            <li><a href="###" id="current">&nbsp;</a></li>
            <li><a href="###" id="current">&nbsp;&nbsp;&nbsp;&nbsp;</a></li>


            <?php
            if ($admin_checker == 1) {
                ?>
                <li><a href="#" id="current">admin page</a>
                    <ul>
                        <li><a href="<?= $basepath ?>/admin/admin_userSearch.php" id="current">Edit Mileage</a></li>
                        <li><a href="<?= $basepath ?>/admin/admin_listMile.php" id="current">Mileage List</a></li>
                        <li><a href="<?= $basepath ?>/admin/admin_listSel.php" id="current">Sel List</a></li>
                        <li><a href="<?= $basepath ?>/admin/admin_listUser.php" id="current">User List</a></li>


                    </ul>
                </li>

            <?php } else { ?>
                <li><a href="#" id="current">&nbsp&nbsp&nbsp</a></li>
            <?php } ?>


            <?php
		
            if (isset($_SESSION['member_Session_id'])) {
							?>
			
				
                <li><a href="<?= $basepath ?>/member/mem_logout.php" id="current">logout</a></li>

                <li><a href="<?= $basepath ?>/mileage_View/view_myMileage.php"
                       id="current"><?php echo $_SESSION['member_Session_id']; ?>님 접속중</a></li>

                <?php
            }else { ?>
                <li><a href="<?= $basepath ?>/member/mem_login.php" id="current">login</a></li>
                <li><a href="<?= $basepath ?>/member/mem_regForm.php" id="current">register</a></li>

            <?php } ?>


        </ul>
    </div>
</div>
</body>
</html>


<div id='contents'>
    <!--아래는 content 들어갈 공간 -->
