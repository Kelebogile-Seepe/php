<?php

$student = "Kelebogile";
$age = 25;
$marks = 4;
$ispass = true;



if ($marks <= 100 && $marks >=90){
	echo "$ispass";
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

echo "Student name : $student <br>";
echo "age: $age <br>";
echo "he got the mark: $marks <br>";
echo "did he pass: " . ($ispass ? "Yes": "No");

?>