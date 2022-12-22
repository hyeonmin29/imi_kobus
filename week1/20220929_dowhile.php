<?php
$nNumber = 1;
$nSum = 0;
do{
    echo $nNumber."까지 누적합 ";
    $nSum +=$nNumber;
    $nNumber++;
    echo "=$nSum <br>";
}while($nNumber <= 10);
echo "1부터 10까지 합 = $nSum<br>";
?>