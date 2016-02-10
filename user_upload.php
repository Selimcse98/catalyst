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
} //else echo "Database connectin successful\n";

if(empty($argv[1])){
	echo "You must provide a valid argument\ntype --help for necessary Help\n";
}else if( $argv[1] == "--file"){
    if(($argv[2] == "") || ($argv[2] ==' ')){
        echo "Please provide file name as second argument";
        return;
    } else {
    	$fileName = $argv[2];
    	echo "Input file is: ".$fileName."\n";
    }
} else if( $argv[1] == "--create_table"){
	create_table($conn,$tableName);
} else if( $argv[1] == "--dry_run"){
	echo "Host Server ".$servername."\n".
		 "Database name: ".$dbname."\n".
		 "File Name: ".$fileName."\n".
		 "Talbe Name: ".$tableName."\n";
} else if( $argv[1] == "--update_table"){
	readFileAndUpdateDbTable($dbname,$conn,$tableName,$fileName);	
} else if( $argv[1] == "--help"){
	echo "The PHP script command line options (directives):\n".
		 "--file [csv file name] - this is the name of the CSV to be parsed \n".
		 "--create_table - this will cause the MySQL users table to be built (and no further action will be taken) \n".
		 "--dry_run – this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered. \n".
		 "--update_table //this will parse info from users.csv file and insert into users table\n".
 		 "-u - MySQL username\n-p - MySQL password\n-h - MySQL host\n";
}else if($argv[1] == "-u"){
	echo "MySQL username is: ".$username."\n";
}else if($argv[1] == "-p"){
	echo "MySQL password is: ".$password."\n";
}else if($argv[1] == "-h"){
	echo "MySQL host server is: ".$servername."\n";
}else echo "No valid argument provided\n --help for Help info";
                                                


function is_valid_email($email) {
 return preg_match('/^(([^<>()[\]\\.,;:\s@"\']+(\.[^<>()[\]\\.,;:\s@"\']+)*)|("[^"\']+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\])|(([a-zA-Z\d\-]+\.)+[a-zA-Z]{2,}))$/', $email); 
}

function titleCase($string) 
{
    $string = strtolower($string);
    $string = ucwords($string);
    return $string; 
}

function readFileAndUpdateDbTable($dbname,$conn,$tableName,$fileName){
    $myfile = fopen($fileName, "r") or die("Unable to open file!");
    if($myfile) {
        while (($line = fgets($myfile)) !== false) { //while(!feof($myfile))
            $myArray = explode(',', $line);
            $name = titleCase(trim($myArray[0],"' '\'\""));
            if($name == "Name")
                continue;
            $surname = titleCase(trim($myArray[1],"' '\'\""));
            $surname = str_replace("O'h","O\'H", $surname);
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
}

function create_table($conn,$tableName){
    //GLOBAL $tableName;
    $sql = "DROP TABLE IF EXISTS $tableName;";
    if ($conn->query($sql) === TRUE) {
        echo "Table $tableName has been deleted successfully\n";
    } else {
        echo "Error: " . $sql . "\n" . $conn->error."\n";
    }

    $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
           `name` varchar(50) NOT NULL,
           `surname` varchar(50) NOT NULL,
           `email` varchar(50) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    if ($conn->query($sql) === TRUE) {
        echo "Table $tableName has been created successfully\n";
    } else {
        echo "Error: " . $sql . "\n" . $conn->error."\n";
    }
    
    $sql = "ALTER TABLE `$tableName` ADD UNIQUE KEY `email` (`email`) COMMENT 'email must be unique for each user';";
    if ($conn->query($sql) === TRUE) {
    	echo "Table $tableName altered making email field unique\n";
    } else {
    	echo "Error: " . $sql . "\n" . $conn->error."\n";
    }
}

$conn->close(); 
?>