#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php');

$logger = new LoggerClient(__FILE__);
$loggerServer = new LoggerServer(__FILE__."_distributed");

set_error_handler(array($logger, 'onError'));

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $username = $argv[1];
  $password = $argv[2];
  $msg = $argv[3];
}
else
{
  $msg = "test message";
}

$request = array();
$request['type'] = "login";
$request['username'] = $username;
$request['password'] = $password;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);
$logger->logg("it's snowing on mount Fuji");

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

?>