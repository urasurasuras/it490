#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username,$password) 
{  
   
 $mydb = new mysqli('25.6.86.226','root','12345','testdb');

  if ($mydb->errno != 0)
  {
    echo "failed to connect to database: ". $mydb->error . PHP_EOL;
    exit(0);
  }else{
    echo "successfully connected to database".PHP_EOL;
  }

  $queryUName = "select * from students where name='".$username."';"; 
  $responseUName = $mydb->query($queryUName);
 
  if ($responseUName->num_rows == 0) {
    // NOTIFICATION THAT NO USERNAME MATCH FOUND
    echo "No such username found in records.";
    return true;
  }else{
    $queryUPass = "select * from students where id='".$password."';";
    $responseUPass = $mydb->query($queryUPass);
    if ($responseUPass->num_rows == 1)
    {
      echo "User was found in records.";
      return true;
    }else{
      // NOTIFICATION OF INCORRECT PASSWORD
      echo "Incorrect password.";
      return true;
    }
  }

  if ($mydb->errno != 0)
  {
    echo "failed to execute query:".PHP_EOL;
    echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
    exit(0);
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
  * lookup username in databas
  * check password
  * return true;
  * return false if not valid
  *
  */
}

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
    case "Login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>

