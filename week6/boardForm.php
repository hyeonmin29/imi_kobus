<html>
<body>
<h3>글 작성 폼</h3>
<form action = 'board.php' method = 'POST'>
<li><td>작성자 : <td><td><input type="text" name="writer1" size = "15"></td></br></li>
<li><td>제목  : <td><td><input type="text" name="title" size = "15"></td></br></li>
<li><td>내용  : <td><td><input type="text" name="text" size = "30"></td> </br></li>


<input type="submit" value="글 올리기"/>
<input type="button" value ="홈으로 돌아가기"
onclick = "window.location='http://192.168.56.116/week6/boardMain.php'">
</form>
</body>
</html>