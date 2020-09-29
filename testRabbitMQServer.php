#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

echo "runnning".PHP_EOL;

function doLogin($username,$password) 
{  
   
 $mydb = new mysqli('25.6.86.226','root','12345','usersDB');

  if ($mydb->errno != 0)
  {
    echo "failed to connect to database: ". $mydb->error . PHP_EOL;
    exit(0);
  }else{
    echo "successfully connected to database".PHP_EOL;
  }

  $queryUName = "select * from users where username='".$username."';"; 
  $responseUName = $mydb->query($queryUName);
 
  if (!empty($responseUName) && $responseUName->num_rows == 0) {
    // NOTIFICATION THAT NO USERNAME MATCH FOUND
    echo $username." was not found in records.";
    //return true;
    return array("returnCode" => '0', 'message'=>$username." was not found in records.");
  }else{
    $queryUPass = "select * from users where password='".$password."';";
    $responseUPass = $mydb->query($queryUPass);
    if (!empty($responseUPass) && $responseUPass->num_rows == 1)
    {
      echo $username." was found in records.";
      //return true;
      return array("returnCode" => '0', 'message'=>$username." was found in records.");
    }else{
      // NOTIFICATION OF INCORRECT PASSWORD
      echo "Incorrect password for ".$username;
      //return true;
      return array("returnCode" => '0', 'message'=>"Incorrect password for ".$username);
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
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

