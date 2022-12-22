<?php
$rgNumber1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9");
$rgNumber2 = array("1", "2", "3", "4", "5", "6", "7", "8", "9");
foreach($rgNumber1 as $nKey => $nVal){
	echo "".$nVal." 단<br>";	
	foreach($rgNumber2 as $nKey2=>$nVal2){
		echo $nVal ." X ". $nVal2 ." = ".($nVal*$nVal2)."<br>";
		}
}
?>