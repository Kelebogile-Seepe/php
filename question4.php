<?php
$year=10;
$AnnualIntrest=0.5;

if($year>=1 && $year<=10){
	$investmentamount= 1000;
}
elseif($year>10){
	$investmentamount= 1500;
}

$totalinvestAmount= $investmentamount + ($investmentamount*0.5);
$totalintrest= $investmentamount * 0.5;

$YearInvestment = 
$totalinvestAmount*$year;


echo "total amount at the end of each year: $totalinvestAmount" ; 
echo "total of investment Intrest: $totalintrest";
echo "Total amount in the period worked:$YearInvestment "
?>