<?php
///세션 삭제
///session_start(); ->세션을 삭제하기 위해서는 세션을 시작하고 삭제해야함
///특정변수 삭제 ->uset($_SESSION['세션변수명']);
session_start();
session_destroy();                  // 살아있는 세션을 모두 없앤다.

///경고문으로 Good Bye!. 출력
echo "<script>
alert(\"Good Bye!.\");
window.open('./dr2.html','drdr','width=600,height=600,top=100,left=100');
</script>";



///인덱스 페이지로 이동
echo("<script>location.href='../index.php';</script>");





?>