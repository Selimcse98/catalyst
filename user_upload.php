#!/usr/bin/php
<?php
$servername = "localhost";
$dbname = "catalyst_db";
$username = "root";
$password = "root";
$tableName = "users";
$fileName = "users.csv";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else echo "Database connectin successful\n";

$sql = "DROP TABLE IF EXISTS $tableName;";
if ($conn->query($sql) === TRUE) {
	echo "Table $tableName has been deleted successfully\n";
} else {
	echo "Error: " . $sql . "\n" . $conn->error."\n";
}

$sql = "CREATE TABLE IF NOT EXISTS `users` (
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

if ($conn->query($sql) === TRUE) {
	echo "Table $tableName has been created successfully\n";
} else {
	echo "Error: " . $sql . "\n" . $conn->error."\n";
}
			
$myfile = fopen($fileName, "r") or die("Unable to open file!");
if($myfile) {
   while (($line = fgets($myfile)) !== false) { //while(!feof($myfile))
		$myArray = explode(',', $line);
		$name = titleCase(trim($myArray[0]));
		if($name == "name")
			continue;
		$surname = str_replace('\'','\\\'', trim($myArray[1],"' '\'\""));
		echo "Modified surname $surname \n";
		$surname = str_replace(' ','', $surname);
		$surname = str_replace('\"','', $surname);
		$surname = titleCase(str_replace(' ','',$surname));
		$email = trim($myArray[2]);
		if (!is_valid_email($email)){
			echo($email." is a not valid email address, so user info will not be inserted to db \n");
			continue;
		} else {
			$sql = "INSERT INTO `$dbname`.`$tableName` (`name`, `surname`, `email`) VALUES ('$name', '$surname', '$email');";
			if ($conn->query($sql) === TRUE) {
				echo "New record created successfully for $name $surname $email\n";
			} else {
				echo "Error: " . $sql . "\n" . $conn->error."\n";
			}
		}
    }
    fclose($myfile);
	
} else {
    echo "error opening the file.\n";
}

function is_valid_email($email) {
 return preg_match('/^(([^<>()[\]\\.,;:\s@"\']+(\.[^<>()[\]\\.,;:\s@"\']+)*)|("[^"\']+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\])|(([a-zA-Z\d\-]+\.)+[a-zA-Z]{2,}))$/', $email); 
}

function titleCase($string) 
{
	$string = strtolower($string);
	$string = ucwords($string);
	return $string; 
}

$conn->close(); 
?>