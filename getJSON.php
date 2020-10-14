#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('logger.php');

ini_set("allow_url_fopen", 1);

echo __FILE__."BEGIN".PHP_EOL;

$server = new rabbitMQServer("testRabbitMQ.ini","loggerServer");

$server->process_requests('requestProcessor');
echo __FILE__."END".PHP_EOL;
$logger->close_logger();

exit();

/*

*/ 
function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['bnet']))
  {
    return "ERROR: unsupported message type";
  }

  $btag = str_replace("#", "-", $request['bnet']);
  echo $btag.PHP_EOL;
  $url = 'https://ow-api.com/v1/stats/'.$request['platform'].'/'.$request['region'].'/'.$btag.'/complete';
  echo "URL: ".$url.PHP_EOL;

    $json = file_get_contents($url);
    $obj = json_decode($json);

    print_r( $obj->rating);


  return array("rating" => $obj);
}

// print_r($json);
?>