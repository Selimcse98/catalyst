<?php
for ($num = 1; $num <= 100; $num++) {
	if(($num % 3 == 0) && ($num % 5 != 0))
		echo "triple ";
	else if(($num % 3 != 0) && ($num % 5 == 0))
		echo "fiver ";
	else if(($num % 3 == 0) && ($num % 5 == 0))
		echo "triplefiver ";
	else
    		echo "$num ";
}
?> 