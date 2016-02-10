The PHP script user_upload.php takes user info from file and update into MySQL database.
The script is using localhost database: catalyst_db which will have table users to have user info.

Running the PHP Script from command line
1. php user_upload.php --file users.csv  // if no arguments specified it will consider default file users.csv
2. php user_upload.php --create_table    // this will just create table but no further action will be taken
3. php user_upload.php --dry_run //This will show all necessary info but do not change db
4. php user_upload.php --update_table //this will parse info from users.csv file and insert into users table
5. php user_upload.php --help //This will show necessary help info
6. php user_upload.php -u //will display MySQL username
7. php user_upload.php -p //will display MySQL password
8. php user_upload.php -h //will display MySQL host


Logic Test
1. Run from command line: php counter.php //No argument necessary

which will
• Output the numbers from 1 to 100
• Where the number is divisible by three (3) output the word “triple”
• Where the number is divisible by five (5) output the word “fiver”
• Where the number is divisible by three (3) and five (5) output the word “triplefiver”


==================== MySQL Database commands ========================
-- Database: `catalyst_db`

DROP DATABASE IF EXISTS `catalyst_db`;
CREATE DATABASE IF NOT EXISTS `catalyst_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `catalyst_db`;
COMMIT;

-- Table structure for table `users`

CREATE TABLE IF NOT EXISTS `users` (
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Indexes for table `users`
ALTER TABLE `users`
  ADD UNIQUE KEY `email` (`email`) COMMENT 'email must be unique for each user';
  
INSERT INTO `catalyst_db`.`users` (`name`, `surname`, `email`) VALUES ('John', 'Smith', 'jsmith@gmail.com');