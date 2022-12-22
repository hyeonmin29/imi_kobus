<?php

session_start();
//include('../css/style.css');
$basepath="http://localhost/";
$admin_checker = $_SESSION['member_Session_admin'];
?>

<!DOCTYPE HTML>
<html>
<link rel="stylesheet" href="../css/style.css" type="text/css"/>
<head >
    <title>home</title>

</head>

<body>


<h2>Shoes Shop</h2>




<div id='header'>
    <div class="menubar">
        <ul>

            <li><a href="<?php $basepath?>/./index.php" id="current">Home</a></li>
            <li><a href="<?php $basepath?>/member/mem_regForm.php" id="current">memberReg</a></li>
            <li><a href="<?php $basepath?>/mileage/mil_charging.php" id="current">mileage charging</a></li>
            <li><a href="<?php $basepath?>/sel/sel_regForm.php" id="current"> sel reg form</a></li>
            <li><a href="#" id="current">&nbsp&nbsp&nbsp</a></li>
            <li><a href="#" id="current">&nbsp&nbsp&nbsp</a></li><!--blank menu-->
            <li><a href="#" id="current">&nbsp&nbsp&nbsp</a></li>

            <?php
            if ($admin_checker==1){
                ?>
                <li><a href="#" id="current">admin page</a></li>

            <?php }else{ ?>
                <li><a href="#" id="current">&nbsp&nbsp&nbsp</a></li>
            <?php }?>


            <?php

            if (isset($_SESSION['member_Session_id']))
            {      ?>



                <li><a href="<?php $basepath?>/member/mem_logout.php" id="current">logout</a></li>

                <li><a href="<?php $basepath?>/member/mem_mymil.php" id="current"><?php echo $_SESSION['member_Session_id'];?>접속중</a></li>

                <?php
            }else{      ?>
                <li><a href="<?php $basepath?>/member/mem_login.php" id="current">login</a></li>

            <?php }?>


        </ul>
    </div>
</div>
</body>
</html>





<div id='contents'>
    <!--아래는 content 들어갈 공간 -->
