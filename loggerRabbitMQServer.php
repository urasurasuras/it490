#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php');

// $loggerClient = new LoggerClient(__FILE__);
// set_error_handler(array($loggerClient, 'onError'));


$loggerServer = new LoggerServer("server");

// echo "loggerRabbitMQServer BEGIN".PHP_EOL;
// $loggerServer->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;

exit();

/*
 * FUNCTION DEFINITIONS
 */



/**
 * Log This
 * 
 * @param $request The request to be logged
 * 
 */
// function logThis($request) 
// {  
//   $localLoggerServer = $GLOBALS['loggerServer'];
//   $localLoggerServer->logg($request);

//   return "'".$request."' was logged out to system.log at ".date(DATE_RFC2822).".";
// }
?>

