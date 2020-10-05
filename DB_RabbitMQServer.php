#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php');

$logger = new Logger(__FILE__);
set_error_handler(array($logger, 'onError'));

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
// Connect to MySQL only once in the beginning 
$mydb = new mysqli('127.0.0.1','testuser','12345','testdb');

/**
 * REMEMBER TO CREATE A DATABASE - use these commands from root
 * create user 'testuser'@'localhost' identified by '12345';
 * create database testdb;
 * grant all privileges on testdb.* to 'testuser'@'localhost';
 * flush privileges;
 * use testdb;
 * create table users (id int NOT NULL, username varchar(20), password varchar(20), primary key (id));
 * insert into users (id, username, password) values ('1', 'testuser', 'testpass');
 */

if ($mydb->errno != 0)
{
  echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
  exit(0);
}else{
  echo "successfully connected to database".PHP_EOL;
}

$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
$logger->close_logger();

exit();

/*
Function Definitions
*/

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

/**
 * Do Login
 * 
 * @param $username Username
 * @param $password Password
 * 
 * @return type 0 on success, 1 on no user, 2 on wrong pass
 */
function doLogin($username,$password) 
{  
  $localLogger = $GLOBALS['logger'];
  $DB = $GLOBALS['mydb']; // Locally reference the globally defined connection
  $responseArray = array();// Init response array

  // Get the whole row for asked username
  $queryUSER = "SELECT * FROM users WHERE username='".$username."';"; 
  $responseUSER = $DB->query($queryUSER); // Get response from using query
  $row = $responseUSER->fetch_assoc();    // Turn response into array
  echo "Looking for: ".$row["username"].": ".$row["password"]."...".PHP_EOL;
  $localLogger->logg("this is from ".__FILE__);
  
  if (!$row){// If user doesn't exist
    $responseArray['returnCode']  = '1';
    $responseArray['message']     = $username." was not found in our database.";
    $localLogger->logg("Username not found");
  }
  else{// If user does exist
    if ($row['password'] != $password){// If password does not match
      $responseArray['returnCode']  = '2';
      $responseArray['message']     = "Incorrect password for ".$username.".";  
      $localLogger->logg("wrong password for the username");
    }else{// If password does match
      $responseArray['returnCode']  = '0';
      $responseArray['message']     = $username." was found in our database.";
      $localLogger->logg("found you bitch");
    }
  }

  echo "Response array: ".PHP_EOL;
  print_r($responseArray); 
  return $responseArray;// Always return a response array, if it's empty then we know
}
?>

