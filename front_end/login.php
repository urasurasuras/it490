#!/usr/bin/php
<?php
// require_once('rabbitMQLib.inc');
// require_once('logger.php');
// $logger = new LoggerClient(__FILE__);

// set_error_handler(array($logger, 'onError'));

// $logger->logg("something1");
// echo 'part 1'.PHP_EOL;

//Unit test

//


if (!isset($_POST))
{
	$msg = "NO POST MESSAGE SET, POLITELY FUCK OFF";
	echo json_encode($msg);
	exit(0);
}
// $logger->logg("something2");

$request = $_POST;
$response = "unsupported request type, politely FUCK OFF";
switch ($request["type"])
{
	case "login":		
		$response = /*SendToRabbit($request["uname"], $request["pword"]);*/"yes sir";
	break;
}
echo json_encode($response);
exit(0);

// function SendToRabbit($name, $pass){
// 	$localLoggerClient = $GLOBALS['logger'];
// 	$localLoggerClient->logg("message from mt fuji");

// 	$client = new rabbitMQClient("testRabbitMQ.ini","testClient");
// 	if (isset($argv[1]))
// 	{
// 		$msg = $argv[1];
// 	}
// 	else
// 	{
// 		$msg = "test message";
// 	}

// 	$req = array();
// 	$req['type'] = "login";
// 	$req['username'] = $name;
// 	$req['password'] = $pass;
// 	$req['message'] = $msg;
// 	$resp = $client->send_request($req);
// 	// //$response = $client->publish($request);

// 	// echo "client received response: ".PHP_EOL;
// 	// print_r($resp);
// 	// echo "\n\n";

// 	// echo $argv[0]." END".PHP_EOL;
// 	return $resp;
// }
?>
