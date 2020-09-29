#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username,$password)
{
  // Connect to DB
  // $mydb = new mysqli('25.6.86.226','root','12345','testdb');

  // Error check
  // if ($mydb->errno != 0)
  // {
  //   echo "failed to connect to database: ". $mydb->error . PHP_EOL;
  //   exit(0);
  // }else{
  //   echo "successfully connected to database".PHP_EOL;
  // }

  // lookup username in database
  // $query = "select * from students;";

  // check password
  return true;
  //return false if not valid
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

