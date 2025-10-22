<?php

$totalsalary =22000;
$years = 20;

if ($years>=10){
	$bonus = 0.15;
}
elseif ($years<=9 && $years>= 5){
	$bonus = 0.10;
}

elseif ($years<5){
	$bonus = 0.5;
}

$totalbonussalary = $totalsalary + ($totalsalary * $bonus);
$bonusamount = $totalsalary * $bonus; 
echo "The total bonus is: $totalbonussalary  : $bonusamount";




?>