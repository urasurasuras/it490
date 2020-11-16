#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php');

$logger = new LoggerClient(__FILE__);
set_error_handler(array($logger, 'onError'));

$client = new rabbitMQClient("testRabbitMQ.ini","testClient");
if (isset($argv[1]))
{
  $type = $argv[1];
  $username = $argv[2];
  $password = $argv[3];
  $bnet = $argv[4];
}
else
{
  trigger_error("Too few args bruv");
  exit(0);
  // $bnet = "Tekircan#2533";
}

$request = array();
$request['type'] = $type;
$request['username'] = $username;
$request['password'] = $password;
$request['bnet'] = $bnet;
$request['platform'] = 'pc';
$request['region'] = 'us';

$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

?>