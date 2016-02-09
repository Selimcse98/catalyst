<?php
$myfile = fopen("users.csv", "r") or die("Unable to open file!");
// Output one line until end-of-file
while(!feof($myfile)) {
  $line = fgets($myfile);
  if(substr($line, 0, 4)=="name")
	continue;
 else  echo $line . "\n";
}
fclose($myfile);
?> 