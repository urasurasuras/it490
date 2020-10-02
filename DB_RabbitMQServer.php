#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
// Connect to MySQL only once in the beginning 
$mydb = new mysqli('127.0.0.1','testuser','12345','testdb');

if ($mydb->errno != 0)
{
  echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
  exit(0);
}else{
  echo "successfully connected to database".PHP_EOL;
}

$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();

/*
Function definitions
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
  $DB = $GLOBALS['mydb'];// Locally reference the globally defined connection

  $queryUName = "select * from users where username='".$username."';"; 
  $responseUName = $DB->query($queryUName);
 
  $responseArray = array();
  if (!empty($responseUName) && $responseUName->num_rows == 0) {
    // NOTIFICATION THAT NO USERNAME MATCH FOUND
    // echo $username." was not found in records.";
    
    $responseArray['returnCode']  = '0';
    $responseArray['message']     = $username." was not found in records.";
  }
  else{
    //FIXME: This query returns ALL users with the same password, 
    //should look for only $username 's password instead
    $queryUPass = "select * from users where password='".$password."';";
    
    $responseUPass = $DB->query($queryUPass);
    if (!empty($responseUPass) && $responseUPass->num_rows == 1)
    {
      // echo $username." was found in records.";
      
      $responseArray['returnCode']  = '1';
      $responseArray['message']     = $username." was found in records.";
      
    }else{
      // NOTIFICATION OF INCORRECT PASSWORD
      // echo "Incorrect password for ".$username;
      
      $responseArray['returnCode']  = '2';
      $responseArray['message']     = "Incorrect password for ".$username;  
    }

    echo "Response array: ".PHP_EOL;
    print_r($responseArray);
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

