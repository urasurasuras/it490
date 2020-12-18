#!/usr/bin/php
<?php
$libDir = dirname(__FILE__)."/../libs/";
$cfgDir = dirname(__FILE__)."/../cfg/";

require_once($libDir.'path.inc');
require_once($libDir.'get_host_info.inc');
require_once($libDir.'rabbitMQLib.inc');
require_once($libDir.'logger.inc');

$logger = new LoggerClient(__FILE__);
set_error_handler(array($logger, 'onError'));

echo basename(__FILE__)." BEGIN".PHP_EOL;

$server = new rabbitMQServer($cfgDir."testRabbitMQ.ini","testServer");

$hostname = 'localhost';
$user = 'testuser';
$password = '12345';
$db_name = 'it490';
$table_name = 'users';

// Create connection
$conn = new mysqli($hostname,$user,$password, $db_name);
// Check connection.
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


/**
 * REMEMBER TO CREATE A DATABASE - use these commands from root
 * create user 'testuser'@'localhost' identified by '12345';
 * create database testdb;
 * grant all privileges on testdb.* to 'testuser'@'localhost';
 * flush privileges;
 * use testdb;
 * create table users (id int NOT NULL, username varchar(20), hash varchar(20), primary key (id));
 * insert into users (id, username, hash) values ('1', 'testuser', 'testpass');
 * 
 * TODO: Come up with more sophisticated 'returnCode's
 */

if ($conn->errno != 0)
{
  echo __FILE__.':'.__LINE__.":error: ".$conn->error.PHP_EOL;
  exit(0);
}else{
  echo "successfully connected to database".PHP_EOL;
}

$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
$logger->close_logger();

exit();

/*
Function Definitions
*/

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
    case "getJSON":
      return doGetJSON($request['username'],$request['password']);
    case "register":
      return doRegister($request['username'],$request['password'],$request['bnet']);
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '-1', 'message'=>"Server received request and processed");
}

/**
 * Do Login
 * 
 * @param $username Username
 * @param $password Password
 * 
 * @return type 0 on success, 1 on no user, 2 on wrong pass
 */
function doLogin($username,$password) 
{  
  $localLogger = $GLOBALS['logger'];
  $DB = $GLOBALS['conn']; // Locally reference the globally defined connection
  $responseArray = array();// Init response array
  // Get the whole row for asked username
  $queryUSER = "SELECT * FROM users WHERE username='".$username."';"; 
  $responseUSER = $DB->query($queryUSER); // Get response from using query
  $row = $responseUSER->fetch_assoc();    // Turn response into array
  echo "Looking for: ".$row["username"].": ".$row["password"]."...".PHP_EOL;
  
  if (!$row){// If user doesn't exist
    $responseArray['returnCode']  = '1';
    $responseArray['message']     = $username." was not found in our database.";
    $localLogger->logg("Username not found");
  }
  else{// If user does exist
	$hashed = hash('sha512', $password);

  echo $hashed.PHP_EOL;
    if ($hashed != $row['hash']){// If password does not match
      $responseArray['returnCode']  = '2';
      $responseArray['message']     = "Incorrect password for ".$username.".";  
      $localLogger->logg("wrong password for the username");
    }else{// If password does match
      
      if(isset($row['bnet'])){// If bnet is NOT null
        $responseArray['bnet'] = $row['bnet'];
      }
      
      if(isset($row['skillRating'])){// If SR is NOT null
        $responseArray['rating'] = $row['skillRating'];
      }

      $responseArray['returnCode']  = '0';
      $responseArray['message']     = $username." was found in our database.";
      $responseArray['username']    = $username;

    }
  }

  echo "Login response: ".PHP_EOL;
  print_r($responseArray); 
  return $responseArray;// Always return a response array, if it's empty then we know
}

/**
 * Register user:
 * First checks if user exists by running doLogin
 * Otherwise registers input as a new user
 * 
 * @param $username Username
 * @param $password Password
 * 
 * @return array User info and action taken (existing login or new registered user)
 */
function doRegister($username,$password, $bnet) {
  $localLogger = $GLOBALS['logger'];
  $DB = $GLOBALS['conn']; // Locally reference the globally defined connection
  $responseArray = array();// Init response array
  

  $login = doLogin($username,$password);
  
  	$hashed = hash('sha512', $password);
	echo "Hashed password: ".$hashed.PHP_EOL;

  if($login['returnCode'] == '0'){// If user was found in DB
    $responseArray['returnCode']  = '0';
    $responseArray['message']     = 'User: '.$login['username'].' was found';
    $responseArray['username']    = $username;
    $responseArray['password']    = $password;
    $responseArray['bnet']        = $bnet;

    if(isset($login['bnet'])){
      $responseArray['bnet'] = $login['bnet'];
    }
  }
  else{
    $registerQuery = "INSERT INTO users (username, hash, bnet) VALUES ('".$username."', '".$hashed."', '".$bnet."')";
    $result = $DB->query($registerQuery);
    $responseArray['returnCode']  = '0';
    $responseArray['message']     = 'User: '.$username.' was registered with BNET: '.$bnet;
    $responseArray['username']    = $username;
    $responseArray['password']    = $hashed;
    $responseArray['bnet']        = $bnet;
  }

  echo "Register response: ".PHP_EOL;
  print_r($responseArray); 
  return $responseArray;// Always return a response array, if it's empty then we know
}

/**
 * Opens a client to the DMZ, which gets a JSON formatted response from:
 * https://ow-api.com/
 * 
 * @param $username Username
 * @param $password Password
 * 
 * @return array 0 if user exists, rating info is user has bnet
 * 1 if user doesn't exist
 * */
function doGetJSON($username,$password){
  $localLogger = $GLOBALS['logger'];
  $DB = $GLOBALS['conn']; // Locally reference the globally defined connection
  $responseArray = array();// Init response array

  $login = doLogin($username,$password);

  if($login['returnCode'] == '0'){// If user was found in DB

    $responseArray['returnCode']  = '0';
    $responseArray['message']     = 'User: '.$login['username'].' was found';

    if(!empty($login['bnet'])){
      $responseArray['bnet'] = $login['bnet'];

      $client = new rabbitMQClient('testRabbitMQ.ini','API_Client');
      
      $request['bnet'] = $login['bnet'];
      $request['platform'] = 'pc';
      $request['region'] = 'us';
      
      $response = $client->send_request($request);

      echo "Info from ".$login['bnet'].PHP_EOL ;
      print_r($response);
    }
    else{// user has no BNET asssinged
      $responseArray['message']     = 'User: '.$login['username'].' was found with no Battle.net ID assigned to it';
    }
  }else{// If user was not found
    $responseArray['returnCode']  = '1';
    $responseArray['message']     = 'User: '.$login['username'].' was NOT found';
  }

  echo "Response from DMZ: ".PHP_EOL;
  print_r($responseArray); 
  return $responseArray;
}

?>

