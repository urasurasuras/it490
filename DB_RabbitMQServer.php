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

function doLogin($username,$password) 
{  
  $doLoginLogger = $GLOBALS['logger'];
  $DB = $GLOBALS['mydb']; // Locally reference the globally defined connection
  $responseArray = array();
  $queryUName = "SELECT username FROM users WHERE username='".$username."';"; 
  $doLoginLogger->logg(__LINE__." ".$queryUName); // DEBUGGING?
  $responseUName = $DB->query($queryUName);
   
  if (empty($responseUName)){
    // NOTIFICATION THAT NO USERNAME MATCH FOUND    
    $responseArray['returnCode']  = '1';
    $responseArray['message']     = $username." was not found in our database.";
    $doLoginLogger->logg("Username not found");
  }
  else{
    $queryUPass = "SELECT * FROM users WHERE username='".$username."' AND password='".$password."';";
    $responseUPass = $DB->query($queryUPass);
    if (empty($responseUPass)){
      // NOTIFICATION OF INCORRECT PASSWORD    
      $responseArray['returnCode']  = '2';
      $responseArray['message']     = "Incorrect password for ".$username.".";  
      $doLoginLogger->logg("wrong password for the username");
      
    }else{
      // echo $username." was found in records.";

      $responseArray['returnCode']  = '0';
      $responseArray['message']     = $username." was found in our database.";
      $doLoginLogger->logg("found you bitch");
    }

    echo "Response array: ".PHP_EOL;
    echo $responseArray;
    print_r($responseArray); // Just added at 10:27 PM
    return $responseArray;// Always return a response array, if it's empty then we know
  }

  /** 
  *if ($response->num_rows > 0){
  *  // output each row
  *  while($row = $response->fetch_assoc()){
  *    echo "id: ".$row["id"]." - Name: ".$row["name"].PHP_EOL;
  *  }
  *}else {
  *  echo "no results".PHP_EOL;
  *}
  *
  */
}
?>

