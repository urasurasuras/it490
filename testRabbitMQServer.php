#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php');

$logger = new LoggerClient(__FILE__);
set_error_handler(array($logger, 'onError'));

//  This test server only prints out whatever array it gets
function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }

  $client = new rabbitMQClient('testRabbitMQ.ini','API_Client');
  // $request = array();
  $request['bnet'] = $request['bnet'];
  $request['platform'] = 'pc';
  $request['region'] = 'us';
  
  $response = $client->send_request($request);
  print_r($response);

  return array("returnCode" => '0', "rating"=>$response['rating']);
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

