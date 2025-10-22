<?php
$amount = 450;

if ($amount >= 1000){
	$discount= 0.20;
}

elseif ($amount >= 500){
	$discount = 0.10;
}

else{
	$discount = 0.5;
}
$finalamount= $amount - ($amount * $discount);
echo "Orginal Amount: $amount, final amount after discount: $finalamount";
?>