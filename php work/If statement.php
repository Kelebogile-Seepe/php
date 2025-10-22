<?php
$marks= 100;

if ($marks <= 100 && $marks >=90){
	echo "A+";
}
elseif ($marks <= 89 && $marks >=80){
	echo "A";
}

elseif ($marks <= 79 && $marks >=70 ){
	echo "B";
}
elseif ($marks <= 69 && $marks >=60 ){
	echo "C";
}
elseif ($marks <= 59 && $marks >=50){
	echo "D";
}
else {
	echo  "students failed";
}
?>