<?php

@ob_start(PHP_OUTPUT_HANDLER_CLEANABLE, PHP_OUTPUT_HANDLER_REMOVABLE);
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php');

//$logger = new LoggerClient(__FILE__);
//set_error_handler(array($logger, 'onError'));
//$logger->logg("test Log");
$client = new rabbitMQClient("testRabbitMQ.ini","testClient");//Check this part in the .ini file

if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}

$request = array();
$request['type'] = $_POST["type"];
$request['username'] = $_POST['username'];
$request['password'] = $_POST['password'];
$request['bnet'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);
@ob_end_clean();
//print_r($response);
$test = "testResponse";
echo json_encode($response);
exit(0);
?>
