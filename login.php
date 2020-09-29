<?php
require_once('rabbitMQLib.inc');


if (!isset($_POST))
{
	$msg = "NO POST MESSAGE SET, POLITELY FUCK OFF";
	echo json_encode($msg);
	exit(0);
}
$request = $_POST;
$response = "unsupported request type, politely FUCK OFF";
switch ($request["type"])
{
	case "login":
		$response = "login, yeah we can do that";
		SendToRabbit($request['uname'], $request['pword']);
	break;
}
echo json_encode($response);
exit(0);

function SendToRabbit($name, $pass){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	if (isset($argv[1]))
	{
		$msg = $argv[1];
	}
	else
	{
		$msg = "test message";
	}

	$req = array();
	$req['type'] = "Login";
	$req['username'] = $name;
	$req['password'] = $pass;
	$req['message'] = $msg;
	$resp = $client->send_request($req);
	// //$response = $client->publish($request);

	// echo "client received response: ".PHP_EOL;
	// print_r($resp);
	// echo "\n\n";

	// echo $argv[0]." END".PHP_EOL;

}
?>
