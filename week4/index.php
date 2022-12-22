<?php
session_start();
?>
<body>
    <div class="base">
        <h2><?php print $_SESSION['user_id']."님 반갑습니다.<br>
				  번호 : " . $_SESSION['user_no']."<br>
				  생일 : " . $_SESSION['user_birth']."<br>
				  지역 : " . $_SESSION['user_city'];
   			?>
		</h2>
        <button type="button" class="btn" onclick="location.href='logout.php'">
            LOGOUT
        </button>
    </div>
</body>