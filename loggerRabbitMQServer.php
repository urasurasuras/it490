#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php');

$logger = new Logger("system");
set_error_handler(array($logger, 'onError'));

$server = new rabbitMQServer("testRabbitMQ.ini","testServer"); // Change to log server or something

echo "loggerRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
$logger->close_logger();

exit();

/*
 * FUNCTION DEFINITIONS
 */

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  /*
   * var_dump($request);
   *
   * if(!isset($request['type']))
   * {
   *    return "ERROR: unsupported message type";
   * }
   * switch ($request['type'])
   * {
   *  case "login":
   *    return doLogin($request['username'],$request['password']);
   *  case "validate_session":
   *    return doValidate($request['sessionId']);
   * }
   */
  return logThis($request);
  //return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

/**
 * Log This
 * 
 * @param $request The request to be logged
 * 
 */
function logThis($request) 
{  
  $generalLogger = $GLOBALS['logger'];
  $generalLogger->logg($request);
  
  return "'".$request."' was logged out to system.log at ".date(DATE_RFC2822).".";
}
?>

