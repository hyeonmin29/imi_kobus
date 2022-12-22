<?php
//Exception 클래스 -> 파일열기 안됐을 때 toString을 사용해서 출력
class CfileOpenException extends Exception
{
	function __toString(){
		return "CfileOpenException ". $this->getCode()
			. ": ". $this->getMessage()."<br />"
			."in ". $this->getFile()
			."on line ". $this->getLine(). "<br />";
	}
}
//Exception 클래스 -> 파일쓰기 안됐을 때 toString을 사용해서 출력
class CfilewriteException extends Exception
{
	function __toString()
	{
		return "CfileWriteException ".$this->getCode()
			. ": ". $this->getMessage()."<br />"
			. "in ". $this->getFile()
			. "on line ". $this->getLine(). "<br />";
	}
}
//Exception 클래스 -> 파일락 안됐을 때 toString을 사용해서 출력
class CfileLockException extends Exception
{
	function __toString()
	{
		return "CflieLockexception ".$this->getCode()
			. ": ". $this->getMessage()."<br />"
			. "in ". $this->getFile()
			. "on line ". $this->getLine(). "<br />";
	}
}
?>

