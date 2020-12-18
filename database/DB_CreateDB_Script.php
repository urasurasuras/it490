#!/usr/bin/php
<?php
$libDir = dirname(__FILE__)."/../libs/";
$cfgDir = dirname(__FILE__)."/../cfg/";

require_once($libDir.'logger.inc');

$logger = new LoggerClient(__FILE__);
set_error_handler(array($logger, 'onError'));

echo basename(__FILE__)." BEGIN".PHP_EOL;

$hostname = 'localhost';
$user = 'testuser';
$password = '12345';
$db_name = 'it490';
$table_name_users = 'users';
$table_name_bookings = 'bookings';

// Create connection
$conn = new mysqli($hostname,$user,$password);
// Check connection.
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE ".$db_name;
if ($conn->query($sql) === TRUE) {
  echo "Database ".$db_name." created successfully";
} else {
  echo "Error creating database: " . $conn->error;
}
echo PHP_EOL;

$conn-> select_db($sql);

$query = "CREATE TABLE IF NOT EXISTS ".$db_name.".".$table_name_users." (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    hash VARCHAR(128) NOT NULL,
    bnet     VARCHAR(20),
	private BOOLEAN,
	skillRating SMALLINT
    )";
    
if ($conn->query($query) === TRUE) {
    echo "Table ".$table_name_users." created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
echo PHP_EOL;

$query2 = "CREATE TABLE IF NOT EXISTS ".$db_name.".".$table_name_bookings."
	(id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY 		
	KEY, name VARCHAR(30) NOT NULL, email VARCHAR(50) NOT NULL, date DATE)";

if ($conn->query($query2) === TRUE) {
    echo "Table bookings created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
echo PHP_EOL;

$conn->close();

?>
